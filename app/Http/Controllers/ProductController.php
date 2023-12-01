<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Products/Index', [
            'filters' => Request::all('search', 'trashed'),
            'products' => Product::query()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' =>  $product->productType->name,
                    'deleted_at' => $product->deleted_at,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product_types = ProductType::all();
        return Inertia::render('Products/Create', compact('product_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Auth::user()->account->products()->create($request->validated());
        if ($request->file('image')) {
            $product->addMediaFromRequest('image')
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('product');
        }

        return Redirect::route('products')->with('success', 'Product created.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product_types = ProductType::all();

        return Inertia::render('Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'client_details_declaration' => $product->client_details_declaration,
                'pre_declarations' => $product->pre_declarations,
                'post_declarations' => $product->post_declarations,
                'payments_description' => $product->payments_description,
                'hex_color' => $product->hex_color,
                'owner' => $product->owner,
                'active' => $product->active,
                'image' => $product->getFirstMedia('product'),
                // 'icon' => $product->icon ? URL::route('image', ['path' => $product->icon, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                'product_type_id' => $product->productType()->first()->id,
                'deleted_at' => $product->deleted_at,
            ],
            'product_types' => $product_types,
            'product_counters' => $product->productCounters()->with('paymentGateway')->get(),
            'doprint_counter' => $product->doprintCounters
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        if ($request->file('image')) {
            // recupero il precedente e cancello
            $prev_image = $product->getFirstMedia('product');
            if ($prev_image) {
                $prev_image->delete();
            }

            $product->addMediaFromRequest('image')
                ->sanitizingFileName(function ($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection('product');
        }

        // TODO fare il sync con tutte le entità necessarie, tipo offerte, ecc
        // if ($request->get('role')) {
        //     $user->syncRoles($request->get('role'));
        // }

        return Redirect::back()->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return Redirect::back()->with('success', 'Product deleted.');
    }

    public function restore(Product $product)
    {
        $product->restore();

        return Redirect::back()->with('success', 'Product restored.');
    }
}
