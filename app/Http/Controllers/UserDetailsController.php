<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class UserDetailsController extends Controller
{
    public function index()
    {
        return Inertia::render('UserDetails/Index', [
            'filters' => Request::all('search', 'trashed'),
            'userDetails' => Auth::user()->account->userDetails()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user_detail) => [
                    'id' => $user_detail->id,
                    'name' => $user_detail->name,
                    'phone' => $user_detail->phone,
                    'city' => $user_detail->city,
                    'deleted_at' => $user_detail->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('UserDetails/Create');
    }

    public function store()
    {
        Auth::user()->account->userDetails()->create(
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

        return Redirect::route('userDetails')->with('success', 'UserDetail created.');
    }

    public function edit(UserDetail $user_detail)
    {
        return Inertia::render('UserDetails/Edit', [
            'user_detail' => [
                'id' => $user_detail->id,
                'name' => $user_detail->name,
                'email' => $user_detail->email,
                'phone' => $user_detail->phone,
                'address' => $user_detail->address,
                'city' => $user_detail->city,
                'region' => $user_detail->region,
                'country' => $user_detail->country,
                'postal_code' => $user_detail->postal_code,
                'deleted_at' => $user_detail->deleted_at,
                'contacts' => $user_detail->contacts()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
            ],
        ]);
    }

    public function update(UserDetail $user_detail)
    {
        $user_detail->update(
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

        return Redirect::back()->with('success', 'UserDetail updated.');
    }

    public function destroy(UserDetail $user_detail)
    {
        $user_detail->delete();

        return Redirect::back()->with('success', 'UserDetail deleted.');
    }

    public function restore(UserDetail $user_detail)
    {
        $user_detail->restore();

        return Redirect::back()->with('success', 'UserDetail restored.');
    }
}