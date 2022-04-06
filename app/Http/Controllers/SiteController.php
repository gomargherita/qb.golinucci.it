<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Models\Site;
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
}
