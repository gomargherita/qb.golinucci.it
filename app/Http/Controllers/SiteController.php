<?php

namespace App\Http\Controllers;
use App\Notifications\EhealthEmail;

use App\Models\Account;
use App\Models\Product;
use App\Models\Cart;
use App\Models\EhealthBuyer;
use App\Models\ImportoLiberoBuyer;
use App\Models\EhealthCode;
use Doctrine\DBAL\ArrayParameterType;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Mail;




class SiteController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        // TODO rendere dinamica questa sezione: per ora estraggo solo i prodotti di golinucci
        $account = Account::with('products');
        $products = $account->find(1)->products
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'description' => $product->description,
                'hex_color' => $product->hex_color,
            ]);

        return Inertia::render('Sites/Home', [
            'products' => $products
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function aboutUs()
    {
        return Inertia::render('Sites/About-us');
    }

    public function userAccount()
    {
        $certificates = array();
        $documents = array();
        $isExpiring = array();
        $user = auth()->user();
        $isRenewed = array();
        $noProds = false;

        $carts = Cart::join('products', 'carts.product_id', '=', 'products.id')->where('user_id', $user->id)->where('carts.product_step_id', 7)->select(DB::raw('carts.user_id, carts.id, carts.indemnity_limit, carts.indemnity_limit_patrimonial, products.name as nome, carts.product_id, carts.cart_ref'))->get();
        foreach($carts as $cart){
            $certificates[$cart->id] = Certificate::join('carts', 'carts.id', '=', 'certificates.cart_id')->where('cart_id', $cart->id)->where('product_step_id', '7')->select(DB::raw('certificates.doprint_certificate_no, DATE_FORMAT(certificates.start_validity_date, "%d-%m-%Y") as start, DATE_FORMAT(certificates.end_validity_date, "%d-%m-%Y") as end, DATE_FORMAT(certificates.retroactivity, "%d-%m-%Y") as retroactivity2'))->get();
            $certificate_folder = 'certificates/user_' . $cart->user_id . '/cart_' . $cart->id;
            $documents[$cart->id]['certificate_final'] = Storage::disk('private')->exists($certificate_folder . '/' . 'certificate-final.pdf') ? route('privateFilesUser', $certificate_folder . '/' . 'certificate-final.pdf') : null;
            $documents[$cart->id]['declarations'] = Storage::disk('private')->exists($certificate_folder . '/' . 'declarations.pdf') ? route('privateFilesUser', $certificate_folder . '/' . 'declarations.pdf') : null;
            $documents[$cart->id]['payment_certificate'] = Storage::disk('private')->exists($certificate_folder . '/' . 'payment-certificate.pdf') ? route('privateFilesUser', $certificate_folder . '/' . 'payment-certificate.pdf') : null;

            $end = date_create($certificates[$cart->id][0]->end);
            $today = date_create(date('d-m-Y'));
            $interval = date_diff($end,$today);
            $interval = $interval->format("%R%a");
            
            $isExpiring[$cart->id] = $interval;
            $new_cart_ref = DB::select(DB::raw('SELECT new_cart_ref FROM renewals WHERE old_cart_ref = "'.$cart->cart_ref.'";'));
            if(!empty($new_cart_ref)){
                $current_step = DB::select(DB::raw('SELECT product_step_id FROM carts WHERE cart_ref = "'.$new_cart_ref[0]->new_cart_ref.'";'));
                if($current_step[0]->product_step_id == 7){
                    $isRenewed[$cart->id] = true;
                }
                else{
                    $isRenewed[$cart->id] = false;
                }
            }
            else{
                $isRenewed[$cart->id] = false;
            }
        }
        if(count($carts) == 0){
            $noProds = true;
        };

       

        return Inertia::render('Sites/User-account', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                //'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'role' => $user->getRoleNames()->first(),
                'client_details' => $user->clientDetails ? $user->clientDetails : null,
                'carts' => $carts,
                'certificates' => $certificates,
                'documents' => $documents,
                'noProds' => $noProds,
                'isExpiring' => $isExpiring,
                'isRenewed' => $isRenewed,
            ]
        ]);
    }

    public function products()
    {
        // TODO rendere dinamica questa sezione: per ora estraggo solo i prodotti di golinucci
        $account = Account::with('products');
        $products = $account->find(1)->products
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'description' => $product->description,
                'hex_color' => $product->hex_color,
            ]);

        return Inertia::render('Sites/Products', [
            'products' => $products
        ]);
    }

    public function product(Product $product)
    {
        $product = $product->load([
            'productOffers' => function ($query) {
                $flag_prev_2022 = 0;
                $query->with(['conditionalFields', 'contract'])->where('flag_prev_2022', $flag_prev_2022);
            }
        ]);

        return Inertia::render('Sites/Product', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => Str::slug($product->productType->name, '-'),
                'detailed_description' => $product->detailed_description,
                'conditions' => $product->conditions,
                'conditional_fields' => $product->conditionalFields,
                'hex_color' => $product->hex_color,
                'offers' => $product->productOffers,
                'table_elements' => json_decode($product->productOfferTable, true),
            ],
        ]);
    }

    public function benefitEVirtualhospital($id = null){
        return $this->importo_libero($id, "benefitEVirtualhospital");
    }

    public function benefit($id = null){
        return $this->importo_libero($id, "benefit");
    }

    public function importo_libero($id = null, $page = 'importo_liberoStep1'){
        if($id != null){
            $buyer = ImportoLiberoBuyer::find(intval($id));
            if($buyer != null && $buyer->paid == true){
                return Inertia::render('Sites/importo_libero_completo');
            }
        }

        if(($id == null)){
            return $this->importo_liberoStep1($page);
        }
        else{
            //dd(EhealthBuyer::find(intval($id)));
            $buyer = ImportoLiberoBuyer::find(intval($id));
            if($buyer == null){
                return $this->importo_liberoStep1($page);
            }
            //step 2
            $paypalClientId = config('paypal.' . config('paypal.mode') . '.client_id');

            return Inertia::render('Sites/importo_liberoStep2', ['paypal_client_id' => $paypalClientId, 'id' => $id, 'total_payable' => $buyer->total_payable,], );
        }
    }
    public function importo_liberoStep1($page){
        return Inertia::render('Sites/'.$page);
    }
    public function importo_libero_store_step1(Request $request){
        $email = $request->input('email');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $importo = $request->input('total_payable');
        $causale = $request->input('causale');

        $buyer = ImportoLiberoBuyer::create(['name' => $name,
                            'surname' => $surname,
                            'email' => $email,
                            'causale' => $causale,
                            'total_payable' => $importo]);
        
        return redirect()->route('front.importoLibero', ['id' => $buyer->id]);
    }

    public function importoLiberoPay($id){
        $buyer = ImportoLiberoBuyer::find(intval($id));

        if($buyer->paid == 1){
            return response()->json([
                'reload' => 'reload']);
        }


        $order = $this->create_order_paypal($buyer->total_payable, $id);
      
        Log::info($buyer);
        $buyer->transaction_ref = $order['id'];
        $buyer->save();

            //exec("explorer.exe");
            return response()->json([
                'order' => $order,
                //'payment_id' => 1,
            ],200);
    }

    public function importoLiberoCheckPayment($id){
        $buyer = ImportoLiberoBuyer::find(intval($id));
        
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($buyer->transaction_ref);
Log::info($response);
        if(isset($response['purchase_units'][0]['payments']['captures'][0]['status']) && $response['purchase_units'][0]['payments']['captures'][0]['status'] == 'COMPLETED'){
            $buyer->paid = true;
            $buyer->save();
            return response()->json([
                'info_paypal' => 'SUCCESS'
            ], 200);
        }
        else{
            return response()->json([
                'info_paypal' => 'FAILED',
            ], 200);
        }
    }

    public function ehealth($id = null){
        if($id != null){
            $buyer = EhealthBuyer::find(intval($id));
            if($buyer != null && $buyer->paid == true){
                return Inertia::render('Sites/eHealthCompleto');
            }
        }

        $code = EhealthCode::where('used', false)->first();
        if($code == null){
            return Inertia::render('Sites/eHealthNoCodici');
        }

        if(($id == null)){
            return $this->ehealth_Step1();
        }
        else{
            //dd(EhealthBuyer::find(intval($id)));
            $buyer = EhealthBuyer::find(intval($id));
            if($buyer == null){
                return $this->ehealth_Step1();
            }
            //step 2
            $paypalClientId = config('paypal.' . config('paypal.mode') . '.client_id');

            return Inertia::render('Sites/eHealthStep2', ['paypal_client_id' => $paypalClientId, 'id' => $id], );
        }
    }

    public function ehealth_Step1(){
        return Inertia::render('Sites/eHealthStep1');
    }

    public function ehealth_store_step1(Request $request){
        $email = $request->input('email');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $codice_fiscale = $request->input('codice_fiscale');
        $indirizzo_residenza = $request->input('indirizzo_residenza');
        $citta_residenza = $request->input('citta_residenza');



        $buyer = EhealthBuyer::create(['name' => $name,
                            'surname' => $surname,
                            'email' => $email,
                            'codice_fiscale' => $codice_fiscale,
                            'indirizzo_residenza' => $indirizzo_residenza,
                            'citta_residenza' => $citta_residenza]);
        
        return redirect()->route('front.ehealth', ['id' => $buyer->id]);
    }

    public function ehealthPay($id){
        $buyer = EhealthBuyer::find(intval($id));

        if($buyer->paid == 1){
            return response()->json([
                'reload' => 'reload']);
        }


        $order = $this->create_order_paypal('90.00', $id);
      
        Log::info($buyer);
        $buyer->transaction_ref = $order['id'];
        $buyer->save();

            //exec("explorer.exe");
            return response()->json([
                'order' => $order,
                //'payment_id' => 1,
            ],200);
    }


    private function create_order_paypal($cifra, $id)
    {
        $provider = new PayPalClient;

        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Prepare Order
        $order = $provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => 'transaction_id_pagamento_esterno_' . $id,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => $cifra
                ]
            ]]
            /*,
            'application_context' => [
                'cancel_url' => route('front.product.store_step4_paypal_cancel', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment->id
                ]),
                'return_url' => route('front.product.store_step4_paypal_return', [
                    'product' => $product,
                    'cart' => $cart,
                    'payment_id' => $payment->id
                ])
            ]*/
        ]);

        //sleep(10);
        

        // Store Token so we can retrieve after PayPal sends them back to us
        //$payment->transaction_ref = $order['id'];
        //$payment->save();
        $paypal_redirect_url = $order['links'][1]['href'];

        return $order;
    }

    public function ehealthSendEmail($id){
        $buyer = EhealthBuyer::find(intval($id));
        
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($buyer->transaction_ref);
Log::info($response);
        if(isset($response['purchase_units'][0]['payments']['captures'][0]['status']) && $response['purchase_units'][0]['payments']['captures'][0]['status'] == 'COMPLETED'){
            $buyer->paid = true;
            $code = EhealthCode::where('used', false)->first();

            $buyer->ehealth_code = $code->code;
            $code->used = true;

            $buyer->save();
            $code->save();

            Notification::route('mail', [
                $buyer->email => $buyer->name." ".$buyer->surname,
            ])->notify(new EhealthEmail($buyer));
            return response()->json([
                'info_paypal' => 'SUCCESS'
            ], 200);
        }
        else{
            return response()->json([
                'info_paypal' => 'FAILED',
            ], 200);
        }
    }

    function applePayAssociationFile(){
        return Storage::disk('private')->get('domain-association-file-live');
    }
}
