<?php

namespace App\Http\Controllers;

use App\Library\Services\LloydsDoPrint;
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
        $products = [
            0 => [
                'icon' => 'icon1',
                'title' => 'title1',
                'description' => 'description1',
                'label' => 'label1',
                'url' => 'url1',
                'img' => 'img1',
                'color' => '#6CB8EB'
            ],
            1 => [
                'icon' => 'icon2',
                'title' => 'title2',
                'description' => 'description2',
                'label' => 'label2',
                'url' => 'url2',
                'img' => 'img2',
                'color' => '#F17831'
            ],
        ];

        return Inertia::render('Sites/Products', compact('products'));
    }

    public function product(Product $product)
    {
        return Inertia::render('Sites/Product', compact('product'));
    }

    public function buy(Product $product)
    {
        return Inertia::render('Sites/Product', compact('product'));
    }

}
