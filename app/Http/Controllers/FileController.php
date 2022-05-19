<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class FileController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function get($file)
    {
        return FacadesStorage::disk('private')->get($file);
    }
}
