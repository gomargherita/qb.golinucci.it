<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Str;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        // set permission team based on user roles
        if ($request->user()) {
            setPermissionsTeamId($request->user()->account_id);
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'first_name' => $request->user()->first_name,
                    'last_name' => $request->user()->last_name,
                    'email' => $request->user()->email,
                    'owner' => $request->user()->owner,
                    'account' => [
                        'id' => $request->user()->account->id,
                        'name' => $request->user()->account->name,
                    ],
                    'roles' => $request->user()->roles,
                    'is_superadmin' => $request->user()->can(Role::ROLE_SUPERADMIN)
                ] : null,
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'locale' => function () {
                return app()->getLocale();
            },
            'language' => function () {
                return translations(
                    resource_path('lang/' . app()->getLocale() . '.json')
                );
            },
            'errors' => function () use ($request) {
                $flag_cd = false;
                $coll_original = $this->resolveValidationErrors($request);
                foreach ($coll_original as $key => $value) {
                    if (Str::startsWith($key, 'product_client_details.')) {
                        unset($coll_original->$key);
                        $flag_cd = true;
                    }
                }
                if ($flag_cd) {
                    $coll_original->product_client_details = 'I campi per preselezionare l\'offerta di prodotto sono obbligatori.';
                }

                return (object) $coll_original;
            },
        ]);
    }
}
