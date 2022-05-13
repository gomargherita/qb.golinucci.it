<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientDetailsRequest;
use App\Http\Requests\UpdateClientDetailsRequest;
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

    public function store(StoreClientDetailsRequest $request)
    {
        Auth::user()->account->clientDetails()->create($request->validated());

        return Redirect::route('clientDetails')->with('success', 'ClientDetail created.');
    }

    public function edit(ClientDetail $client_detail)
    {
        return Inertia::render('ClientDetails/Edit', [
            // 'can' => [
            //     'delete_client_detail' => $currentUser->can('client_details.delete')
            // ],
            'client_detail' => [
                'id' => $client_detail->id,
                'first_name' => $client_detail->first_name,
                'last_name' => $client_detail->last_name,
                'fiscal_code' => $client_detail->fiscal_code,
                'gender' => $client_detail->gender,
                'dob' => $client_detail->dob,
                'phone' => $client_detail->phone,
                'address' => $client_detail->address,
                'city' => $client_detail->city,
                'province' => $client_detail->province,
                'country' => $client_detail->country,
                'postal_code' => $client_detail->postal_code,
                'contact_ref' => $client_detail->contact_ref,
                'varsion_ref' => $client_detail->version_ref,
                'note' => $client_detail->note,
                'with_accident' => $client_detail->with_accident,
                'flag_prev_2022' => $client_detail->flag_prev_2022,
                'deleted_at' => $client_detail->deleted_at,
            ],
            'user' => $client_detail->user ? $client_detail->user : null,
        ]);
    }

    public function update(UpdateClientDetailsRequest $request, ClientDetail $client_detail)
    {
        $client_detail->update($request->validated());

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
