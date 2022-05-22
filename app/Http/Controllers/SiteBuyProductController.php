<?php

namespace App\Http\Controllers;

use App\Imports\MedmalRenewalsImport;
use App\Services\LloydsDoprintService;
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
use App\Services\Yousign\YousignApiService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;

class SiteBuyProductController extends Controller
{

    public function buy(Product $product, Cart $cart = null)
    {
        $user = auth()->user();

        if (!empty($cart)) {
            $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

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
        } else {
            // se cart assente o locked, genero un cart ref e creo il carrello
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_step_id' => 1,
                'expired_at' => now(), // TODO Francesco come calcolare? Riposta: Scadenza renewal_date + 1 anno
                'locked' => 'N',
                'cart_ref' => uniqid()
            ]);

            return redirect()->route('front.product.buy', ['product' => $product, 'cart' => $cart]);
        }
    }

    private function step1(Product $product, Cart $cart)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::query()
            ->with('productClientDetails:field_name')
            ->where([
                'user_id' => $user->id
            ])
            ->latest('id')
            ->first();

        $product = $product->load([
            'productOffers'
        ]);

        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart,
            'clientDetails' => $clientDetails
        ]);
    }

    public function store_step1(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $num_additional_fields = $product->conditionalFields->count();

        $validated = $request->validate([
            'user_id' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'fiscal_code' => ['required'],
            'gender' => ['required'],
            'dob' => ['date'],
            'address' => ['required'],
            'city' => ['required'],
            'province' => ['required'],
            'country' => ['required'],
            'postal_code' => ['required'],
            'phone' => ['required', 'max:30', 'phone:IT,mobile'],
            'product_client_details' => ['required', 'array', 'min:' . $num_additional_fields, 'max:' . $num_additional_fields],
            'product_client_details.*.pivot.selected_field_value' => ['required'],
            'confirmation' => ['accepted']
        ]);

        DB::transaction(function() use ($user, $validated, $cart){
            $clientDetails = ClientDetail::updateOrCreate(['user_id'   => $user->id], $validated);

            $rebuildProductClientDetails = [];
            foreach($validated['product_client_details'] as $el){
                $conditional_field_id = $el['pivot']['conditional_field_id'];
                $selected_field_value = $el['pivot']['selected_field_value'];
                $rebuildProductClientDetails[$conditional_field_id] = ['selected_field_value' => $selected_field_value];
            }
    
            $clientDetails->productClientDetails()->sync($rebuildProductClientDetails);
    
            $cart->updateCartStep(2);
        });

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step2(Product $product, Cart $cart)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::select(['flag_prev_2022'])
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        $flag_prev_2022 = $clientDetails->flag_prev_2022;

        $product = $product->load([
            'productOffers' => function ($query) use ($flag_prev_2022) {
                $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022);
            }
        ]);

        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
                'steps' => $product->productSteps->count(),
            ],
            'cart' => $cart
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

        $cart->product_offer_id = $request->product_offer_id_selected;
        $cart->save();

        $cart->updateCartStep(3);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step3(Product $product, Cart $cart)
    {
        return Inertia::render('Sites/BuyProduct', [
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

        // TODO generare il pdf e persisterlo

        $cart->updateCartStep(4);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step4(Product $product, Cart $cart, $payment = null)
    {

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

            return Inertia::render('Sites/BuyProduct', [
                'product' => $productObject,
                'cart' => $cart
            ]);
        } else {
            $payment_data = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment);
            $productObject['payment'] = $payment_data;

            return Inertia::render('Sites/BuyProduct', [
                'product' => $productObject,
                'cart' => $cart
            ]);
        }
    }

    public function store_step4(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([
            'payment_gateway_id_selected' => ['required', 'integer'],
        ]);

        // recupero o genero un record di payment
        $payment = Payment::firstOrCreate(
            [
                'cart_id' => $cart->id
            ],
            [
                'payment_gateway_id' => $validated['payment_gateway_id_selected'],
                'total_payable' => $cart->total_payable,
            ]
        );

        if ($validated['payment_gateway_id_selected'] == 1) {
            // paypal
            $url = $this->handle_step4paypal($cart, $product, $payment);
            return redirect()->route('front.paypal_redirect', ['url' => $url]);
        } else if ($validated['payment_gateway_id_selected'] == 2) {
            // bbm
            // TODO add cart details
            auth()->user()->notify(new BankTransferGatewayPayment($payment));
            return redirect()->route('front.product.buy', compact('product', 'cart', 'payment'));
        }
    }

    private function handle_step4paypal(Cart $cart, Product $product, Payment $payment)
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
            ]],
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
            ]
        ]);

        // Store Token so we can retrieve after PayPal sends them back to us
        $payment->transaction_ref = $order['id'];
        $payment->save();
        $paypal_redirect_url = $order['links'][1]['href'];

        return $paypal_redirect_url;
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

    public function store_step4_paypal_return(Product $product, Cart $cart)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder(request('token'));
        $payment_id = request('payment_id');

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $payment = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment_id);
            $payment->paid = 1;
            $payment->paid_at = now();
            $payment->save();
            $cart->updateCartStep(5);
        } else {
            return redirect()
                ->route('front.product.store_step4_paypal_cancel', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment_id
                ]);
        }

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    /**
     * Transazione PayPal non andata a buon fine
     * - Mostro al cliente l'errore interrompendo il flusso d'acquisto
     * - Invio una notifica via email al cliente e in copia a golinucci
     */
    public function store_step4_paypal_cancel(Product $product, Cart $cart)
    {
        $payment_id = request('payment_id');
        $payment = Payment::query()->where(['cart_id' => $cart->id])->findOrFail($payment_id);
        // TODO invio email a cliente con notifica che il pagamento non è andato a buon fine
        $delay = now()->addMinutes(10);
        auth()->user()->notify((new PaypalGatewayPaymentCancel($payment))->delay($delay));

        // TODO invio email in copia a golinucci Francesco

        return Inertia::render('Sites/PaymentPaypalCancel', compact('product', 'cart', 'payment'));
    }

    private function step5(Product $product, Cart $cart)
    {
        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'documentGenerator' => [],
                'completedDocumentGenerator' => false
            ],
            'cart' => $cart,
        ]);
    }

    public function store_step5(Product $product, Cart $cart, Request $request)
    {
        // TODO questo dovrà essere differenziato tra utente a rinnovo e utenti nuovi (cambia il numero di allegati)
        //$file = Storage::disk('private')->get('lloyds_rinnovi_base.xml');
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);
        $certificate = Certificate::firstOrCreate(
            ['cart_id' => $cart->id],
            [
                //'certificate_no' => $certificate_no, // set in certificate boot method
                'contract_id' => 1, // TODO DA SISTEMARE
                //'renewal_date' => $proposal_date,
            ]
        );
        if (isset($request->method)) {
            switch ($request->method) {
                case 'certificate_initialization':
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
            // TODO settare cart renewal date, per i nuovi clienti, alla data di creazione del certificato
            $cart->updateCartStep(6);

            activity()
                ->causedBy($user)
                ->on($certificate)
                ->event('certificate_generation')
                ->log("Completed certificate $certificate->certificate_no generation;");

            return redirect()->route('front.product.buy', compact('product', 'cart'));
        }
    }

    private function store_step5_certificate_initialization(Cart $cart)
    {
        try {
            $certificate = $cart->certificate;
            $user = auth()->user();
            if (empty($certificate->doprint_token)) {
                activity()
                    ->causedBy($user)
                    ->on($certificate)
                    ->event('certificate_generation')
                    ->log("Initiate certificate $certificate->certificate_no generation;");
            } else {
                activity()
                    ->causedBy($user)
                    ->on($certificate)
                    ->event('certificate_generation')
                    ->log("Re-Initiate certificate $certificate->certificate_no generation due to previous error;");
            }

            return [
                'error' => false,
                'message' => 'Inizializzazione certificato completata...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on initialization;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Inizializzazione certificato fallita. Si prega di contattarci via telefono o email'
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
                'message' => 'Connessione fallita. Si prega di contattarci via telefono o email'
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

                $customer = [
                    'user_id' => $user->id, // serve per creare il percorso dove salvare i file
                    'profession' => "Medico Convenzionato",
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
                ];

                // $scheda = 'scheda/scheda.pdf'; // '../props/prop.pdf'; // TODO DA GENERARE
                $scheda = 'props/scheda.pdf';
                $wording = 'wordings/wording.pdf'; // '../wordings/wording.pdf';
                $prop = 'props/prop.pdf'; // '../props/prop.pdf';

                $contract_type = 'RC PROFESSIONALE';
                // TODO nome dipende dall-offerta selezionata, campo ente
                $product_name = "COLPA GRAVE - STRUTTURA SANITARIA PUBBLICA"; // "COLPA GRAVE – STRUTTURA SANITARIA PRIVATA"

                // IMPORTANTE la data di START deve ricadere entro la data di validità del Binder
                $start_date = '2021-06-01';
                $end_date = '2022-06-01';
                $proposal_date = '2022-06-01'; // se rinnovo metto start_date, altrimenti data di compilazione della form date('Y-m-d');
                //Setup Policy using the xml and MD5 file hashes
                $isValid = LloydsDoprintService::validationRequest(
                    $cart,
                    $start_date,
                    $end_date,
                    $proposal_date,
                    $contract_type,
                    $product_name,
                    $customer,
                    $cart_details,
                    $scheda,
                    $wording,
                    $prop
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
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on validation;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Validazione certificato fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    private function store_step5_certificate_upload(Cart $cart)
    {

        try {
            $user = auth()->user();
            $certificate = $cart->certificate;
            if ($certificate->doprint_current_step == 'upload') {

                // $scheda = 'scheda/scheda.pdf'; // '../props/prop.pdf'; // TODO DA GENERARE
                $scheda = 'props/scheda.pdf';
                $wording = 'wordings/wording.pdf'; // '../wordings/wording.pdf';
                $prop = 'props/prop.pdf'; // '../props/prop.pdf';

                // // Upload PER RINNOVI
                // Ordine allegati 1. scheda prodotto (da generare, pagina 6 della polizza),
                LloydsDoprintService::uploadRequest($cart, $scheda);

                // // Upload 2. wording
                // per pubblico file WORDING BINDER NL 2021_SECTION_A_RENEW.pdf
                // per privato WORDING BINDER NL 2021_SECTION B_RENEW.pdf
                LloydsDoprintService::uploadRequest($cart, $wording);

                //  Upload 3.attach
                // ATTACHING CLAUSES
                LloydsDoprintService::uploadRequest($cart, $prop);

                // // Upload PER NUOVI
                // Ordine allegati 1. scheda prodotto (da generare, pagina 6 della polizza), 

                // Upload 2. wording
                // per pubblico WORDING BINDER NL 2021_SECTION_A
                // per privato WORDING BINDER NL 2021_SECTION B

                // Upload 3.attach
                // ATTACHING CLAUSES

                // Upload 4. modulo di adesione
                // da generare


                $certificate->doprint_current_step = 'transaction';
                $certificate->save();
            }

            return [
                'error' => false,
                'message' => 'Upload file completato...'
            ];
        } catch (Throwable $e) {
            activity()
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on upload;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Upload file fallito. Si prega di contattarci via telefono o email'
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
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on transaction;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Transazione fallita. Si prega di contattarci via telefono o email'
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
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on Download;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Preparazione certificato fallita. Si prega di contattarci via telefono o email'
            ];
        }
    }

    // TODO
    private function step6(Product $product, Cart $cart)
    {
        $yousignClient = new YousignApiService($cart);

        // try to grab already existing signature procedure
        $signature = Signature::where('certificate_id', $cart->certificate->id)->first();

        if (empty($signature)) {
            $yousignMemberSignerId = $yousignClient->startYousignClientAndLaunchProcedure();
        } else {
            $yousignMemberSignerId = $signature->member_signer_id;
        }

        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'yousign_url' => config('yousign.app_url'),
                'yousign_member_signer_id' => $yousignMemberSignerId
            ],
            'cart' => $cart
        ]);
    }

    // TODO
    public function store_step6(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([]);

        // TODO
        $this->finalizeCart($cart);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    // TODO
    private function step7(Product $product, Cart $cart)
    {

        return Inertia::render('Sites/BuyProduct', [
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

    // TODO
    public function store_step7(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->verifyAndGetCartOrRedirect($user, $product, $cart);

        $validated = $request->validate([]);

        // TODO
        $this->finalizeCart($cart);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function verifyAndGetCartOrRedirect(User $user, Product $product, Cart $cart)
    {
        if (!empty($cart)) {
            // recupero il carrello verificando se è ancora attivo
            $cart->verifyAndGrabCart($user);
        }

        if (empty($cart)) {
            redirect()->route('front.product.buy', ['product' => $product]);
        }

        return $cart;
    }

    // TODO
    private function finalizeCart(Cart $cart)
    {
    }

    public function testImport()
    {
        Excel::import(new MedmalRenewalsImport, storage_path('app/public/' . 'database-giugno-settembre.csv'));
    }
}
