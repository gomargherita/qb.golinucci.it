<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\UpdateDecorrenzaRequest;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\ClientDetail;
use App\Models\Product;
use App\Services\LloydsDoprintService;
use Illuminate\Http\Request as RequestHttp;
use App\Services\DatasheetToPdfService;
use App\Services\Yousign\YousignApiService;
use App\Models\Signature;
use App\Notifications\ConfirmedOfflinePayment;
use Illuminate\Support\Facades\Log;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;








class CartController extends Controller
{

    public function isRenewable(Cart $cart){
        if($cart->product_step_id < 6){
            return false;
        }

        $product = Product::find($cart->product_id);
        $user_id = $cart->user_id;

        $check = Cart::where('cart_ref', $cart->cart_ref)->first();
        if(empty($check->cart_ref) || $check->user_id != $user_id){
            return false;
        }

        $cart_ref = $check->cart_ref;

        $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->latest()->first();

        if($last_prev_certificate != null){
            $old_cart_end_date = $last_prev_certificate->end_validity_date;
        }
        else{
            return false;
        }

        $end = date_create($old_cart_end_date);
        $today = date_create(date('d-m-Y'));

        $interval = date_diff($end,$today);
        $interval = $interval->format("%R%a");

        if($interval < -30){
            return false;
        }

        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product_type = 1)
    {
        if($product_type == 1){
            $request = Request::only('search', 'steps', 'trashed');

            if(isset($request['search'])){
                $searchQuery = $request['search'];
            }
            else{
                $searchQuery = " ";
            }
            $searchTerms = explode(' ', $searchQuery);

            $carts = Auth::user()->account->carts()
                ->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where(function ($subQuery) use ($term) {
                            $subQuery->where('users.first_name', 'LIKE', "%{$term}%")
                                    ->orWhere('users.last_name', 'LIKE', "%{$term}%")
                                    ->orWhere('users.email', 'LIKE', "%{$term}%");
                        });
                    }
                })
                    ->whereIn('product_id', [1, 5])
                    ->orderByDate()
                    ->filter($request)
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
                        'product_type' => $product_type,
                        'isRenewable' => "",
                    ]);

            $isRenewable = [];

            for($i=0; $i<count($carts); $i++){
                $isRenewable[$carts[$i]['cart_ref']] = $this->isRenewable(Cart::find($carts[$i]['id']));
            }

            return Inertia::render('Carts/Index', [
                'product_type' => $product_type,
                'filters' => Request::all('search', 'steps', 'trashed'),
                'all_product_steps' => ProductStep::where('product_id', 1)->get(), // TODO questa cosa va rivalutata quando verranno creati nuovi prodotti
                'carts' => $carts,
                'isRenewable' => $isRenewable,
            ]);
        }
        if($product_type == 2){
            $request = Request::only('search', 'steps', 'trashed');

            if(isset($request['search'])){
                $searchQuery = $request['search'];
            }
            else{
                $searchQuery = " ";
            }
            $searchTerms = explode(' ', $searchQuery);

            $carts = Auth::user()->account->carts()
            ->leftJoin('payments', 'payments.cart_id', '=', 'carts.id')
                ->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where(function ($subQuery) use ($term) {
                            $subQuery->where('users.first_name', 'LIKE', "%{$term}%")
                                    ->orWhere('users.last_name', 'LIKE', "%{$term}%")
                                    ->orWhere('users.email', 'LIKE', "%{$term}%")
                                    ->orWhere('payments.payment_no', 'LIKE', "%{$term}%");

                        });
                    }
                })/*->toSql();
                dd($carts);
                die;*/
                ->whereIn('product_id', [2, 3, 4])
                ->orderByDate()
                //->filter($request)
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
                    'product_type' => $product_type,
                    'isRenewable' => "",
                ]);
                $isRenewable = [];

            for($i=0; $i<count($carts); $i++){
                $isRenewable[$carts[$i]['cart_ref']] = $this->isRenewable(Cart::find($carts[$i]['id']));
            }

            return Inertia::render('Carts/Index', [
                'product_type' => $product_type,
                'filters' => Request::all('search', 'steps', 'trashed'),
                'all_product_steps' => ProductStep::where('product_id', 2)->get(), // TODO questa cosa va rivalutata quando verranno creati nuovi prodotti
                'carts' => $carts,
                'isRenewable' => $isRenewable,
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

    static public function renew(Cart $cart)
    {
        if($cart->product_step_id < 6){
            abort(510);
        }

        $product = Product::find($cart->product_id);
        $user_id = $cart->user_id;

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
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->latest()->first();

        $old_cart_end_date = $last_prev_certificate->end_validity_date;

        $end = date_create($old_cart_end_date);
        $today = date_create(date('d-m-Y'));

        $interval = date_diff($end,$today);
        $interval = $interval->format("%R%a");
        if($interval < -30){
            abort(510);
        }

        $new_cart_ref = DB::select(DB::raw('SELECT new_cart_ref FROM renewals WHERE old_cart_ref = "'.$cart_ref.'";'));
        if(!empty($new_cart_ref)){
            return redirect()->route('carts.continue', ['product' => $product, 'cart' => $new_cart_ref[0]->new_cart_ref]);
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

        return redirect()->route('carts.continue', ['product' => $product, 'cart' => $newCart]);
    }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($product_type, $user_id = null, $product_id = null, $step = 1, $cart = null)
    {
        if($cart != null){
            /** Preparazione scadenze **/
            if($this->isRenewal($cart)){
                    $expiration_date = date_create($cart->expired_at);
                    $now = date_create(date('d-m-Y'));
                    $interval = date_diff($expiration_date,$now);
                    $interval = $interval->format("%R%a");
                    //if($interval > -1){
                    //    abort(419);
                    //}
                }
                else{
                    // get renewal date if exists in previous cart
                    $last_prev_certificate = Certificate::query()
                        ->with('cart')
                        ->select(['end_validity_date'])->where([
                            // controllo su product
                            'product_id' => $cart->product_id,
                            ])->whereHas('cart', function ($q) use ($user_id) {
                                $q->where('user_id', $user_id);
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

                        //if(!($intervallo > -60) && ($product_id == 1 || $product_id == 5)){
                        //    abort(308);
                        //}
                    }
                }
            }

        /** Selezione utente **/
        if(!isset($user_id) && !isset($product_id)){
            $request = Request::only('search', 'steps', 'trashed');

            if(isset($request['search'])){
                $searchQuery = $request['search'];
            }
            else{
                $searchQuery = " ";
            }
            $searchTerms = explode(' ', $searchQuery);

            //dd($product_type);
        return Inertia::render('Carts/Create', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'product_type' => $product_type,
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->where(function ($query) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $query->where(function ($subQuery) use ($term) {
                            $subQuery->where('users.first_name', 'LIKE', "%{$term}%")
                                    ->orWhere('users.last_name', 'LIKE', "%{$term}%")
                                    ->orWhere('users.email', 'LIKE', "%{$term}%");

                        });
                    }
                })
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
                    if($cart == null){
                        $cart = Cart::create([
                            'user_id' => $user_id,
                            'product_id' => $product_id,
                            'product_step_id' => 1,
                            'expired_at' => now()->addMonths(1),
                            'locked' => 'N',
                            'cart_ref' => uniqid()
                        ]);
                    }
                    
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
                case 6:
                    return $this->step6($product, $cart, $user_id);
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

            if($validated['confirmation_sinistri'] == "si" || $validated['confirmation_gmedici1'] == 'si' || $validated['confirmation_gmedici2'] == 'si' || $validated['confirmation_gmedici3'] == 'si' || $validated['confirmation_gmedici4'] == 'si' || $validated['confirmation_gmedici5'] == 'si'){
                if($cart->product->id == 5 || $cart->product->id == 1){
                    abort(480);
                }
            }

            $cart->giorno_completamento_step_1 = now();
            $cart->save();
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
            $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->latest()->first();

            if($last_prev_certificate != null){
                $old_cart_end_date = $last_prev_certificate->end_validity_date;
            }
            else{
                $old_cart_end_date = "08-11-2024";
            }

            $end = date_create($old_cart_end_date);
            //dd($last_prev_certificate);
            $deadline = date_create("08-11-2024");

            $interval = date_diff($end,$deadline);
            $interval = $interval->format("%R%a");


            //interval > 0 significa che hai l'offerta vecchia
            /*if($interval > 0){
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
        }*/
        //else{
            $flag_prev_2022 = 0;
            $full_cert = Certificate::query()
                    ->with('cart')
                    ->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->latest()->first();


                //facendo in modo che l'utente non possa scegliere una retro diversa
            if($product->id == 5 && $full_cert != null && $full_cert->cart->indemnity_limit_patrimonial == 2){
                $product = $product->load([
                'productOffers' => function ($query) use ($flag_prev_2022) {
                    $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022)->whereNull('old')->where('indemnity_limit_patrimonial', 2);
                }
                ]);
            }
            else if($product->id == 5 && $full_cert != null && $full_cert->cart->indemnity_limit_patrimonial == 10){
                $product = $product->load([
                'productOffers' => function ($query) use ($flag_prev_2022) {
                    $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022)->whereNull('old')->where('indemnity_limit_patrimonial', 10);
                }
                ]);
            }
            else{
                $product = $product->load([
                    'productOffers' => function ($query) use ($flag_prev_2022) {
                        $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022)->whereNull('old');
                    }
                ]);
            }

        //}

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
        if($cart->product_id == 1 || $cart->product_id == 5){
            return $this->generateLloyds($cart);
        }
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
            if(Carbon::now()->lt("31-12-2025")){
                $startReal = Carbon::parse('31-12-2025')->format('Y-m-d');
            }
            else{
                $startReal = $cart->payment->paid_at->format('Y-m-d');
            }
            if($cart->productOffer->months == 12){
                $endReal = Carbon::parse('01-12-2026')->endOfYear()->format('Y-m-d');
            }
            else if(Carbon::parse($startReal)->addMonths($cart->productOffer->months) > Carbon::parse('01-12-2026')->endOfYear()->format('Y-m-d')) {
                $endReal = Carbon::parse('01-12-2026')->endOfYear()->format('Y-m-d');
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

    private function step6(Product $product, Cart $cart)
    {
        try {
            $iterazioni = 0;
            $yousignClient = new YousignApiService($cart, User::find($cart->user_id));
            $signature = Signature::where('certificate_id', $cart->certificate->id)->first();

        if (empty($signature)) {
            $yousignMemberSignerId = $yousignClient->startYousignClientAndLaunchProcedure();
        } else if ($signature->status == 'init'){
            while($signature->status == 'init' && $iterazioni < 3){
                $signature->delete();
                $yousignMemberSignerId = $yousignClient->startYousignClientAndLaunchProcedure();
                $signature = Signature::where('certificate_id', $cart->certificate->id)->first();
                $iterazioni = $iterazioni + 1;
            }
        }
        $payment = $cart->payment;
        $payment->cart->user->notify(new ConfirmedOfflinePayment($payment));
        $yousignMemberSignerId = $signature->member_signer_id;

        } catch (\Exception $e) {
            //error_log("wualche errore".$e);
             $product_signature_error = 'Si è verificato un errore con la procedura di firma.';
        }

            $redirect = 'Carts/Steps/Step6';

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
            //Notification::mailer('pec')->sendNow($cart->user, new FinalEmailWithAttachments($cart));

            //Mail::mailer('pec')->to($cart->user->email)->send(new FinalEmailWithAttachments($cart));
            return Redirect::back()->with('success', 'Email inviata.');
        }
        catch(Throwable $e){
            return Redirect::back()->with('error', 'Email NON inviata.');
        }
    }

    public function sendFirstEmail(Cart $cart, $product_id){
        $user_id = $cart->user_id;
        $last_prev_certificate = Certificate::query()
                    ->with('cart')
                    ->select(['end_validity_date'])->where([
                        // controllo su product
                        'product_id' => $cart->product_id,
                    ])->whereHas('cart', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    })
                    ->latest()->first();
        $renewal = (explode(" ", ((string)$last_prev_certificate['end_validity_date']))[0]);
        //if($cart->renewal_date == null || $cart->product_id == null){
            $cart->renewal_date = $renewal;
            $cart->product_id = intval($product_id);
            $cart->save();

            //dd($cart);
            //return $this->sendFirstEmail($cart, $product_id);
        //}

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
            
            'start_date' => $cart->product->product_type_id == 1 ? $cart->renewal_date?->format('Y-m-d') : "",
            'start_date' => $cart->product->product_type_id == 2 ? $cart->certificate?->start_validity_date?->format('Y-m-d') : "",

            
            'retroactivity' => $cart->retroactivity?->format('Y-m-d'),

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
        //dd($request);return;
        if($cart->product->product_type_id == 1 ){
            $certificate = Certificate::where('cart_id', $cart->id)->first();
            //if($certificate != null){
            //    return Redirect::back()->with('error', 'Il certificato Lloyd\'s è già stato emesso. Eliminare il certificato per poter cambiare decorrenza.');
            //}

            if($request->startDate != null){
                $cart->renewal_date = $request->startDate;
            }

            if($request->retroactivity != null){
                $cart->retroactivity = $request->retroactivity;
            }
            $cart->save();
            //dd($cart);
            return Redirect::back()->with('success', 'Date di decorrenza aggiornate.')->with('reload', true);
        }

        if($cart->product->product_type_id == 2 ){
            $certificate = Certificate::find($cart->certificate->id);
            $certificate->start_validity_date = $request->startDate;
            $certificate->end_validity_date = $request->endDate;
            //dd($cart);
            $certificate->save();
            $declarationToPdf = new PaymentBankersToPdfService($cart);
            $declarationToPdf->generateAndPersist();
            return Redirect::back()->with('success', 'Date di decorrenza aggiornate.')->with('reload', true);
        }
    }

    public function updateToStep6(Cart $cart){
        $cart->updateCartStep(6);
        $this->step6($cart->product, $cart);
        $product = $cart->product;
        return redirect()->route("carts.continue", compact('product', 'cart'));
    }

    public function generateLloyds(Cart $cart){
        if($cart->product_step_id != 5){
            return Redirect::back()->with('error', 'Il carrello non è allo step 5. Lloyds');
        }
        return $this->step5Lloyds($cart->product, $cart, $cart->user_id);
        
    }

    public function step5Lloyds(Product $product, Cart $cart, $user_id){
       
        $redirect = 'Carts/Steps/Step5Lloyds';
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

    public function storeLloyds(Product $product, Cart $cart, RequestHttp $request)
    {
        //return 500;
        $user = User::find($cart->user_id);
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

            return Redirect::back();
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
            $user = User::find($cart->user_id);
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
                ->causedBy(User::find($cart->user_id))
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
                ->causedBy(User::find($cart->user_id))
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
            $user = User::find($cart->user_id);
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
                ->causedBy(User::find($cart->user_id))
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
            $user = User::find($cart->user_id);
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
                ->causedBy(User::find($cart->user_id))
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
            $user = User::find($cart->user_id);
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
                ->causedBy(User::find($cart->user_id))
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
            $user = User::find($cart->user_id);
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
                ->causedBy(User::find($cart->user_id))
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on Download;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Preparazione certificato fallita. Si prega di ricaricare la pagina e riprovare oppure contattarci al numero 0547 22351 o via email.'
            ];
        }
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

    public function generateProposal(Cart $cart){
        $cart->giorno_completamento_step_1 = now();
        $cart->save();

        $new_proposal = new NewApplicationToPdfService($cart);
        $new_proposal->generateAndPersist();
        return Redirect::back()->with('success', 'Modulo creato.');
    }


    /**
     *   V Ricordare all’admin che deve prima eliminare la vecchia polizza da Doprint
     *   - Elimina il certificato vecchio (on CASCADE, ricorda che attualmente è RESTRICT)
     *   V Ricorda di eliminare anche i file sul file system, collegati al certificato!!! (Tipo certificate_final.pdf e gli altri)
     *   Delegato dal redirect - Crea un certificato nuovo vuoto
     *   Delegato dal redirect - Collega il carrello con il nuovo certificato
     *   TODO - Procedi alla rigenerazione del certificato completo con i nuovi dati dal carrello (gestire bene quando non vengono forniti dati (esempio: ricordare che il sistema in automatico prende il rinnovo dal carrello vecchio se non sono fornite informazioni)) Farei in modo che sia proibito rigenerare un certificato senza avergli dato delle date esplicite
     *   TODO - Genera anche la firma di Yousign
     *   TODO - Avvisa l’admin che è tutto pronto
     *
     * @param Cart $cart Il carrello di cui rigenerare il certificato.
     * @return RedirectResponse fa il redirect per generare il nuovo certificato
     */
    public function regen_certificate(Cart $cart){
        $user_id = $cart->user_id;
        $old_certificate = Certificate::where('cart_id', $cart->id)->first();
        if ($old_certificate != null){
            $old_signature = Signature::where('certificate_id', $old_certificate->id)->first();
        }
        else{
            $old_signature = null;
        }

        //eliminazione dal file system
        $certificate_folder = 'certificates/user_' . $user_id . '/cart_' . $cart->id . "/";

        Storage::disk('private')->delete($certificate_folder . "certificate.pdf");
        Storage::disk('private')->delete($certificate_folder . "new-application.pdf");
        Storage::disk('private')->delete($certificate_folder . "policy_response.xml");
        Storage::disk('private')->delete($certificate_folder . "scheda.pdf");
        Storage::disk('private')->delete($certificate_folder . "certificate-yousign.pdf");
        Storage::disk('private')->delete($certificate_folder . "certificate-final.pdf");
        
        if($cart->product->product_type_id == 1){
            $new_proposal = new NewApplicationToPdfService($cart);
            $new_proposal->generateAndPersist();
        }
        if ($old_signature != null){
            $old_signature->delete();
        }
        if ($old_certificate != null){
            $old_certificate->delete();
        }

        $cart->product_step_id = 5;
        $cart->save();
        return redirect()->route('carts.generatelloyds', ['cart' => $cart]);
    }
}
