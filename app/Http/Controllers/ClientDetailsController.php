<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ClientDetailsController extends Controller
{
    public function index()
    {
        return Inertia::render('ClientDetails/Index', [
            'filters' => Request::all('search', 'trashed'),
            'clientDetails' => Auth::user()->account->clientDetails()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($client_detail) => [
                    'id' => $client_detail->id,
                    'name' => $client_detail->name,
                    'phone' => $client_detail->phone,
                    'city' => $client_detail->city,
                    'deleted_at' => $client_detail->deleted_at,
                    'user' => $client_detail->user ? $client_detail->user->only('id', 'name') : null,
                ]),
        ]);
    }
    /**
     * Creo anagrafica solo associata a uno specifico user
     */
    public function create(User $user)
    {
        if (empty($user)) {
            abort(404);
        }

        // check if user belongs to account
        $checkedUser = Auth::user()->account->users()->where('id', $user->id)->first();

        if (empty($checkedUser)) {
            abort(302);
        }

        return Inertia::render('ClientDetails/Create', ['user' => $checkedUser]);
    }

    public function store()
    {
        Auth::user()->account->clientDetails()->create(
            Request::validate([
                'user_id' => ['required', Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'first_name' => ['required', 'max:100'],
                'last_name' => ['required', 'max:100'],
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
            // 'can' => [
            //     'delete_client_detail' => auth()->user()->can('delete_client_detail', $client_detail)
            // ],
            'client_detail' => [
                'id' => $client_detail->id,
                'first_name' => $client_detail->first_name,
                'last_name' => $client_detail->last_name,
                'phone' => $client_detail->phone,
                'address' => $client_detail->address,
                'city' => $client_detail->city,
                'region' => $client_detail->region,
                'country' => $client_detail->country,
                'postal_code' => $client_detail->postal_code,
                'deleted_at' => $client_detail->deleted_at,
            ],
            'user' => $client_detail->user ? $client_detail->user : null,
        ]);
    }

    public function update(ClientDetail $client_detail)
    {
        $client_detail->update(
            Request::validate([
                'first_name' => ['required', 'max:100'],
                'last_name' => ['required', 'max:100'],
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
