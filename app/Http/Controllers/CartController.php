<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\UpdateDecorrenzaRequest;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\ClientDetail;
use App\Models\Product;

use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\ProductStep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateBackClientDetailsRequest;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOffer;
use App\Services\NewApplicationToPdfService;
use Illuminate\Http\Request as HttpRequest;
use App\Rules\ProjectDeclarationsRule;
use App\Services\DeclarationsToPdfService;
use App\Services\PaymentBankersToPdfService;
use App\Notifications\FinalEmailWithAttachments;
use App\Models\Certificate;
use App\Notifications\FirstEmailWithCredentials;
use Throwable;
use Carbon\Carbon;






class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product_type = 1)
    {
        if($product_type == 1){
            return Inertia::render('Carts/Index', [
                'product_type' => $product_type,
                'filters' => Request::all('search', 'steps', 'trashed'),
                'all_product_steps' => ProductStep::where('product_id', 1)->get(), // TODO questa cosa va rivalutata quando verranno creati nuovi prodotti
                'carts' => Auth::user()->account->carts()
                    ->where('product_id', 1)
                    ->orderByDate()
                    ->filter(Request::only('search', 'steps', 'trashed'))
                    ->paginate(20)
                    ->withQueryString()
                    ->through(fn ($cart) => [
                        'id' => $cart->id,
                        'user' => $cart->user->only('id', 'email', 'first_name', 'last_name'),
                        'product_name' => $cart->product->name,
                        'step' => $cart->productStep ? $cart->productStep->step_number . '. ' . $cart->productStep->step_name : null,
                        'product_offer' => $cart->productOffer ? $cart->productOffer->id . '. ' . $cart->productOffer->name : null,
                        'cart_ref' => $cart->cart_ref,
                        'created_at' => $cart->created_at->format('d/m/Y'),
                        'deleted_at' => $cart->deleted_at,
                        'product_type' => $product_type
                    ]),
            ]);
        }
        if($product_type == 2){
            return Inertia::render('Carts/Index', [
                'product_type' => $product_type,
                'filters' => Request::all('search', 'steps', 'trashed'),
                'all_product_steps' => ProductStep::where('product_id', 2)->get(), // TODO questa cosa va rivalutata quando verranno creati nuovi prodotti
                'carts' => Auth::user()->account->carts()
                    ->orderByDate()
                    ->whereIn('product_id', [2,3,4])
                    ->filter(Request::only('search', 'steps', 'trashed'))
                    ->paginate(20)
                    ->withQueryString()
                    ->through(fn ($cart) => [
                        'id' => $cart->id,
                        'user' => $cart->user->only('id', 'email', 'first_name', 'last_name'),
                        'product_name' => $cart->product->name,
                        'step' => $cart->productStep ? (($cart->productStep->step_number == 5) ? $cart->productStep->step_number . '. ' . 'Completato' : $cart->productStep->step_number . '. ' . $cart->productStep->step_name) : null,
                        'product_offer' => $cart->productOffer ? $cart->productOffer->id . '. ' . $cart->productOffer->name : null,
                        'cart_ref' => $cart->cart_ref,
                        'created_at' => $cart->created_at->format('d/m/Y'),
                        'deleted_at' => $cart->deleted_at,
                        'product_type' => $product_type
                    ]),
            ]);
        }
    }


    public function continue(Product $product, Cart $cart = null){
        $product_type = $product->product_type_id;
        $user_id = $cart->user_id;
        $product_id = $product->id;
        $step = $cart->product_step_id;

        return $this->create($product_type, $user_id, $product_id, $step, $cart);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($product_type, $user_id = null, $product_id = null, $step = 1, $cart = null)
    {
        /** Selezione utente **/
        if(!isset($user_id) && !isset($product_id)){
            //dd($product_type);
        return Inertia::render('Carts/Create', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'product_type' => $product_type,
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account' => $user->account->name,
                    'owner' => $user->owner,
                    //'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
        }

        /** Selezione prodotto **/
        if(isset($user_id) && !isset($product_id)){
            return Inertia::render('Carts/Steps/Step0', [
                'filters' => Request::all('search', 'trashed'),
                'user_id' => $user_id,
                'product_type' => $product_type,
                'products' => Product::query()
                    ->orderByName()
                    ->where("product_type_id", $product_type)
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($product) => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'type' =>  $product->productType->name,
                        'deleted_at' => $product->deleted_at,
                    ]),
            ]);
        }

        /** Step del carrello **/
        if(isset($user_id) && isset($product_id)){
            $product = Product::find($product_id);

            switch($step){
                case 1:
                    $cart = Cart::create([
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'product_step_id' => 1,
                        'expired_at' => now()->addMonths(1),
                        'locked' => 'N',
                        'cart_ref' => uniqid()
                    ]);
                    
                    return $this->step1($product, $cart, $user_id);
                    break;
                case 2:
                    return $this->step2($product, $cart, $user_id);
                    break;
                case 3:
                    return $this->step3($product, $cart, $user_id);
                    break;
                case 4:
                    return $this->step4($product, $cart, $user_id);
                    break;
                case 5:
                    return $this->step5($product, $cart, $user_id);
                    break;
            }
        }

    }

    public function step1(Product $product, Cart $cart, $user_id){
        $user = User::find($user_id);
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


        return Inertia::render('Carts/Steps/Step1', [
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
            'clientDetails' => $clientDetails,
            'user_id' => $user_id,
        ]);
    }

    public function store_step1(Product $product, Cart $cart, UpdateBackClientDetailsRequest $request)
    {
        $user = User::find($request->user_id);
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated, $cart) {
            $clientDetails = ClientDetail::updateOrCreate(['user_id'   => $user->id], $validated);

            if($clientDetails->dob == null){
                $clientDetails->dob = "1970-01-01";
            }
            if($clientDetails->gender == null){
                $clientDetails->gender = '';
            }
            $clientDetails->save();

            $rebuildProductClientDetails = [];
            foreach ($validated['product_client_details'] as $el) {
                $conditional_field_id = $el['pivot']['conditional_field_id'];
                $selected_field_value = $el['pivot']['selected_field_value'];
                $rebuildProductClientDetails[$conditional_field_id] = ['selected_field_value' => $selected_field_value];
            }


            $clientDetails->productClientDetails()->syncWithoutDetaching($rebuildProductClientDetails);
            $cart->updateCartStep(2);
        });

        return redirect()->route('carts.continue', compact('product', 'cart'));
    }

    private function step2(Product $product, Cart $cart, $user_id)
    {
        $user = User::find($user_id);
        $clientDetails = ClientDetail::query($product)
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        
        $flag_prev_2022 = $clientDetails->flag_prev_2022;

        if($product->product_type_id == 1){
            $product = $product->load([
                'productOffers' => function ($query) use ($flag_prev_2022) {
                    $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022);
                }
            ]);
        }
        else{
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

        $offers = $product->productOffers->map(function ($rec) use ($clean) {
            $rec2 = $rec->conditionalFields;
            $flag = true;
            foreach ($rec2 as $bhgh) {
                $conditional_field_id = $bhgh->pivot->conditional_field_id;
                $selected_field_value = $bhgh->pivot->selected_field_value;
                if ($selected_field_value != $clean[$conditional_field_id]) {
                    $flag = false;
                }
            }
            if ($flag) {
                return $rec;
            }
        })->filter(function ($value) {
            return $value != null;
        });

        return Inertia::render('Carts/Steps/Step2', [
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

    public function store_step2(Product $product, Cart $cart, HttpRequest $request)
    {
        $user = User::find($cart->user_id);
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'product_offer_id_selected' => ['required', 'integer'],
        ]);
        if($request->dateStart != null){
            $cart->renewal_date = $request->dateStart;
        }
        if($request->dateEnd != null){
            $cart->custom_end_date = $request->dateEnd;
        }

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

        $cart->product_offer_id = $request->product_offer_id_selected;
        $cart->save();

        // genero e salvo il pdf new proposal se new client
        if($product->id == 1){
            $new_proposal = new NewApplicationToPdfService($cart);
            $new_proposal->generateAndPersist();
        }

        $cart->updateCartStep(3);

        return redirect()->route('carts.continue', compact('product', 'cart'));
    }

    private function step3(Product $product, Cart $cart)
    {
        $user = User::find($cart->user_id);
        return Inertia::render('Carts/Steps/Step3', [
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
            'cart' => $cart,
            'user' => $user,
        ]);
    }

    public function store_step3(Product $product, Cart $cart, HttpRequest $request)
    {
        $user = User::find($cart->user_id);
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

        return redirect()->route('carts.continue', compact('product', 'cart'));
    }

    private function step4(Product $product, Cart $cart)
    {

        $payment = Payment::firstOrCreate(
            [
                'cart_id' => $cart->id
            ],
            [
                'payment_gateway_id' => 1,
                'total_payable' => $cart->total_payable,
            ]
        );

        $payment_gateways = PaymentGateway::all();
        $payment = Payment::where('cart_id', $cart->id)->first();
            return Inertia::render('Carts/Steps/Step4', [
                // 'can' => [
                //     'delete_cart' => $currentUser->can('carts.delete')
                // ],
                'cart' => [
                    'id' => $cart->id,
                    'product' => $cart->product,
                    'product_offer' => $cart->productOffer ? $cart->productOffer : null,
                    'product_step_id' => $cart->product_step_id,
                    'product_steps' => $cart->product->productSteps,
                    'payment' => ($payment) ?
                        [
                            'id' => $payment->id,
                            'paid' => $payment->paid,
                            'paid_at' => $payment->paid_at?->format('Y-m-d'),
                            'payment_gateway_id' => $payment->payment_gateway_id,
                            'payment_no' => $payment->payment_no,
                            'total_payable' => $payment->total_payable,
                            'transaction_ref' => $payment->transaction_ref
                        ]
                        : null,
                    'certificate' => $cart->certificate,
                    'cart_ref' => $cart->cart_ref,
                    'deleted_at' => $cart->deleted_at,
                ],
                'payment_gateways' => $payment_gateways,
                'product_counters' => $cart->product->productCounters,
                'user' => $cart->user ? $cart->user : null,
            ]);;
    }

    public function store_step4(Product $product, Cart $cart){
        $cart->updateCartStep(5);

        return redirect()->route('carts.continue', compact('product', 'cart'));
    }

    public function step5(Product $product, Cart $cart, $user_id)
    {
        if($cart->custom_end_date != null){
            $certificate = Certificate::firstOrCreate(
                ['cart_id' => $cart->id],
                [
                    'product_id' => $cart->product_id,
                    'product_offer_id' => $cart->product_offer_id,
                    'contract_id' => $cart->productOffer->contract->id,
                    'end_validity_date' => $cart->custom_end_date,
                    'start_validity_date' => $cart->renewal_date,
                    //'retroactivity' => $cart->retroactivity
                ]
            );
        }
        else{
            if(Carbon::now()->format('d-m-y') < "31-12-2023"){
                $startReal = Carbon::parse('31-12-2023')->format('Y-m-d');
            }
            else{
                $startReal = $cart->renewal_date;
            }
            if($cart->productOffer->months == 12){
                $endReal = Carbon::parse('01-12-2024')->endOfYear()->format('Y-m-d');
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
        }
        $certificate->save();
        $declarationToPdf = new PaymentBankersToPdfService($cart);
        $declarationToPdf->generateAndPersist();

        


        activity()
            ->causedBy(auth()->user())
            ->on($certificate)
            ->event('certificate_generation')
            ->log("Completed certificate $certificate->id generation;");
            
        //INVIO EMAIL $cart->user->notify(new FinalEmailWithAttachments($cart));
        //return Inertia::render('Carts/Steps/Step5', ['cart' => $cart]);
        return redirect()->route("carts.edit", $cart);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartRequest $request)
    {
        //
    }

    public function sendEmail(Cart $cart){
        try{
            $cart->user->notify(new FinalEmailWithAttachments($cart));
            return Redirect::back()->with('success', 'Email inviata.');
        }
        catch(Throwable $e){
            return Redirect::back()->with('error', 'Email NON inviata.');
        }
    }

    public function sendFirstEmail(Cart $cart){
        if($cart->renewal_date == null || $cart->product_offer_id == null){
            return Redirect::back()->with('error', 'Questo carrello non è un rinnovo. Nessuna mail inviata.');
        }

        try{
            $cart->user->notify(new FirstEmailWithCredentials($cart));
            return Redirect::back()->with('success', 'Email per RINNOVO inviata.');
        }
        catch(Throwable $e){
            return Redirect::back()->with('error', 'Email NON inviata.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        $payment_gateways = PaymentGateway::all();
        $certificate_folder = 'certificates/user_' . $cart->user_id . '/cart_' . $cart->id;
        $payment = Payment::where('cart_id', $cart->id)->first();

        return Inertia::render('Carts/Edit', [
            // 'can' => [
            //     'delete_cart' => $currentUser->can('carts.delete')
            // ],
            'start_date' => $cart->certificate?->start_validity_date?->format('Y-m-d'),
            'end_date' => $cart->certificate?->end_validity_date?->format('Y-m-d'),
            
            'cart' => [
                'id' => $cart->id,
                'product' => $cart->product,
                //'product_type' => $cart->product->product_type,
                'product_offer' => $cart->productOffer ? $cart->productOffer : null,
                'product_step_id' => $cart->product_step_id,
                'product_steps' => $cart->product->productSteps,
                
                'payment' => ($payment) ?
                    [
                        'id' => $payment->id,
                        'paid' => $payment->paid,
                        'paid_at' => $payment->paid_at?->format('Y-m-d'),
                        'payment_gateway_id' => $payment->payment_gateway_id,
                        'payment_no' => $payment->payment_no,
                        'total_payable' => $payment->total_payable,
                        'transaction_ref' => $payment->transaction_ref
                    ]
                    : null,
                'certificate' => $cart->certificate,
                'cart_ref' => $cart->cart_ref,
                'deleted_at' => $cart->deleted_at,
                'documents' => [
                    'certificate_final' => Storage::disk('private')->exists($certificate_folder . '/' . 'certificate-final.pdf') ? route('privateFiles', $certificate_folder . '/' . 'certificate-final.pdf') : null,
                    'declarations' => Storage::disk('private')->exists($certificate_folder . '/' . 'declarations.pdf') ? route('privateFiles', $certificate_folder . '/' . 'declarations.pdf') : null,
                    'payment_certificate' => Storage::disk('private')->exists($certificate_folder . '/' . 'payment-certificate.pdf') ? route('privateFiles', $certificate_folder . '/' . 'payment-certificate.pdf') : null,
                ]
            ],
            'payment_gateways' => $payment_gateways,
            'product_counters' => $cart->product->productCounters,
            'user' => $cart->user ? $cart->user : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCartRequest  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $cart->update($request->validated());

        return Redirect::back()->with('success', 'Cart updated.');
    }

    public function updateDecorrenza(UpdateDecorrenzaRequest $request, Cart $cart)
    {
        //$cart->update($request->validated());
        //echo "cio";
        //dd($request);return;
        $certificate = Certificate::find($cart->certificate->id);
        $certificate->start_validity_date = $request->startDate;
        $certificate->end_validity_date = $request->endDate;
        //dd($cart);
        $certificate->save();
        $declarationToPdf = new PaymentBankersToPdfService($cart);
        $declarationToPdf->generateAndPersist();
        return Redirect::back()->with('success', 'Date di decorrenza aggiornate.')->with('reload', true);
    }

    public function certificateUpdate(Cart $cart)
    {
        $declarationToPdf = new PaymentBankersToPdfService($cart);
        $declarationToPdf->generateAndPersist();
        return Redirect::back()->with('success', 'Certificato aggiornato nel sistema.')->with('reload', true);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        return Redirect::back()->with('success', 'Cart deleted.');
    }

    public function restore(Cart $cart)
    {
        $cart->restore();

        return Redirect::back()->with('success', 'Cart restored.');
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
