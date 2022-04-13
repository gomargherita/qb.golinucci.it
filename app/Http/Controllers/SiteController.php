<?php

namespace App\Http\Controllers;

use App\Library\Services\LloydsDoPrint;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
        // DoPrint Login
        $SessionToken = $lloydsDoPrint->HandShake();
        // Setup Policy using the xml and MD5 file hashes
        // $lloydsDoPrint->ValidationRequest($SessionToken, $nextvalue . '-LB', $customer, $start_date, $end_date, $arr, $wording, $prop);
        // Upload the wording
        //$lloydsDoPrint->UploadRequest($SessionToken, $wording);
        // Upload Proposal form
        //$lloydsDoPrint->UploadRequest($SessionToken, $prop);
        // Create the policy
        //$lloydsDoPrint->TransactionRequest($SessionToken);
        // Download the certificate
        //$lloydsDoPrint->DownloadRequest($SessionToken, $filename);
    }

    public function products()
    {
        return Inertia::render('Sites/Products', [
            'products' => Auth::user()->account->products()->get()
                ->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'icon' => $product->icon,
                    'hex_color' => $product->hex_color,
                    'deleted_at' => $product->deleted_at,
                ])
        ]);
    }

    public function product(Product $product)
    {
        return Inertia::render('Sites/Product', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'icon' => $product->icon,
                'hex_color' => $product->hex_color,
                'deleted_at' => $product->deleted_at,
            ]
        ]);
    }

    public function buy(Product $product)
    {
        return Inertia::render('Sites/Product', compact('product'));
    }
}
