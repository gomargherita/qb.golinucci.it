<?php

namespace App\Http\Controllers;

use App\Library\Services\LloydsDoPrint;
use App\Models\Account;
use App\Models\Cart;
use App\Models\ClientDetail;
use App\Models\Product;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

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
                'deleted_at' => $user->deleted_at,
                'role' => $user->getRoleNames()->first(),
                'client_details' => $user->clientDetails ? $user->clientDetails : null,
            ]
        ]);
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
                'deleted_at' => $product->deleted_at,
            ]);

        return Inertia::render('Sites/Products', [
            'products' => $products
        ]);
    }

    public function product(Product $product)
    {
        // $invoice = 'invoice';
        // auth()->user()->notify(new InvoicePaid($invoice));
        // TODO necessario rieseguire così le query per avere le relazioni?
        $product = $product->with([
            'productOffers.conditionalFields',
            'productOffers.contract',
        ])->find($product->id);

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
                'deleted_at' => $product->deleted_at,
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
            $cart = Cart::query()
                ->where([
                    'user_id' => $user->id,
                    'cart_ref' => $cart->cart_ref,
                    'locked' => 'N'
                ])->first();

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
                        return $this->step4($product, $cart);
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
                'cart_start_time' => now(),
                'cart_expiry_time' => now(),
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

        // return Inertia::render('Sites/BuyProduct', [
        //     [
        //         'product' => [
        //             'id' => $product->id,
        //             'name' => $product->name,
        //             'type' => Str::slug($product->productType->name, '-'),
        //             'detailed_description' => $product->detailed_description,
        //             'icon' => $product->icon,
        //             'hex_color' => $product->hex_color,
        //             'offers' => $product->productOffers, //->with(['conditionalFields']),
        //             'deleted_at' => $product->deleted_at,
        //         ],
        //         'conditions' => $product->conditionalFields,
        //         'clientDetails' => $clientDetails
        //     ]
        // ]);
    }

    private function step2(Product $product, Cart $cart)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::query()
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        return Inertia::render('Sites/BuyProduct', compact('product', 'clientDetails'));
    }

    public function store_step1(Product $product, Cart $cart, Request $request)
    {
        $user = auth()->user();

        if (!empty($cart)) {
            // recupero il carrello verificando se è ancora attivo
            $cart = Cart::query()
                ->where([
                    'user_id' => $user->id,
                    'cart_ref' => $cart->cart_ref,
                    'locked' => 'N'
                ])->first();
        }

        if (empty($cart)) {
            redirect()->route('front.product.buy', ['product' => $product]);
        }

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
            'confirmation' => ['required']
        ]);

        $clientDetail = ClientDetail::query()
            ->where(['user_id' => $user->id]);
        if ($clientDetail) {
            $clientDetail->update($validated);
        } else {
            ClientDetail::create($validated);
        }

        $cart->product_step_id = 2;
        $cart->save();

        return redirect()->route('front.product.buy', compact('product', 'cart'));
    }

    public function store_step2(Product $product, Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'fiscal_code' => ['required'],
            'address' => ['required'],
            'region' => ['required'],
            'postal_code' => ['required'],
            'phone' => ['required'],
            'qualification' => ['required'],
            'authority' => ['required'],
        ]);

        ClientDetail::create($validated);

        return redirect()->route('front.product.step2');
    }

    private function step3(Product $product)
    {
        return Inertia::render('Sites/BuyProduct', compact('product'));
    }

    public function store_step3(Product $product, Request $request)
    {
        $validated = $request->validate([]);

        return redirect()->route('front.product.step3');
    }

    // private function step4(Product $product)
    // {
    //     return Inertia::render('Sites/BuyProduct', compact('product'));
    // }

    // public function store_step4(Product $product, Request $request)
    // {
    //     $validated = $request->validate([

    //     ]);

    //     return redirect()->route('front.product.step3');
    // }

    // private function step5(Product $product)
    // {
    //     return Inertia::render('Sites/BuyProduct', compact('product'));
    // }

    // public function store_step5(Product $product, Request $request)
    // {
    //     $validated = $request->validate([

    //     ]);

    //     return redirect()->route('front.product.step3');
    // }

    // private function step6(Product $product)
    // {
    //     return Inertia::render('Sites/BuyProduct', compact('product'));
    // }

    // public function store_step6(Product $product, Request $request)
    // {
    //     $validated = $request->validate([

    //     ]);

    //     return redirect()->route('front.product.step3');
    // }

}
