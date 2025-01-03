<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
//use Debugbar;
use App\Http\Requests\UpdateFrontClientDetailsRequest;
use App\Imports\MedmalRenewalsImport;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Certificate;
use App\Models\ClientDetail;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Models\Signature;
use App\Notifications\BankTransferGatewayPayment;
use App\Notifications\PaypalGatewayPaymentCancel;
use App\Rules\ProjectDeclarationsRule;
use App\Services\DatasheetToPdfService;
use App\Services\LloydsDoprintService;
use App\Services\DeclarationsToPdfService;
use App\Services\NewApplicationToPdfService;
use App\Services\PaymentCertificateToPdfService;
use App\Services\PaymentBankersToPdfService;
use App\Services\Yousign\YousignApiService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;
use App\Notifications\FinalEmailWithAttachments;


class SiteBuyProductController extends Controller
{
    public function isRenewal(Cart $cart){
        $check = Cart::where('cart_ref', $cart->cart_ref)->first();
        if(empty($check->cart_ref)){
            abort(404);
        }
        $cart_ref = $check->cart_ref;

        $new_cart_ref = DB::select(DB::raw('SELECT new_cart_ref FROM renewals WHERE new_cart_ref = "'.$cart_ref.'";'));
        if(!empty($new_cart_ref)){
            return true;
        }
        else{
            return false;
        }
    }

    public function buy(Product $product, Cart $cart = null)
    {
        $user = auth()->user();

        //DB::statement("UPDATE users SET new_client=1 WHERE id=".$user->id.";");
        if (!empty($cart)) {
            if($this->isRenewal($cart)){
                $expiration_date = date_create($cart->expired_at);
                $now = date_create(date('d-m-Y'));
                $interval = date_diff($expiration_date,$now);
                $interval = $interval->format("%R%a");
                if($interval > -1){
                    abort(419);
                }
            }
            else{
                // get renewal date if exists in previous cart
                $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    })
                    ->latest()->first();

                if($last_prev_certificate != null && $cart->product_step_id < 5){
                    $scadenza = date_create($last_prev_certificate->end_validity_date);
                    date_add($scadenza, date_interval_create_from_date_string('30 days'));
                    $ora = date_create(date('d-m-Y'));
                    $intervallo = date_diff($scadenza,$ora);
                    $intervallo = $intervallo->format("%R%a");

                    $cart->renewal_date = null;
                    $cart->retroactivity = null;
                    $cart->save();

                    if(!($intervallo > -1) && $product->id == 1){
                        abort(308);
                    }
                }

                //if ($last_prev_certificate) {
                    ////$cart->renewal_date = Carbon::parse($last_prev_certificate->end_validity_date)->toDateString();
                    //$cart->renewal_date = null;
                    //$cart->retroactivity = null;
                    //$cart->save();
                //}
            }

            $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

