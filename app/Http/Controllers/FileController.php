<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function get($file)
    {
        if (Storage::disk('private')->exists($file)) {
            $storage_private = Storage::disk('private');
            return $storage_private->download($file);
        } else {
            abort('Requested file does not exist on our server.');
        }
    }
}
