<?php

namespace App\Http\Controllers;

use App\Library\Services\LloydsDoPrint;
use App\Models\Account;
use App\Models\Cart;
use App\Models\ClientDetail;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ProductOffer;
use App\Notifications\BankTransfer;
use App\Notifications\InvoicePaid;
use App\Rules\ProjectDeclarationsRule;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class SiteController extends Controller
{
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
                'icon' => $product->icon,
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
                'conditions' => $product->conditions,
                'icon' => $product->icon,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
            ],
            'conditions' => $product->conditionalFields,
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
                        // optional payment 
                        return $this->step4($product, $cart, request('payment'));
                        break;
                    case 5:
                        return $this->step5($product, $cart);
                        break;
                    case 6:
                        return $this->step6($product, $cart);
                        break;
                }
            }
        } else {
            // se cart assente o locked, genero un cart ref e creo il carrello
            $cart = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_step_id' => 1,
                'expired_at' => now(), // TODO Francesco come calcolare?
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

        return Inertia::render('Sites/BuyProduct', compact('product', 'clientDetails', 'cart'));
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
            'address' => ['required'],
            'city' => ['required'],
            'region' => ['required'],
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
                'icon' => $product->icon,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
            ],
            'conditions' => $product->conditionalFields,
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
                'icon' => $product->icon,
                'hex_color' => $product->hex_color,
            ],
            'conditions' => $product->conditionalFields,
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
        if (empty($payment)) {
            $payment_gateways = PaymentGateway::where(['public' => 1])->get();

            return Inertia::render('Sites/BuyProduct', [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' => Str::slug($product->productType->name, '-'),
                    'payment_gateways' => $payment_gateways,
                    'payments_description' => $product->payments_description,
                    'icon' => $product->icon,
                    'hex_color' => $product->hex_color,
                ],
                'conditions' => $product->conditionalFields,
                'cart' => $cart
            ]);
        } else {
            $payment_data = Payment::findOrFail($payment);

            return Inertia::render('Sites/BuyProduct', [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' => Str::slug($product->productType->name, '-'),
                    'payments_description' => $product->payments_description,
                    'payment' => $payment_data,
                    'icon' => $product->icon,
                    'hex_color' => $product->hex_color,
                ],
                'conditions' => $product->conditionalFields,
                'cart' => $cart
            ]);
        }
    }

    // TODO
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
                'cart_id' => $cart->id,
                'payment_gateway_id' => $validated['payment_gateway_id_selected']
            ],
            [
                'total_payable' => $cart->total_payable,
            ]
        );

        // TODO
        switch ($validated['payment_gateway_id_selected']) {
            case 1:
                // paypal
                $this->handle_step4paypal($cart, $payment);
                $this->updateCartStep($cart, 5);
                break;
            case 2:
                // bbm
                auth()->user()->notify(new BankTransfer($payment));

                return redirect()->route('front.product.buy', compact('product', 'cart', 'payment'));
                break;
            default:
        }

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    // TODO TO FINISH
    private function handle_step4paypal(Cart $cart, Payment $payment)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal')); // Pull values from Config
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Prepare Order
        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => 'transaction_number' . $cart->id,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => $cart->total_payable
                ]
            ]],
            'application_context' => [
                'cancel_url' => 'https://laravel-paypal-app.loc/cancel', //route('post.show', ['post' => 1]);
                'return_url' => 'https://laravel-paypal-app.loc/success'
            ]
        ]);

        dd($order);

        // Store Token so we can retrieve after PayPal sends them back to us
        $payment->transaction_ref = $order['id'];
        $payment->save();

        // Send user to PayPal to confirm payment
        return redirect($order['links'][1]['href'])->send();
        // porto l-utente  allo step successivo se pagamento andato a buon fine
    }

    private function step5(Product $product, Cart $cart)
    {
        //     return Inertia::render('Sites/BuyProduct', compact('product'));
        // }

        // private function step6(Product $product)
        // {
        //     return Inertia::render('Sites/BuyProduct', compact('product'));
    }

    // TODO
    public function store_step5(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();
        $cart = $this->prepareBeforeStore($user, $product, $cart);

        $validated = $request->validate([]);

        $this->updateCartStep($cart, 6);

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    private function step6(Product $product, Cart $cart)
    {
        //     return Inertia::render('Sites/BuyProduct', compact('product'));
        // }

        // private function step6(Product $product)
        // {
        //     return Inertia::render('Sites/BuyProduct', compact('product'));
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

    public function testLloyds(LloydsDoPrint $lloydsDoPrint)
    {
        //     // DoPrint Login
        //     $SessionToken = $lloydsDoPrint->HandShake();
        //     // Setup Policy using the xml and MD5 file hashes
        //     // $lloydsDoPrint->ValidationRequest($SessionToken, $nextvalue . '-LB', $customer, $start_date, $end_date, $arr, $wording, $prop);
        //     // Upload the wording
        //     //$lloydsDoPrint->UploadRequest($SessionToken, $wording);
        //     // Upload Proposal form
        //     //$lloydsDoPrint->UploadRequest($SessionToken, $prop);
        //     // Create the policy
        //     //$lloydsDoPrint->TransactionRequest($SessionToken);
        //     // Download the certificate
        //     //$lloydsDoPrint->DownloadRequest($SessionToken, $filename);
    }
}
