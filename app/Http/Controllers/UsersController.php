<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account' => $user->account->name,
                    'owner' => $user->owner,
                    //'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        $roles = Auth::user()->account->roles()->get();
        return Inertia::render('Users/Create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = Auth::user()->account->users()->create($request->validated());
        // 'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,

        $user->assignRole(Role::ROLE_USER);

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $roles = Auth::user()->account->roles()->get();

        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                //'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
                'role' => $user->getRoleNames()->first(),
                'client_details' => $user->clientDetails ? $user->clientDetails : null,
            ],
            'roles' => $roles
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        // if (Request::file('photo')) {
        //     $user->update(['photo_path' => Request::file('photo')->store('users')]);
        // }

        if ($request->get('password')) {
            $user->update(['password' => $request->get('password')]);
        }

        if ($request->get('role')) {
            $user->syncRoles($request->get('role'));
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
