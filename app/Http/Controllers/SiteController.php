<?php

namespace App\Http\Controllers;

use App\Imports\MedmalRenewalsImport;
use App\Services\LloydsDoprintService;
use App\Models\Account;
use App\Models\Cart;
use App\Models\Certificate;
use App\Models\ClientDetail;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductCounter;
use App\Models\ProductOffer;
use App\Notifications\BankTransferGatewayPayment;
use App\Notifications\PaypalGatewayPaymentCancel;
use App\Rules\ProjectDeclarationsRule;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Nidrax69\YousignApiLaravel\YousignApiLaravel;
use Throwable;

class SiteController extends Controller
{

    private $lloydsDoprintService;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return Inertia::render('Sites/Home');
    }

    /**
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function aboutUs()
    {
        return Inertia::render('Sites/About-us');
    }

    public function userAccount()
    {
        $user = auth()->user();

        return Inertia::render('Sites/User-account', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                //'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'role' => $user->getRoleNames()->first(),
                'client_details' => $user->clientDetails ? $user->clientDetails : null,
            ]
        ]);
    }

    public function products()
    {
        // TODO rendere dinamica questa sezione
        // Per ora estraggo solo i prodotti di golinucci
        $account = Account::with('products');
        $products = $account->find(1)->products
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'description' => $product->description,
                'hex_color' => $product->hex_color,
            ]);

        return Inertia::render('Sites/Products', [
            'products' => $products
        ]);
    }

    public function product(Product $product)
    {
        $product = $product->load([
            'productOffers' => function ($query) {
                $flag_prev_2022 = 0;
                $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022);
            }
        ]);

        return Inertia::render('Sites/Product', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
            ],
        ]);
    }

    // contiene lo step successivo passo $product Cart $cart
    public function buy(Product $product, Cart $cart = null)
    {
        $user = auth()->user();

        if (!empty($cart)) {
            // recupero il carrello verificando se è ancora attivo
            $cart = $this->verifyAndGrabCart($user, $cart);

            // verifico se il carrello esiste o è ancora attivo altrimenti forzo a null per crearne uno nuovo
            if (empty($cart)) {
                return redirect()->route('front.product.buy', ['product' => $product, 'cart' => null]);
            } else {
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
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

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
        $cart = $this->prepareBeforeStore($user, $product, $cart);

        $validated = $request->validate([
            'user_id' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'fiscal_code' => ['required'],
            'gender' => ['required'],
            'dob' => ['nullable'],
            'address' => ['required'],
            'city' => ['required'],
            'province' => ['required'],
            'country' => ['required'],
            'postal_code' => ['required'],
            'phone' => ['required'],
            'confirmation' => ['accepted']
        ]);

        ClientDetail::updateOrCreate(['user_id'   => $user->id], $validated);

        $this->updateCartStep($cart, 2);

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
                'steps' => $product->productSteps->count()
            ],
            'cart' => $cart
        ]);
    }

    public function store_step2(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->prepareBeforeStore($user, $product, $cart);

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

        $this->updateCartStep($cart, 3);

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
        $cart = $this->prepareBeforeStore($user, $product, $cart);

        $validated = $request->validate([
            'signature' => ['required', 'string', 'min:5'],
            'selected_declarations' => ['required', 'array', new ProjectDeclarationsRule($product->productDeclarations)],
            'selected_declarations.*' => ['integer', 'exists:product_declarations,id'],
        ]);

        // salvo nel db la firma delle dichiarazioni, da usare poi per la generazione del documento
        // nota: il salvataggio avviene poco sotto, nel metodo updateCartStep
        $cart->declarations_signature = $validated['signature'];

        // TODO generare il pdf e persisterlo

        $this->updateCartStep($cart, 4);

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
        $cart = $this->prepareBeforeStore($user, $product, $cart);

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
            $this->updateCartStep($cart, 5);
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
                'documentGenerator' => []
            ],
            'cart' => $cart,
        ]);
    }

    private function store_step5_certificate_initialization(Cart $cart)
    {
        try {
            $user = auth()->user();

            $certificate = Certificate::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'cart_id' => $cart->id,
                    'product_id' => $cart->product_id,
                    'product_offer_id' => $cart->product_offer_id,
                ],
                [
                    //'certificate_no' => $certificate_no, // set in certificate boot method
                    //'certificate_doprint_no' => '', //$lloydsDoprintService->certificateNo,
                    'contract_id' => 1, // TODO DA SISTEMARE
                    //'renewal_date' => $proposal_date,
                    'total_payable' => $cart->total_payable,
                    'admin_fee' => $cart->admin_fee,
                    'gross_premium' => $cart->gross_premium,
                    'tax' => $cart->tax,
                    'net_premium' => $cart->net_premium,
                    'accessory' => $cart->accessory,
                    'net_plus_access_premium' => $cart->net_plus_access_premium,
                    'indemnity_limit' => $cart->indemnity_limit,
                    'indemnity_limit_patrimonial' => $cart->indemnity_limit_patrimonial,
                ]
            );

            activity()
                ->causedBy($user)
                ->on($certificate)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate $certificate->certificate_no;");

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

    private function store_step5_certificate_generation(Cart $cart)
    {
        try {
            $user = auth()->user();
            // get certificate
            $certificate = Certificate::where([
                'user_id' => $user->id,
                'cart_id' => $cart->id,
            ])->first();
            // IMPORTANTE la data di START deve ricadere entro la data di validità del Binder
            $start_date = '2021-06-01';
            $end_date = '2022-06-01';
            $proposal_date = '2022-06-01'; // se rinnovo metto start_date, altrimenti data di compilazione della form date('Y-m-d');
            $this->lloydsDoprintService = new LloydsDoprintService($certificate, $start_date, $end_date, $proposal_date);
            $user = auth()->user();
            $this->lloydsDoprintService->HandShake();
        } catch (Throwable $e) {
            activity()
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on handshake;" . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Connessione fallita. Si prega di contattarci via telefono o email'
            ];
        }

        try {
            $this->store_step5_certificate_validation($cart);
        } catch (Throwable $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        try {
            $this->store_step5_certificate_upload($cart);
        } catch (Throwable $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    private function store_step5_certificate_validation(Cart $cart)
    {
        try {
            $user = auth()->user();
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

            //Setup Policy using the xml and MD5 file hashes
            $isValid = $this->lloydsDoprintService->ValidationRequest(
                $contract_type,
                $product_name,
                $customer,
                $cart_details,
                $scheda,
                $wording,
                $prop
            );

            return $isValid;
        } catch (Throwable $e) {
            activity()
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on validation;" . $e->getMessage());
            throw new \Exception('Validazione fallita. Si prega di contattarci via telefono o email');
        }
    }

    private function store_step5_certificate_upload(Cart $cart)
    {

        try {
            $user = auth()->user();

            // $scheda = 'scheda/scheda.pdf'; // '../props/prop.pdf'; // TODO DA GENERARE
            $scheda = 'props/scheda.pdf';
            $wording = 'wordings/wording.pdf'; // '../wordings/wording.pdf';
            $prop = 'props/prop.pdf'; // '../props/prop.pdf';

            // // Upload PER RINNOVI
            // Ordine allegati 1. scheda prodotto (da generare, pagina 6 della polizza),
            $this->lloydsDoprintService->UploadRequest($scheda);

            // // Upload 2. wording
            // per pubblico file WORDING BINDER NL 2021_SECTION_A_RENEW.pdf
            // per privato WORDING BINDER NL 2021_SECTION B_RENEW.pdf
            $this->lloydsDoprintService->UploadRequest($wording);

            //  Upload 3.attach
            // ATTACHING CLAUSES
            $this->lloydsDoprintService->UploadRequest($prop);

            // // Upload PER NUOVI
            // Ordine allegati 1. scheda prodotto (da generare, pagina 6 della polizza), 

            // Upload 2. wording
            // per pubblico WORDING BINDER NL 2021_SECTION_A
            // per privato WORDING BINDER NL 2021_SECTION B

            // Upload 3.attach
            // ATTACHING CLAUSES

            // Upload 4. modulo di adesione
            // da generare

        } catch (Throwable $e) {
            activity()
                ->causedBy($user)
                ->event('certificate_generation')
                ->log("An error occurred on generating a certificate on validation;" . $e->getMessage());
            throw new \Exception('Validazione fallita. Si prega di contattarci via telefono o email');
        }
    }

    private function store_step5_certificate_transaction()
    {
        try {
            $this->lloydsDoprintService->TransactionRequest();
        } catch (Throwable $e) {
            report($e);
            $reportGeneration = $e->getMessage();

            return false;
        }
    }

    private function store_step5_certificate_download()
    {
        try {
            // // Download the certificate
            $filename = 'test-download.pdf';
            $this->lloydsDoprintService->DownloadRequest($filename);
        } catch (Throwable $e) {
            report($e);
            $reportGeneration = $e->getMessage();
            return false;
        }
    }

    // TODO
    public function store_step5(Product $product, Cart $cart, Request $request)
    {
        // TODO questo dovrà essere differenziato tra utente a rinnovo e utenti nuovi (cambia il numero di allegati)
        //$file = Storage::disk('private')->get('lloyds_rinnovi_base.xml');
        $user = auth()->user();

        //return response()->json(['errors' => false, 'data' => $user->email]);
        //return Redirect::route('package.index');


        switch ($request->method) {
            case 'certificate_initialization':
                $response = $this->store_step5_certificate_initialization($cart);
                break;
            case 'generation':

                $response = $this->store_step5_certificate_generation($cart);
                break;
        }

        return response()->json($response);



        // TODO settare cart renewal date, per i nuovi clienti, alla data di creazione del certificato


        $user = auth()->user();
        $cart = $this->prepareBeforeStore($user, $product, $cart);

        $validated = $request->validate([]);

        $this->updateCartStep($cart, 6);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    // TODO
    private function step6(Product $product, Cart $cart)
    {
        $user = auth()->user();
        // $users = Yousign::getUsers();
        $certificate_folder = 'certificates/user_' . $user->id . '/cart_' . $cart->id;
        $storagePath  = Storage::disk('private')->getDriver()->getAdapter()->getPathPrefix();
        // non posso usare lo storage perch{ la libreria usa get_contents e si spacca
        //$certificate_pdf = Storage::disk('private')->get($certificate_folder . '/certificate.pdf');
        $certificate_pdf = $storagePath . $certificate_folder . '/certificate.pdf';

        $client = new YousignApiLaravel();

        // see all possibilities at https://dev.yousign.com/
        $client->setProcedureKeyValue('name', 'Polizza Medici Colpa Grave');
        $client->setProcedureKeyValue('description', 'Polizza Medici Colpa Grave ' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')');
        // to set default expiration date to the signature
        $client->setProcedureKeyValue('expiresAt', '2022-05-24');

        // add webhooks 
        // you can add different headers
        $webhookUrl = env('YOUSIGN_API_URL') . 'yousign/';
        $client->addWebhook('member.finished', $webhookUrl . 'signature', 'GET', array(
            //"X-Custom-Header" => $type . '-signature',
        ));
        $client->addWebhook('procedure.refused', $webhookUrl . 'refused', 'GET', array(
            //"X-Custom-Header" => $type . '-signature',
        ));
        // procedure created but not ready for signature
        $procedure = $client->createProcedure();

        // Allows you to define the content of SMS. {{code}} will be used to define the security code managed by Yousign.
        // up to 150 characters
        $client->addSmsContent('DIGITAL SIGNATURE - {{code}} is your security code to sign your documents.');

        // add files to procedure
        $file = $client->addFile('certificate.pdf', $certificate_pdf, $procedure['id']);

        // add member to sign the documents
        $member = $client->addMember('Marco', 'Masi', 'masiorama@gmail.com', '+393299858148', $procedure['id']);

        // to determine the last page of your file
        $lastPageNumber = $client->getNumPagesInPDF($certificate_pdf);

        $reason = "Signed by " . $user->firstname . " " . $user->lastname . " (Yousign)";

        // to determine position see https://placeit.yousign.fr/
        $position = '252,545,352,586';
        $fileObject = $client->addFileObject($file['id'], $member['id'], $position, $reason, $lastPageNumber);

        // fileObject ritorna ciò
        // array(13) { ["mention"]=> NULL ["mention2"]=> NULL ["member"]=> array(17) { ["id"]=> string(45) "/members/b3ee30b9-f643-4a94-8470-cfd85f2030eb" ["user"]=> NULL ["type"]=> string(6) "signer" ["firstname"]=> string(5) "Marco" ["lastname"]=> string(4) "Masi" ["email"]=> string(19) "masiorama@gmail.com" ["phone"]=> string(13) "+393299858148" ["position"]=> int(1) ["createdAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["updatedAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["startedAt"]=> NULL ["finishedAt"]=> NULL ["status"]=> string(7) "pending" ["comment"]=> NULL ["parent"]=> NULL ["contact"]=> NULL ["fields"]=> array(2) { ["company"]=> NULL ["job_title"]=> NULL } } ["fieldName"]=> NULL ["reason"]=> string(21) "Signed by (Yousign)" ["id"]=> string(50) "/file_objects/cfcb2839-1070-4e43-8dc5-239b9def60a9" ["type"]=> string(9) "signature" ["file"]=> string(43) "/files/9894b0b5-2f8b-4cfa-9d17-b4d36e9f0b6f" ["page"]=> int(1) ["position"]=> string(15) "252,545,352,586" ["createdAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["updatedAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["parent"]=> NULL }

        // start the signature process
        $client->launchProcedure($procedure['id']);
        return Inertia::render('Sites/BuyProduct', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'hex_color' => $product->hex_color,
                'steps' => $product->productSteps->count(),
                'yousign_url' => env('YOUSIGN_APP_URL'),
                'yousign' => $fileObject
            ],
            'cart' => $cart
        ]);
    }

    // TODO
    public function store_step6(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->prepareBeforeStore($user, $product, $cart);

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
        $cart = $this->prepareBeforeStore($user, $product, $cart);

        $validated = $request->validate([]);

        // TODO
        $this->finalizeCart($cart);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function prepareBeforeStore(User $user, Product $product, Cart $cart)
    {
        if (!empty($cart)) {
            // recupero il carrello verificando se è ancora attivo
            $cart = $this->verifyAndGrabCart($user, $cart);
        }

        if (empty($cart)) {
            redirect()->route('front.product.buy', ['product' => $product]);
        }

        return $cart;
    }

    private function updateCartStep(Cart $cart, int $next_step)
    {
        $cart->product_step_id = $next_step;
        return $cart->save();
    }

    // TODO
    private function finalizeCart(Cart $cart)
    {
    }

    private function verifyAndGrabCart(User $user, Cart $cart)
    {
        return Cart::query()
            ->where([
                'user_id' => $user->id,
                'cart_ref' => $cart->cart_ref,
                'locked' => 'N'
            ])->first();
    }



    public function testImport()
    {
        Excel::import(new MedmalRenewalsImport, storage_path('app/public/' . 'database-giugno-settembre.csv'));
    }
}
