<?php

namespace App\Http\Controllers;

use App\Library\Services\LloydsDoPrint;
use App\Models\Account;
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
        // per ora estraggo solo i prodotti di golinucci
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
        $product = $product->with('productOffers.conditionalFields')->find($product->id);

        return Inertia::render('Sites/Product', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'icon' => $product->icon,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers, //->with(['conditionalFields']),
                'deleted_at' => $product->deleted_at,
            ],
            'conditions' => $product->conditionalFields,
        ]);
    }

    public function step1(Product $product)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::query()
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        return Inertia::render('Sites/BuyProduct', compact('product', 'clientDetails'));
    }

    public function store_step1(Product $product, Request $request)
    {
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
            'correctness_of_data' => ['required']
        ]);

        ClientDetail::create($validated);

        return redirect()->route('front.product.step2', compact('product'));
    }

    public function step2(Product $product)
    {
        $user = auth()->user();
        $clientDetails = ClientDetail::query()
            ->where([
                'user_id' => $user->id
            ])->latest('id')->first();

        return Inertia::render('Sites/BuyProduct', compact('product', 'clientDetails'));
    }

    public function store_step2(Request $request)
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
            'correctness_of_data' => ['required']
        ]);

        ClientDetail::create($validated);

        return redirect()->route('front.product.step2');
    }
}
