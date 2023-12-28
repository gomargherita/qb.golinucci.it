<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientDetailsRequest;
use App\Http\Requests\UpdateClientDetailsRequest;
use App\Models\ClientDetail;
use App\Models\ProductClientDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class ClientDetailsController extends Controller
{
    public function index()
    {
        return Inertia::render('ClientDetails/Index', [
            'filters' => Request::all('search', 'with_accident', 'trashed'),
            'clientDetails' => Auth::user()->account->clientDetails()
                ->orderByName()
                ->filter(Request::only('search', 'with_accident', 'trashed'))
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
        $product_client_details = $client_detail->ProductClientDetails();
        $client_detail_id = $product_client_details->pluck('client_detail_id');
        $conditional_field_id = $product_client_details->pluck('conditional_field_id');
        $selected_field_value = $product_client_details->pluck('selected_field_value');
        

        $field_value = [];
        if($conditional_field_id[0] > 0){
            for($j=0; $j<$conditional_field_id[0]-1; $j++){
              array_push($field_value, "");
            }
          }
          for($i=0; $i<count($conditional_field_id); $i++){
            array_push($field_value, $selected_field_value[$i]);
          }

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
                'client_detail_id' => $client_detail_id,
                'conditional_field_id' => $conditional_field_id,
                'selected_field_value' => $selected_field_value,
                'field_value' => $field_value,
            ],
            'user' => $client_detail->user ? $client_detail->user : null,
        ]);
    }

    public function update(UpdateClientDetailsRequest $request, ClientDetail $client_detail)
    {
        if(auth()->user()->owner == 1){
            $client_detail->first_name = $request->input('first_name');
            $client_detail->last_name = $request->input('last_name');
            $client_detail->fiscal_code = $request->input('fiscal_code');
            $client_detail->gender = $request->input('gender');
            $client_detail->dob = $request->input('dob');
            $client_detail->phone = $request->input('phone');
            $client_detail->address = $request->input('address');
            $client_detail->city = $request->input('city');
            $client_detail->province = $request->input('province');
            $client_detail->country = $request->input('country');
            $client_detail->postal_code = $request->input('postal_code');
            $client_detail->note = $request->input('note');
            $client_detail->with_accident = $request->input('with_accident');
            $client_detail->flag_prev_2022 = $request->input('flag_prev_2022');
            $client_detail->save();
            //$client_detail->update($request->all());
        }
        else{
            $client_detail->update($request->validated());
        }
            //dd($request);
        for($i=0; $i<count($request['field_value']); $i++){

            $rebuildProductClientDetails = [];

            $rebuildProductClientDetails[$i+1] = ['selected_field_value' => $request['field_value'][$i]];
            $client_detail->productClientDetails()->syncWithoutDetaching($rebuildProductClientDetails);

            /*
            DB::table('product_client_details')
            ->where('client_detail_id', $request['client_detail_id'][$i])
            ->where('conditional_field_id', $request['conditional_field_id'][$i])
            ->update([
                'selected_field_value' => $request['selected_field_value'][$i],
                'updated_at' => Carbon::now(),
            ]);*/
        }

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
