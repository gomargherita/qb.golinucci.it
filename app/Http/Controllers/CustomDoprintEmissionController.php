<?php

namespace App\Http\Controllers;

use App\Models\CustomDoprintEmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomDoprintEmissionController extends Controller
{
    public function index()
    {
        $emissions = CustomDoprintEmission::query()
            ->orderByDesc('id')
            ->get()
            ->map(function (CustomDoprintEmission $emission) {
                return [
                    'id' => $emission->id,
                    'product_name' => $emission->product_name,
                    'holder' => $emission->first_name . ' ' . $emission->last_name,
                    'start_date' => $this->dateLabel($emission->start_date),
                    'end_date' => $this->dateLabel($emission->end_date),
                    'status' => $this->statusLabel($emission->status),
                ];
            });

        return Inertia::render('CustomEmissions/Index', [
            'emissions' => $emissions,
        ]);
    }

    public function create()
    {
        return Inertia::render('CustomEmissions/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'coverholder_ref' => ['required', 'string', 'max:125'],
            'contract_type' => ['required', 'string', 'max:125'],
            'product_name' => ['required', 'string', 'max:125'],
            'policy_text_version' => ['required', 'string', 'max:125'],
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'fiscal_code' => ['required', 'string', 'max:16'],
            'gender' => ['required', 'in:M,F'],
            'dob' => ['nullable', 'date'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:50'],
            'province' => ['required', 'string', 'max:2'],
            'country' => ['required', 'string', 'max:2'],
            'postal_code' => ['required', 'string', 'max:5'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'premium_net' => ['required', 'numeric', 'min:0'],
            'premium_accessories' => ['required', 'numeric', 'min:0'],
            'premium_tax' => ['required', 'numeric', 'min:0'],
            'premium_gross' => ['required', 'numeric', 'min:0'],
            'policy_schedule' => ['required', 'file', 'mimes:pdf', 'max:15360'],
            'wording' => ['required', 'file', 'mimes:pdf', 'max:15360'],
            'attached_clauses' => ['required', 'file', 'mimes:pdf', 'max:15360'],
            'warranty_statement' => ['required', 'file', 'mimes:pdf', 'max:15360'],
        ]);

        $emission = new CustomDoprintEmission();
        $emission->fill(collect($data)->except([
            'policy_schedule',
            'wording',
            'attached_clauses',
            'warranty_statement',
        ])->all());
        $emission->status = 'draft';
        $emission->created_by = Auth::id();
        $emission->fiscal_code = strtoupper($emission->fiscal_code);
        $emission->province = strtoupper($emission->province);
        $emission->country = strtoupper($emission->country);
        $emission->policy_schedule_path = '';
        $emission->wording_path = '';
        $emission->attached_clauses_path = '';
        $emission->warranty_statement_path = '';
        $emission->save();

        $folder = 'custom-emissions/' . $emission->id;
        $files = [
            'policy_schedule' => 'scheda-polizza.pdf',
            'wording' => 'condizioni.pdf',
            'attached_clauses' => 'clausole.pdf',
            'warranty_statement' => 'questionario.pdf',
        ];
        foreach ($files as $field => $filename) {
            $emission->{$field . '_path'} = $request->file($field)->storeAs($folder, $filename, 'private');
        }
        $emission->save();

        return Redirect::route('customEmissions.show', $emission)
            ->with('success', 'Bozza salvata. Invio a DoPrint non eseguito.');
    }

    public function show(CustomDoprintEmission $emission)
    {
        return Inertia::render('CustomEmissions/Show', [
            'emission' => [
                'id' => $emission->id,
                'status' => $this->statusLabel($emission->status),
                'coverholder_ref' => $emission->coverholder_ref,
                'contract_type' => $emission->contract_type,
                'product_name' => $emission->product_name,
                'policy_text_version' => $emission->policy_text_version,
                'first_name' => $emission->first_name,
                'last_name' => $emission->last_name,
                'fiscal_code' => $emission->fiscal_code,
                'gender' => $emission->gender,
                'dob' => $this->dateLabel($emission->dob),
                'phone' => $emission->phone,
                'address' => $emission->address,
                'city' => $emission->city,
                'province' => $emission->province,
                'country' => $emission->country,
                'postal_code' => $emission->postal_code,
                'start_date' => $this->dateLabel($emission->start_date),
                'end_date' => $this->dateLabel($emission->end_date),
                'premium_net' => (float) $emission->premium_net,
                'premium_accessories' => (float) $emission->premium_accessories,
                'premium_tax' => (float) $emission->premium_tax,
                'premium_gross' => (float) $emission->premium_gross,
                'files' => [
                    ['label' => 'Scheda di polizza', 'url' => route('privateFiles', $emission->policy_schedule_path)],
                    ['label' => 'Condizioni', 'url' => route('privateFiles', $emission->wording_path)],
                    ['label' => 'Clausole allegate', 'url' => route('privateFiles', $emission->attached_clauses_path)],
                    ['label' => 'Questionario', 'url' => route('privateFiles', $emission->warranty_statement_path)],
                ],
            ],
        ]);
    }

    private function statusLabel(string $status): string
    {
        if ($status === 'draft') {
            return 'Bozza';
        }

        return $status;
    }

    private function dateLabel($date): ?string
    {
        if (!$date) {
            return null;
        }

        return $date->format('d/m/Y');
    }
}
