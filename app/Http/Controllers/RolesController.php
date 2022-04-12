<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Account;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class RolesController extends Controller
{
    public function index()
    {
        return Inertia::render('Roles/Index', [
            'filters' => Request::all('search'),
            'roles' => Auth::user()->account->roles()
                ->orderByName()
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'account' => $role->account->name,
                    // 'can' => [
                    //     'viewAny' => Auth::user()->can('viewAny', $role)
                    // ]
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create');
    }

    public function store()
    {
        Request::validate([
            'account_id' => ['required'],
            'name' => ['required', 'max:50'],
            'guard_name' => ['required', 'string']
        ]);

        Auth::user()->account->roles()->create([
            'account_id' => Request::get('account_id'),
            'name' => Request::get('name'),
            'guard_name' => Request::get('guard_name')
        ]);

        return Redirect::route('roles')->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'account_id' => $role->account_id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
            ],
            'accounts' => Account::all(),
        ]);
    }

    public function update(StoreRoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        return Redirect::back()->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return Redirect::back()->with('success', 'Role deleted.');
    }
}
