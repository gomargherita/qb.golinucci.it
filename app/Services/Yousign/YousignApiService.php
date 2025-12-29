<?php

namespace App\Services\Yousign;

use App\Models\Signature;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

/**
 * https://dev.yousign.com/
 * statuses signature:
 * 1. init
 * 2. server_stamp_ok/server_stamp_error
 * 3. member_finished
 * 4. procedure_finished/procedure_refused
 */
class YousignApiService
{
    protected $cart;
    protected $user;
    protected $certificate_folder;
    protected $storage_path;

    function __construct($cart, $user=null)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for Yousign precedure");
        }
        $this->cart = $cart;
        //error_log("UTENTE".$user);
        if($user == null){
            $this->user = auth()->user();
            //dd("FRONTEND".$this->user);
        }
        else{
            $this->user = $user;
            //dd("BACKEND".$this->user);
        }
        $this->certificate_folder = 'certificates/user_' . $this->user->id . '/cart_' . $this->cart->id;
        $this->storage_path = Storage::disk('private')->getAdapter()->getPathPrefix();
    }

    public function startYousignClientAndLaunchProcedure()
    {

        // inizializzo un record di firma
        $signature = Signature::create([
            'certificate_id' => $this->cart->certificate->id,
            'status' => 'init',
        ]);

        $certificate_pdf = $this->storage_path . $this->certificate_folder . '/certificate.pdf';
        $client = new YousignApiLaravel();

        $expiration_date = Carbon::now()->addMonth(6)->format('Y-m-d');
        $procedure_name = 'Polizza Medici Colpa Grave ' . $this->user->clientDetails->first_name . ' ' . $this->user->clientDetails->last_name;
        $procedure_id = $client->createProcedure($procedure_name, $expiration_date);



        if (App::environment(['local', 'staging'])) {
            $configYousignStaging = config('yousign.staging');
            $email = $configYousignStaging['email'];
            $cellphone = $configYousignStaging['phone'];
            $member_signer = $client->addMember($this->user->clientDetails->first_name, $this->user->clientDetails->last_name, $email, $cellphone, $procedure_id, 'signer', 1);
        } else {
            if(str_starts_with($this->user->clientDetails->phone, "+39")){
                $member_signer = $client->addMember($this->user->clientDetails->first_name, $this->user->clientDetails->last_name, $this->user->email, $this->user->clientDetails->phone, $procedure_id, 'signer', 1);
            }
            else{
                $member_signer = $client->addMember($this->user->clientDetails->first_name, $this->user->clientDetails->last_name, $this->user->email, "+39".$this->user->clientDetails->phone, $procedure_id, 'signer', 1);
            }
        }

        $response = $client->addFile('certificate-yousign.pdf', $certificate_pdf, $procedure_id);
        $rtab = json_decode($response,true);
        //dd(json_decode($response,true));
        $file = $rtab['id'];

        $file = $client->addServerStamp(5, "333,113,415,141", $file, $procedure_id);

        if ($file == false){
            if ($this->cart) {
                $signature = $this->cart->signature;
                $signature->status = 'procedure_error';
                $signature->save();
                activity()
                    ->on($signature)
                    ->event('signature_procedure_error')
                    ->log("Yousign procedure error for cart $this->cart->cart_ref - certificate {$this->cart->payment->payment_no};");
            }
            return;
        }

        /*****$fileWithServerStamp = $serverStampObject['file'];
        $client_ss_filesigned = new YousignApiLaravel();
        $fileServerStampSigned = $client_ss_filesigned->fileSigned($fileWithServerStamp);
        Storage::disk('private')->put($this->certificate_folder . '/certificate-yousign.pdf', $fileServerStampSigned);

        // procedure for client signature
        $client = new YousignApiLaravel(); *****/

        // add member to sign the documents
        

            
        $client->setProcedureId($procedure_id);
        $reason_signer = "Signed by " . $this->user->clientDetails->firstname . " " . $this->user->clientDetails->lastname . " (Yousign)";

            // to determine position see https://placeit.yousign.fr/
            switch($this->cart->productOffer->contract->contract_filepath){
                case "/storage/contracts/WORDING_BINDER_NL_2025_SECTION_A_€_500.000.pdf":
                    $position = "378,553,511,600";
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], $position, $reason_signer, 17, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "30,400,129,440", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "481,601,580,640", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "482,367,581,406", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "484,299,583,338", $reason_signer, 23, 'signature', true, null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "301,560,448,594", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "362,230,467,256", $reason_signer, 27, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2025_SECTION_B_€_500.000.pdf":
                    $position = "378,553,511,600";
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], $position, $reason_signer, 17, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "30,400,129,440", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "481,601,580,640", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "482,367,581,406", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "484,299,583,338", $reason_signer, 23, 'signature', true, null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "301,560,448,594", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "362,230,467,256", $reason_signer, 27, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2025_SECTION_A_€_1.000.000.pdf":
                    $position = "378,553,511,600";
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], $position, $reason_signer, 17, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "30,400,129,440", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "481,601,580,640", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "482,367,581,406", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "484,299,583,338", $reason_signer, 23, 'signature', true, null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "301,560,448,594", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "362,230,467,256", $reason_signer, 27, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2025_SECTION_B_€_1.000.000.pdf":
                    $position = "378,553,511,600";
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], $position, $reason_signer, 17, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "30,400,129,440", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "481,601,580,640", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "482,367,581,406", $reason_signer, 23, 'signature', true, null, null);
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "484,299,583,338", $reason_signer, 23, 'signature', true, null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "301,560,448,594", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "362,230,467,256", $reason_signer, 27, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_A_PREV2022.pdf":
                    $position = "376,194,512,232";
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 21, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 23, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                break;
            case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_B_PREV2022.pdf":
                $position = "375,199,511,238";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 21, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 23, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                break;
            case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_A.pdf":
                $position = "374,108,512,148";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 21, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 23, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                break;
            case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_B.pdf":
                $position = "376,194,513,234";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 21, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 23, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                break;
            case "/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_1.000.000.pdf":
                $position = "369,80,523,118";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 22, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 24, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 26, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "49,393,148,433", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                break;
            case "/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_1.000.000.pdf":
                $position = "367,110,525,150";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 22, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 24, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 26, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "49,393,148,433", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
            case "/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_A_500.000.pdf":
                $position = "367,74,525,120";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 22, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 24, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 26, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "49,393,148,433", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
            case "/storage/contracts/AGGIORNATO_WORDING_BINDER_NL_2024_SECTION_B_500.000.pdf":
                $position = "370,112,518,150";
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "481,601,580,640", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "482,367,581,406", $reason_signer, 22, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "484,299,583,338", $reason_signer, 22, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "301,560,448,594", $reason_signer, 24, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "362,230,467,256", $reason_signer, 26, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "49,393,148,433", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                //$fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                    break;
            case "/storage/contracts/WORDING_GIOVANE_MEDICO_2024.pdf":
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "287,237,460,265", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "260,303,426,333", $reason_signer, 18, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "261,283,427,313", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "55,464,221,494", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "480,593,579,632", $reason_signer, 24, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "480,361,579,400", $reason_signer, 24, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "477,296,576,335", $reason_signer, 24, 'signature', true, null, null);


                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "276,553,481,595", $reason_signer, 26, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "366,231,461,257", $reason_signer, 28, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    break;
            case "/storage/contracts/AGGIORNATO_WORDING_GIOVANE_MEDICO_2024.pdf":
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "287,237,460,265", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "260,303,426,333", $reason_signer, 18, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                
                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "261,283,427,313", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file, $member_signer, "55,464,221,494", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "55,464,221,494", $reason_signer, 21, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "480,593,579,632", $reason_signer, 23, 'signature', true, null, null);

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "480,361,579,400", $reason_signer, 23, 'signature', true, null, null);

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "477,296,576,335", $reason_signer, 23, 'signature', true, null, null);


                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "276,553,481,595", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "366,231,461,257", $reason_signer, 27, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                        break;
                case "/storage/contracts/WORDING_GIOVANE_MEDICO_2025.pdf":
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "287,237,460,265", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "260,303,426,333", $reason_signer, 18, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                    
                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "261,283,427,313", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "55,464,221,494", $reason_signer, 21, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "480,593,579,632", $reason_signer, 23, 'signature', true, null, null);

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "480,361,579,400", $reason_signer, 23, 'signature', true, null, null);

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "477,296,576,335", $reason_signer, 23, 'signature', true, null, null);


                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "276,553,481,595", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                    $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "366,231,461,257", $reason_signer, 27, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                        break;

        }

        $signature->procedure_id = $procedure_id;
        $signature->file_id = $file;
        $signature->member_signer_id = $member_signer;
        $signature->save();

        // start the signature process
        $response = $client->launchProcedure($procedure_id);
        $response = json_decode($response, true);
        $signature->signature_link = $response['signers'][0]['signature_link'];
        $signature->save();
        
        return $response['signers'][0]['signature_link'];
        
    }
        
}
