<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DeclarationsToPdfService
{

    protected $cart;

    public function __construct($cart)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for DeclarationToPdf precedure");
        }
        $this->cart = $cart;
    }

    public function generateAndPersist()
    {
        $product = Product::where('id', $this->cart->product_id)->first();
        $declarations = $product->productDeclarations;
        $data = [
            'title' => 'Dichiarazioni dell\'Assicurato',
            'pre_declarations' => $product->pre_declarations,
            'declarations' => $declarations,
            'post_declarations' => $product->post_declarations,
            'declarations_signature' => $this->cart->declarations_signature
        ];

        PDF::setOptions([
            // 'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'fontHeightRatio' => 1,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true
        ]);

        $pdf = PDF::loadView('template-declarations', $data);
        $policy_response_folder = 'certificates/user_' . $this->cart->user_id . '/cart_' . $this->cart->id;
        Storage::disk('private')->put($policy_response_folder . '/declarations.pdf', $pdf->stream());
    }
}