            if($cart->product->product_type_id == 1){
                $next_step = $cart->product_step_id;

                switch ($next_step) {
                    case 1:
                        return $this->step1($product, $cart);
                        break;
                    case 2:
                        return $this->step2($product, $cart);
                        break;
                    case 3:
                        return $this->step3($product, $cart);
                        break;
                    case 4:
                        return $this->step4($product, $cart, request('payment'));
                        break;
                    case 5:
                        return $this->step5($product, $cart, request('documentGeneration'));
                        break;
                    case 6:
                        return $this->step6($product, $cart);
                        break;
                    case 7:
                        return $this->step7($product, $cart);
                        break;
                }
            }
            if($cart->product->id == 2 || $cart->product->id == 3 || $cart->product->id == 4){
                $next_step = $cart->product_step_id;

                switch ($next_step) {
                    case 1:
                        return $this->step1($product, $cart);
                        break;
                    case 2:
                        return $this->step2($product, $cart);
                        break;
                    case 3:
                        return $this->step3($product, $cart);
                        break;
                    case 4:
                        return $this->step4($product, $cart, request('payment'));
                        break;
                    case 5:
                        return $this->step5($product, $cart, request('documentGeneration'));
                        break;
                }
            }
        } else {
            // se cart assente o locked, genero un cart ref e creo il carrello
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_step_id' => 1,
                'expired_at' => now()->addMonths(1),
                'locked' => 'N',
                'cart_ref' => uniqid()
            ]);

            return redirect()->route('front.product.buy', ['product' => $product, 'cart' => $cart]);
        }
    }

    static public function renew(Product $product, Cart $cart, $user_id=null)
    {
        if($user_id == null){
            $user_id = auth()->user();
            $user_id = $user_id->id;
        }

        $check = Cart::where('cart_ref', $cart->cart_ref)->first();
        if(empty($check->cart_ref) || $check->user_id != $user_id){
            abort(404);
        }

        DB::statement("UPDATE users SET new_client=0 WHERE id=".$user_id.";");

        $cart_ref = $check->cart_ref;

        $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    })
                    ->latest()->first();

        $old_cart_end_date = $last_prev_certificate->end_validity_date;

        $end = date_create($old_cart_end_date);
        $today = date_create(date('d-m-Y'));

        $interval = date_diff($end,$today);
        $interval = $interval->format("%R%a");
        if($interval < -30){
            abort(403);
        }

        $new_cart_ref = DB::select(DB::raw('SELECT new_cart_ref FROM renewals WHERE old_cart_ref = "'.$cart_ref.'";'));
        if(!empty($new_cart_ref)){
            return redirect()->route('front.product.buy', ['product' => $product, 'cart' => $new_cart_ref[0]->new_cart_ref]);
        }
        
        $newCart = Cart::create([
            'user_id' => $user_id,
            'product_id' => $product->id,
            'product_offer_id' => $product->product_type_id == 1 ? null : $cart->product_offer_id,
            'product_step_id' => 1,
            'expired_at' => $end->modify('+30 day'),
            'locked' => 'N',
            'cart_ref' => uniqid()
        ]);
        DB::statement('INSERT INTO renewals (old_cart_ref, new_cart_ref) VALUES ("'. $cart->cart_ref.'", "'. $newCart->cart_ref.'");');

        return redirect()->route('front.product.buy', ['product' => $product, 'cart' => $newCart]);
    }

    private function step1(Product $product, Cart $cart)
    {
        $user = auth()->user();
        // TODO verificare che i dati vengano estratti per specifico prodotto
        $arrayProductConditions = array();
        foreach($product->productCondition as $singleCondition){
            array_push($arrayProductConditions, $singleCondition->conditional_field_id);
        }

        $clientDetails = ClientDetail::query()
        ->with(['productClientDetails' => function ($query) use ($arrayProductConditions) {
            $query->whereIn('conditional_field_id', $arrayProductConditions)
                   ->where('field_name', 'like', '%'); // Use the '%' wildcard here
        }])
            ->where([
                'user_id' => $user->id,
            ])
            ->latest('id')
            ->first();
            
        $product = $product->load([
            'productOffers'
        ]);

        if($product->product_type_id == 1){
            $redirect = 'Sites/BuyProduct';
        }
        else{
            $redirect = 'Sites/BuyProductBankers';
        }
        return Inertia::render($redirect, [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'client_details_declaration' => $product->client_details_declaration,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
                'steps' => $product->productSteps->count(),
                'step' => $product->productSteps[0],
            ],
            'cart' => $cart,
            'clientDetails' => $clientDetails
        ]);
    }

    public function store_step1(Product $product, Cart $cart, UpdateFrontClientDetailsRequest $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);
        $validated = $request->validated();
        
        DB::transaction(function () use ($user, $validated, $cart) {
            $clientDetails = ClientDetail::updateOrCreate(['user_id'   => $user->id], $validated);
            $rebuildProductClientDetails = [];
            foreach ($validated['product_client_details'] as $el) {
                $conditional_field_id = $el['pivot']['conditional_field_id'];
                $selected_field_value = $el['pivot']['selected_field_value'];
                $rebuildProductClientDetails[$conditional_field_id] = ['selected_field_value' => $selected_field_value];
            }

            $clientDetails->productClientDetails()->syncWithoutDetaching($rebuildProductClientDetails);

            if($validated['confirmation_sinistri'] == "si" || $validated['confirmation_gmedici1'] == 'si' || $validated['confirmation_gmedici2'] == 'si' || $validated['confirmation_gmedici3'] == 'si' || $validated['confirmation_gmedici4'] == 'si' || $validated['confirmation_gmedici5'] == 'si'){
                if($cart->product->id == 5 || $cart->product->id == 1){
                    abort(480);
                }
                else{
                    $clientDetails->with_accident = true;
                    $clientDetails->save();
                }
            }

            $cart->giorno_completamento_step_1 = now();
            $cart->save();
            $cart->updateCartStep(2);
        });

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step2(Product $product, Cart $cart)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::query($product)
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        $flag_prev_2022 = $clientDetails->flag_prev_2022;

        if($product->product_type_id == 1){
            if($clientDetails->with_accident == true){
                abort(480);
            }

            $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    })
                    ->latest()->first();

            if($last_prev_certificate != null){
                $old_cart_end_date = $last_prev_certificate->end_validity_date;
            }
            else{
                $old_cart_end_date = "08-11-2024";
            }

            $end = date_create($old_cart_end_date);
            $deadline = date_create("08-11-2024");

            $interval = date_diff($end,$deadline);
            $interval = $interval->format("%R%a");



            //interval > 0 significa che hai l'offerta vecchia
            if($interval > 0){
                
                if($flag_prev_2022 == 1){
                    $product = $product->load([
                        'productOffers' => function ($query) use ($flag_prev_2022) {
                            $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022);
                        }
                    ]);
                }
                else{
                    $product = $product->load([
                        'productOffers' => function ($query) use ($flag_prev_2022) {
                            $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022)->whereNotNull('old');
                        }
                    ]);
                }
                for($i=0; $i<count($product->productOffers); $i++){
                    $product->productOffers[$i]->hide = 0;
                }
                //dd($product->productOffers);
            }
            else{
            $flag_prev_2022 = 0;
            $product = $product->load([
                'productOffers' => function ($query) use ($flag_prev_2022) {
                    $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022)->whereNull('old');
                }
            ]);
        }

        }
        else{
            if($clientDetails->with_accident == true){
                abort(480);
            }
            $product = $product->load([
                'productOffers' => function ($query) use ($flag_prev_2022) {
                    $query->with(['conditionalFields', 'contract']);
                }
            ]);
        }

        $conditionalFieldsIds = $product
            ->conditionalFields()
            ->select(['id'])
            ->where('only_client_details', 0)
            ->get()
            ->map(function ($v) {
                return $v['id'];
            });

        $productClientDetails = $clientDetails
            ->productClientDetails()
            ->select(['conditional_field_id', 'selected_field_value'])
            ->whereIn('conditional_field_id', $conditionalFieldsIds)
            ->get();

        $clean = []; // key conditional_field_id, value selected_field_value
        foreach ($productClientDetails as $productClientDetail) {
            $clean[$productClientDetail->conditional_field_id] = $productClientDetail->selected_field_value;
        }

        $offers = $product->productOffers->map(function ($rec) use ($clean, $product) {
            $rec2 = $rec->conditionalFields;
            $flag = true;
            foreach ($rec2 as $bhgh) {
                $conditional_field_id = $bhgh->pivot->conditional_field_id;
                $selected_field_value = $bhgh->pivot->selected_field_value;

                if ($selected_field_value != $clean[$conditional_field_id] && $product->id != 5) {
                    $flag = false;
                }
            }
            if ($flag) {
                return $rec;
            }
        })->filter(function ($value) {
            return $value != null;
        });

        if($product->id == 1){
            $redirect = 'Sites/BuyProduct';
        }
        else{
            $redirect = 'Sites/BuyProductBankers';
        }
        return Inertia::render($redirect, [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $offers,
                'table_elements' => json_decode($product->productOfferTable, true),
                'steps' => $product->productSteps->count(),
            ],
            'cart' => $cart,
            'clientDetails' => $clientDetails
        ]);
    }

    public function store_step2(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'product_offer_id_selected' => ['required', 'integer'],
        ]);

        $productOffer = ProductOffer::findOrFail($request->product_offer_id_selected);
        $cart->total_payable = $productOffer->total_payable;
        $cart->admin_fee = $productOffer->admin_fee;
        $cart->gross_premium = $productOffer->gross_premium;
        $cart->tax = $productOffer->tax;
        $cart->net_premium = $productOffer->net_premium;
        $cart->accessory = $productOffer->accessory;
        $cart->net_plus_access_premium = $productOffer->net_plus_access_premium;
        $cart->indemnity_limit = $productOffer->indemnity_limit;
        $cart->indemnity_limit_patrimonial = $productOffer->indemnity_limit_patrimonial;
        $clientDetails = ClientDetail::query($product)
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        
        $flag_prev_2022 = $clientDetails->flag_prev_2022;
        if($flag_prev_2022 == 1){
            $cart->indemnity_limit_patrimonial = 1000000;
            $cart->save();
        }
        $cart->product_offer_id = $request->product_offer_id_selected;
        $cart->save();

        // genero e salvo il pdf new proposal se new client
        if($product->product_type_id == 1){
            $new_proposal = new NewApplicationToPdfService($cart);
            $new_proposal->generateAndPersist();
        }

        $cart->updateCartStep(3);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step3(Product $product, Cart $cart)
    {
        if($product->id == 1){
            $redirect = 'Sites/BuyProduct';
        }
        else{
            $redirect = 'Sites/BuyProductBankers';
        }
        return Inertia::render($redirect, [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'pre_declarations' => $product->pre_declarations,
                'post_declarations' => $product->post_declarations,
                'declarations' => $product->productDeclarations,
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart
        ]);
    }

    public function store_step3(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'signature' => ['required', 'string', 'min:5'],
            'selected_declarations' => ['required', 'array', new ProjectDeclarationsRule($product->productDeclarations)],
            'selected_declarations.*' => ['integer', 'exists:product_declarations,id'],
        ]);

        // salvo nel db la firma delle dichiarazioni, da usare poi per la generazione del documento
        // nota: il salvataggio avviene poco sotto, nel metodo updateCartStep
        $cart->declarations_signature = $validated['signature'];

        // genero e salvo il pdf delle dichiarazioni
        $declarationToPdf = new DeclarationsToPdfService($cart);
        $declarationToPdf->generateAndPersist();

        $cart->updateCartStep(4);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step4(Product $product, Cart $cart, $payment = null)
    {
        if($cart->payment != null && $cart->payment->paid == 1){
            $cart->updateCartStep(5);
            return redirect()->route('front.product.buy', compact('product', 'cart')); 
        }
        $paypalClientId = config('paypal.' . config('paypal.mode') . '.client_id');
        $productObject = [
            'id' => $product->id,
            'name' => $product->name,
            'type' => Str::slug($product->productType->name, '-'),
            'payments_description' => $product->payments_description,
            'hex_color' => $product->hex_color,
            'steps' => $product->productSteps->count(),
        ];

        if (empty($payment)) {
            $payment_gateways = PaymentGateway::where(['public' => 1])->get();
            $productObject['payment_gateways'] = $payment_gateways;

            if($product->id == 1){
                $redirect = 'Sites/BuyProduct';
            }
            else{
                $redirect = 'Sites/BuyProductBankers';
            }
            return Inertia::render($redirect, [
                'product' => $productObject,
                'cart' => $cart,
                'paypal_client_id' => $paypalClientId
            ]);
        } else {
            $payment_data = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment);
            $productObject['payment'] = $payment_data;

            if($product->id == 1){
                $redirect = 'Sites/BuyProduct';
            }
            else{
                $redirect = 'Sites/BuyProductBankers';
            }
            return Inertia::render($redirect, [
                'product' => $productObject,
                'cart' => $cart,
                'paypal_client_id' => $paypalClientId
            ]);
        }
    }

    public function store_step4(Product $product, Cart $cart, String $gateway, Request $request)
    {
        if($cart->payment != null && $cart->payment->paid == 1){
            $cart->updateCartStep(5);
            return response()->json([
                'reload' => 'reload']);
        }

        if($gateway == 'paypal'){
            $gatewayid = 1;
        }
        else{
            $gatewayid = 2;
        }
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $payment = Payment::firstOrCreate(
            [
                'cart_id' => $cart->id
            ],
            [
                'payment_gateway_id' => $gatewayid,
                'total_payable' => $cart->total_payable,
            ]
        );

            $payment->payment_gateway_id = $gatewayid;
            $payment->save();
        
        if ($gateway == 'paypal') {
            // paypal

            $order = $this->create_order_paypal($cart, $product, $payment);
            //exec("explorer.exe");
            return response()->json([
                'order' => $order,
                'payment_id' => $payment->id,
            ],200);
            //return redirect()->route('front.paypal_redirect', ['url' => $url]);
        } else {
            // bbm
            auth()->user()->notify(new BankTransferGatewayPayment($payment));
            return redirect()->route('front.product.buy', compact('product', 'cart', 'payment'));
        }
    }

    private function create_order_paypal(Cart $cart, Product $product, Payment $payment = null)
    {
        $provider = new PayPalClient;

        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Prepare Order
        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => 'transaction_id_' . $payment->id,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => $cart->total_payable
                ]
            ]]
            /*,
            'application_context' => [
                'cancel_url' => route('front.product.store_step4_paypal_cancel', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment->id
                ]),
                'return_url' => route('front.product.store_step4_paypal_return', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment->id
                ])
            ]*/
        ]);

        //sleep(10);
        

        // Store Token so we can retrieve after PayPal sends them back to us
        $payment->transaction_ref = $order['id'];
        $payment->save();
        $paypal_redirect_url = $order['links'][1]['href'];

        return $order;
    }
    /**
     * Questo metodo mi serve per gestire la redirect esterna verso paypal per il pagamento
     */
    public function paypal_redirect(Request $request)
    {
        $url = $request->url;
        if (!empty($url)) {
            // funzionano entrambe
            //return redirect()->away($url);
            return Inertia::location($url);
        }
        abort(404);
    }

    public function store_step4_paypal_return(Product $product, Cart $cart, String $payment_id)
    {
        
        $p = Payment::find($payment_id);
        
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($p->transaction_ref);

        $payment_id = $p->id;
        Log::info($response);
        
        if(isset($response['purchase_units'][0]['payments']['captures'][0]['status']) && $response['purchase_units'][0]['payments']['captures'][0]['status'] == 'COMPLETED'){
            $payment = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment_id);
            $payment->updatePaymentStatus();
            $payment->updatePaymentPaymentNo();
            // genero e salvo il pdf con proforma fattura dell'acquisto
            
            if($product->product_type_id == 1){
                $paymentToPdf = new PaymentCertificateToPdfService($cart);
                $paymentToPdf->generateAndPersist();
            }
            //BANCARI
            //bancari
        if($product->product_type_id == 2){
            
            $startReal = Carbon::parse('31-12-2024')->format('Y-m-d');

            if($cart->productOffer->months == 12){
                $endReal = Carbon::parse('01-12-2025')->endOfYear()->format('Y-m-d');
            }
            else if(Carbon::parse($startReal)->addMonths($cart->productOffer->months) > Carbon::parse('01-12-2025')->endOfYear()->format('Y-m-d')) {
                $endReal = Carbon::parse('01-12-2025')->endOfYear()->format('Y-m-d');
            }
            else{
                $endReal = Carbon::parse($startReal)->addMonths($cart->productOffer->months);
            }

            if(!isset($endReal)){
                $certificate = Certificate::firstOrCreate(
                    ['cart_id' => $cart->id],
                    [
                        'product_id' => $cart->product_id,
                        'product_offer_id' => $cart->product_offer_id,
                        'contract_id' => $cart->productOffer->contract->id,
                        'start_validity_date' => $startReal,
                        //'retroactivity' => $cart->retroactivity
                    ]
                );
            }
            else{
                $certificate = Certificate::firstOrCreate(
                    ['cart_id' => $cart->id],
                    [
                        'product_id' => $cart->product_id,
                        'product_offer_id' => $cart->product_offer_id,
                        'contract_id' => $cart->productOffer->contract->id,
                        'start_validity_date' => $startReal,
                        'end_validity_date' => $endReal,
                        //'retroactivity' => $cart->retroactivity
                    ]
                );
            }
            $certificate->save();
            $declarationToPdf = new PaymentBankersToPdfService($cart);
            $declarationToPdf->generateAndPersist();

            


            activity()
                ->causedBy(auth()->user())
                ->on($certificate)
                ->event('certificate_generation')
                ->log("Completed certificate $certificate->id generation;");

            $cart->user->notify(new FinalEmailWithAttachments($cart));
        }
        //fine bancari
            $cart->updateCartStep(5);


            
            return response()->json([
                'info_paypal' => 'SUCCESS'
            ], 200);
        }
        else{
            return response()->json([
                'info_paypal' => 'FAILED',
            ], 200);
        }
    }

    /**
     * Transazione PayPal non andata a buon fine
     * - Mostro al cliente l'errore interrompendo il flusso d'acquisto
     * - Invio una notifica via email al cliente e in copia a golinucci
     */
    public function store_step4_paypal_cancel(Product $product, Cart $cart)
    {
        // se l'utente cancella il pagamento con paypal lo riporto alla pagina di pagamento
        return redirect()->route('front.product.buy', compact('product', 'cart'));

        // $payment_id = request('payment_id');
        // $payment = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment_id);
        // // invio email a cliente con notifica che il pagamento non è andato a buon fine
        // // bcc a MAIL_BCC_TO
        // $delay = now()->addMinutes(1);
        // auth()->user()->notify((new PaypalGatewayPaymentCancel($payment))->delay($delay));
        // return Inertia::render('Sites/PaymentPaypalCancel', compact('product', 'cart', 'payment'));
    }

    private function step5(Product $product, Cart $cart)
    {
        
        if($product->product_type_id == 1){
            $redirect = 'Sites/BuyProduct';
        }
        else{
            $redirect = 'Sites/BuyProductBankers';
        }
        return Inertia::render($redirect, [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'documentGenerator' => [],
                'completedDocumentGenerator' => false,
            ],
            'cart' => $cart,
        ]);
    }

    public function store_step5(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);
        $certificate = Certificate::firstOrCreate(
            ['cart_id' => $cart->id],
            [
                'product_id' => $cart->product_id,
                'product_offer_id' => $cart->product_offer_id,
                'contract_id' => $cart->productOffer->contract->id,
                'start_validity_date' => $cart->renewal_date,
                'retroactivity' => $cart->retroactivity
            ]
        );
        if (isset($request->method)) {
            switch ($request->method) {
                case 'initialization':
                    $response = $this->store_step5_certificate_initialization($cart);
                    break;
                case 'handshake':
                    $response = $this->store_step5_certificate_handshake($cart);
                    break;
                case 'validation':
                    $response = $this->store_step5_certificate_validation($cart);
                    break;
                case 'upload':
                    $response = $this->store_step5_certificate_upload($cart);
                    break;
                case 'transaction':
                    $response = $this->store_step5_certificate_transaction($cart);
                    break;
                case 'download':
                    $response = $this->store_step5_certificate_download($cart);
                    break;
                case 'completed':
                    $response = $this->store_step5_certificate_download($cart);
                    break;
            }

            return response()->json($response);
        } else {
            $cart->updateCartStep(6);

            activity()
                ->causedBy($user)
                ->on($certificate)
                ->event('certificate_generation')
                ->log("Completed certificate $certificate->id generation;");

            return redirect()->route('front.product.buy', compact('product', 'cart'));
        }
    }

    private function store_step5_certificate_initialization(Cart $cart)
    {
        try {
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == null) {
                $certificate->doprint_current_step = 'handshake';
                $certificate->save();
            }
            $user = auth()->user();
            if (empty($certificate->doprint_token)) {
                activity()
                    ->causedBy($user)
                    ->on($certificate)
                    ->event('certificate_generation')
                    ->log("Initiate certificate $certificate->id generation;");
            } else {
                activity()
                    ->causedBy($user)
                    ->on($certificate)
                    ->event('certificate_generation')
                    ->log("Re-Initiate certificate $certificate->id generation due to previous error;");
            }

            return [
                'error' => false,
                'message' => 'Inizializzazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on initialization;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Inizializzazione certificato fallita. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
    }

    private function store_step5_certificate_handshake(Cart $cart)
    {
        try {
            $certificate = $cart->certificate;
            // se validation allora rieseguo anche handshake
            if ($certificate->doprint_current_step == 'handshake' && empty($certificate->doprint_token) || $certificate->doprint_current_step == 'validation' && !empty($certificate->doprint_token)) {
                $certificate->doprint_token = LloydsDoprintService::handShake();
                $certificate->doprint_current_step = 'validation';
                $certificate->save();
            }

            return [
                'error' => false,
                'message' => 'Connessione avvenuta correttamente...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on handshake;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Connessione fallita. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
    }

    private function store_step5_certificate_validation(Cart $cart)
    {
        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'validation') {
                $clientDetails = ClientDetail::where('user_id', $cart->user_id)->first();
                
                if($cart->product->id == 1){
                    $profession = $clientDetails->getConditionalFieldMatchById(1);
                    $authority_type = $clientDetails->getConditionalFieldMatchById(2);
                }
                if($cart->product->id == 5){
                    $profession = "GIOVANE MEDICO";
                    $authority_type = "nulla";
                }

                $customer = [
                    'profession' => $profession,
                    'authority_type' => $authority_type,
                    'first_name' => $clientDetails->first_name,
                    'last_name' => $clientDetails->last_name,
                    'address' => $clientDetails->address,
                    'city' => $clientDetails->city,
                    'postal_code' => $clientDetails->postal_code,
                    'province' => $clientDetails->province, //'MI',
                    'fiscal_code' => $clientDetails->fiscal_code,
                    'gender' => $clientDetails->gender,
                    'country' => ($clientDetails->country == 'IT') ? 'Italia' : $clientDetails->country
                ];

                $cart_details = [
                    'cart_id' => $cart->id,
                    'price' => $cart->net_premium,
                    'accessori' => $cart->accessory, // 10% di sovrapprezzo sul netto; 44/10
                    'tax' => $cart->tax, // tasse calcolate su premio + accessori
                    'price_with_tax' => $cart->gross_premium, // premio + accessori + tax (no fee)
                    'indemnity_limit' => $cart->indemnity_limit
                ];

                $datasheetToPdf = new DatasheetToPdfService($cart);
                $scheda = $datasheetToPdf->generateAndPersist($profession); // passo profession perché già generato poco sopra

                $isValid = LloydsDoprintService::validationRequest(
                    $cart,
                    $customer,
                    $cart_details,
                    $scheda
                );
                if ($isValid) {
                    $certificate->doprint_current_step = 'upload';
                    $certificate->save();
                }
            }
            return [
                'error' => false,
                'message' => 'Validazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on validation;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Validazione certificato fallita. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
    }

    private function store_step5_certificate_upload(Cart $cart)
    {

        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'upload') {
                LloydsDoprintService::uploadAttachments($cart);
                $certificate->doprint_current_step = 'transaction';
                $certificate->save();
            }

            return [
                'error' => false,
                'message' => 'Upload file completato...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on upload;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Upload file fallito. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
    }

    private function store_step5_certificate_transaction(Cart $cart)
    {
        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'transaction') {
                $isValid = LloydsDoprintService::transactionRequest($cart);
                if ($isValid) {
                    $certificate->doprint_current_step = 'download';
                    $certificate->save();
                }
            }

            return [
                'error' => false,
                'message' => 'Transazione completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on transaction;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Transazione fallita. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
    }

    private function store_step5_certificate_download(Cart $cart)
    {
        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'download') {
                // // Download the certificate
                $filename = 'certificate.pdf';
                $isValid = LloydsDoprintService::downloadRequest($cart, $filename);
                if ($isValid) {
                    $certificate->doprint_current_step = 'completed';
                    $certificate->save();
                }
            }
            return [
                'error' => false,
                'message' => 'Preparazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on Download;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Preparazione certificato fallita. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
    }

    private function step6(Product $product, Cart $cart)
    {
        try {
            $yousignClient = new YousignApiService($cart);
            $signature = Signature::where('certificate_id', $cart->certificate->id)->first();

            if (empty($signature)) {
                $yousignMemberSignerId = $yousignClient->startYousignClientAndLaunchProcedure();
            } else {
                $yousignMemberSignerId = $signature->member_signer_id;
            }
        } catch (\Exception $e) {
            $product_signature_error = 'Si è verificato un errore con la procedura di firma.';
        }

        if($product->id == 1){
            $redirect = 'Sites/BuyProduct';
        }
        else{
            $redirect = 'Sites/BuyProductBankers';
        }
        return Inertia::render($redirect, [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'yousign_url' => config('yousign.app_url'),
                'yousign_member_signer_id' => $yousignMemberSignerId ?? null,
                'signature_error' => $product_signature_error ?? null
            ],
            'cart' => $cart
        ]);
    }

    private function step7(Product $product, Cart $cart)
    {
        if($product->id == 1){
            $redirect = 'Sites/BuyProduct';
        }
        else{
            $redirect = 'Sites/BuyProductBankers';
        }
        return Inertia::render($redirect, [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart
        ]);
    }

    private function verifyAndGetCartOrRedirect(User $user, Product $product, Cart $cart)
    {
        if (!empty($cart)) {
            // recupero il carrello verificando se è ancora attivo
            if (empty($cart->verifyAndGrabCart($user))) {
                abort(401);
            }
        }

        return $cart;
    }
}
