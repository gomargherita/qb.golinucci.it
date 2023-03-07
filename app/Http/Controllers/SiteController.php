<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Cart;
use Doctrine\DBAL\ArrayParameterType;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Certificate;



class SiteController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        // TODO rendere dinamica questa sezione: per ora estraggo solo i prodotti di golinucci
        $account = Account::with('products');
        $products = $account->find(1)->products
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'description' => $product->description,
                'hex_color' => $product->hex_color,
            ]);

        return Inertia::render('Sites/Home', [
            'products' => $products
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function aboutUs()
    {
        return Inertia::render('Sites/About-us');
    }

    public function userAccount()
    {
        $certificates = array();
        $documents = array();
        $user = auth()->user();
        $noProds = false;

        $carts = Cart::join('products', 'carts.product_id', '=', 'products.id')->where('user_id', $user->id)->where('carts.product_step_id', 7)->select(DB::raw('carts.user_id, carts.id, carts.indemnity_limit, carts.indemnity_limit_patrimonial, DATE_FORMAT(carts.retroactivity, "%d-%m-%Y") as retroactivity2, products.name as nome'))->get();
        foreach($carts as $cart){
            print_r($cart);
            $certificates[$cart->id] = Certificate::join('carts', 'carts.id', '=', 'certificates.cart_id')->where('cart_id', $cart->id)->where('product_step_id', '7')->select(DB::raw('certificates.doprint_certificate_no, DATE_FORMAT(certificates.start_validity_date, "%d-%m-%Y") as start, DATE_FORMAT(certificates.end_validity_date, "%d-%m-%Y") as end'))->get();
            $certificate_folder = 'certificates/user_' . $cart->user_id . '/cart_' . $cart->id;
            $documents[$cart->id]['certificate_final'] = Storage::disk('private')->exists($certificate_folder . '/' . 'certificate-final.pdf') ? route('privateFilesUser', $certificate_folder . '/' . 'certificate-final.pdf') : null;
            $documents[$cart->id]['declarations'] = Storage::disk('private')->exists($certificate_folder . '/' . 'declarations.pdf') ? route('privateFilesUser', $certificate_folder . '/' . 'declarations.pdf') : null;
            $documents[$cart->id]['payment_certificate'] = Storage::disk('private')->exists($certificate_folder . '/' . 'payment-certificate.pdf') ? route('privateFilesUser', $certificate_folder . '/' . 'payment-certificate.pdf') : null;
        }
        if(count($carts) == 0){
            $noProds = true;
        };

        

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
                'carts' => $carts,
                'certificates' => $certificates,
                'documents' => $documents,
                'noProds' => $noProds,
            ]
        ]);
    }

    public function products()
    {
        // TODO rendere dinamica questa sezione: per ora estraggo solo i prodotti di golinucci
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
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
            ],
        ]);
    }
}
