<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function get($file)
    {
        return Storage::disk('private')->get($file);
    }
}
