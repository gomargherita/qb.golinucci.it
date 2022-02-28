<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ClientDetailsController extends Controller
{
    public function index()
    {
        return Inertia::render('ClientDetails/Index', [
            'filters' => Request::all('search', 'trashed'),
            'clientDetails' => Auth::user()->account->clientDetails()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($client_detail) => [
                    'id' => $client_detail->id,
                    'name' => $client_detail->name,
                    'phone' => $client_detail->phone,
                    'city' => $client_detail->city,
                    'deleted_at' => $client_detail->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('ClientDetails/Create');
    }

    public function store()
    {
        Auth::user()->account->clientDetails()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('clientDetails')->with('success', 'ClientDetail created.');
    }

    public function edit(ClientDetail $client_detail)
    {
        return Inertia::render('ClientDetails/Edit', [
            'client_detail' => [
                'id' => $client_detail->id,
                'name' => $client_detail->name,
                'email' => $client_detail->email,
                'phone' => $client_detail->phone,
                'address' => $client_detail->address,
                'city' => $client_detail->city,
                'region' => $client_detail->region,
                'country' => $client_detail->country,
                'postal_code' => $client_detail->postal_code,
                'deleted_at' => $client_detail->deleted_at,
                'contacts' => $client_detail->contacts()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
            ],
        ]);
    }

    public function update(ClientDetail $client_detail)
    {
        $client_detail->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'ClientDetail updated.');
    }

    public function destroy(ClientDetail $client_detail)
    {
        $client_detail->delete();

        return Redirect::back()->with('success', 'ClientDetail deleted.');
    }

    public function restore(ClientDetail $client_detail)
    {
        $client_detail->restore();

        return Redirect::back()->with('success', 'ClientDetail restored.');
    }
}