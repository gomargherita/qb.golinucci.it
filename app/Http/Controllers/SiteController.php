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
        return Inertia::render('Sites/Products');
    }
}
