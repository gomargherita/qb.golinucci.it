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

        $serverStampObject = $this->generateServerStamp();

        // rimango in attesa finché non arriva il ping dal server di yousign e non aggiorno lo status
        $maxLoop = 5;
        while (Signature::find($signature->id)->status == 'init' && $maxLoop != 0) {
            sleep(4);
            $maxLoop--;
        }

        // se dopo massimo 20 ancora non è arrivato il ping dal webhook ...
        if (Signature::find($signature->id)->status == 'init') {
            activity()
                ->on($signature)
                ->event('server_stamp_error_after_loop')
                ->log("Yousign server stamp generation error loop exausted for cart {$this->cart->cart_ref} - certificate {$this->cart->payment->payment_no};");
            throw new \Exception("Issue with Yousign server stamp response");
        }

        if ($serverStampObject['id']) {
            $fileWithServerStamp = $serverStampObject['file'];
            $client_ss_filesigned = new YousignApiLaravel();
            $fileServerStampSigned = $client_ss_filesigned->fileSigned($fileWithServerStamp);
            Storage::disk('private')->put($this->certificate_folder . '/certificate-yousign.pdf', $fileServerStampSigned);

            // procedure for client signature
            $client = new YousignApiLaravel();

            // see all possibilities at https://dev.yousign.com/
            $client->setProcedureKeyValue('name', 'Polizza Medici Colpa Grave ' . $this->user->clientDetails->first_name . ' ' . $this->user->clientDetails->last_name . ' (' . $this->user->email . ')');
            $client->setProcedureKeyValue('description', 'Polizza Medici Colpa Grave ' . $this->user->clientDetails->first_name . ' ' . $this->user->clientDetails->last_name . ' (' . $this->user->email . ')');
            // to set default expiration date to the signature // TODO 1 settimana? 1 Mese? // Richiesta Francesco  di mettere 6 mesi
            $client->setProcedureKeyValue('expiresAt', Carbon::now()->addMonth(6));

            // add webhooks 
            // if (App::environment(['local'])) {
            //     $client->addWebhook('member.finished', 'https://webhook.site/74125933-f7d0-471d-a1cb-d9ed998dae0c', 'GET', [
            //         //"X-Custom-Header" => $type . '-signature',
            //     ]);
            //     $client->addWebhook('procedure.finished', 'https://webhook.site/74125933-f7d0-471d-a1cb-d9ed998dae0c', 'GET', [
            //         //"X-Custom-Header" => $type . '-signature',
            //     ]);
            //     $client->addWebhook('procedure.refused', 'https://webhook.site/74125933-f7d0-471d-a1cb-d9ed998dae0c', 'GET', [
            //         //"X-Custom-Header" => $type . '-signature',
            //     ]);
            // } else {
            // $client->addWebhook('member.finished', route('front.yousign.member_finished', $this->cart), 'GET', [
            //     "X-Cart-Header" => $this->cart->cart_ref,
            // ]);
            $client->addWebhook('procedure.finished', route('front.yousign.procedure_finished', $this->cart), 'GET', [
                "X-Cart-Header" => $this->cart->cart_ref,
            ]);
            $client->addWebhook('procedure.refused', route('front.yousign.procedure_refused', $this->cart), 'GET', [
                "X-Cart-Header" => $this->cart->cart_ref,
            ]);
            // }

            // procedure created but not ready for signature
            $procedure = $client->createProcedure();

            // Allows you to define the content of SMS. {{code}} will be used to define the security code managed by Yousign.
            // up to 150 characters
            $client->addSmsContent('GOLINUCCI SRL - FIRMA DIGITALE - Ecco il tuo codice di sicurezza per firmare il documento: {{code}}.');

            // add files to procedure
            $file = $client->addFile('certificate-final.pdf', $this->storage_path . $this->certificate_folder . '/certificate-yousign.pdf', $procedure['id']);

            // add member to sign the documents
            if (App::environment(['local', 'staging'])) {
                $configYousignStaging = config('yousign.staging');
                $email = $configYousignStaging['email'];
                $cellphone = $configYousignStaging['phone'];
                $member_signer = $client->addMember($this->user->clientDetails->first_name, $this->user->clientDetails->last_name, $email, $cellphone, $procedure['id'], 'signer', 1);
            } else {
                if(str_starts_with($this->user->clientDetails->phone, "+39")){
                    $member_signer = $client->addMember($this->user->clientDetails->first_name, $this->user->clientDetails->last_name, $this->user->email, $this->user->clientDetails->phone, $procedure['id'], 'signer', 1);
                }
                else{
                    $member_signer = $client->addMember($this->user->clientDetails->first_name, $this->user->clientDetails->last_name, $this->user->email, "+39".$this->user->clientDetails->phone, $procedure['id'], 'signer', 1);
                }
            }

                

            $reason_signer = "Signed by " . $this->user->clientDetails->firstname . " " . $this->user->clientDetails->lastname . " (Yousign)";

            // to determine position see https://placeit.yousign.fr/
            switch($this->cart->productOffer->contract->contract_filepath){
                case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_A_PREV2022.pdf":
                    $position = "376,194,512,232";
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_B_PREV2022.pdf":
                    $position = "375,199,511,238";
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_A.pdf":
                    $position = "374,108,512,148";
                    break;
                case "/storage/contracts/WORDING_BINDER_NL_2023_SECTION_B.pdf":
                    $position = "376,194,513,234";
                    break;
                        
            }

            if($this->cart->product->id == 1){
                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], $position, $reason_signer, 16, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "312,232,438,262", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,235,166,261", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
                //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "60,113,165,139", $reason_signer, 5, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "481,601,580,640", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "482,367,581,406", $reason_signer, 21, 'signature', true, null, null);
                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "484,299,583,338", $reason_signer, 21, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "301,560,448,594", $reason_signer, 23, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "61,560,166,586", $reason_signer, 22, 'text', true, Carbon::now()->format('d-m-Y'), null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "362,230,467,256", $reason_signer, 25, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                //$fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "105,236,203,252", $reason_signer, 24, 'text', true, Carbon::now()->format('d-m-Y'), null, null);
            }
            else if ($this->cart->product->id == 5){
                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "287,237,460,265", $reason_signer, 5, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "260,303,426,333", $reason_signer, 18, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
                
                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "261,283,427,313", $reason_signer, 19, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "55,464,221,494", $reason_signer, 20, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "480,593,579,632", $reason_signer, 24, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "480,361,579,400", $reason_signer, 24, 'signature', true, null, null);

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "477,296,576,335", $reason_signer, 24, 'signature', true, null, null);


                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "276,553,481,595", $reason_signer, 26, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

                $fileObject_signer = $client->addFileObjectViaPosition($file['id'], $member_signer['id'], "366,231,461,257", $reason_signer, 28, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
            }

            $signature->procedure_id = $procedure['id'];
            $signature->file_id = $file['id'];
            $signature->member_signer_id = $member_signer['id'];
            $signature->save();

            // start the signature process
            $client->launchProcedure($procedure['id']);

            return $fileObject_signer['member']['id'];
        }
    }

    private function generateServerStamp()
    {
        //$this->user = auth()->user();

        $certificate_pdf = $this->storage_path . $this->certificate_folder . '/certificate.pdf';
        $client_ss = new YousignApiLaravel();
        $procedure_ss = $client_ss->createProcedure();
        $file_ss = $client_ss->addFile('certificate.pdf', $certificate_pdf, $procedure_ss['id']);

        // if (App::environment(['local'])) {
        //     $client_ss->addWebhook('server_stamp.finished', 'https://webhook.site/74125933-f7d0-471d-a1cb-d9ed998dae0c', 'GET', [
        //         "X-Cart-Header" => $this->cart->cart_ref,
        //     ]);
        //     $client_ss->addWebhook('server_stamp.error', 'https://webhook.site/74125933-f7d0-471d-a1cb-d9ed998dae0c', 'GET', [
        //         "X-Cart-Header" => $this->cart->cart_ref,
        //     ]);
        // } else {
        $client_ss->addWebhook('server_stamp.finished', route('front.yousign.server_stamps_finished'), 'GET', [
            "X-Cart-Header" => $this->cart->cart_ref,
        ]);
        $client_ss->addWebhook('server_stamp.error', route('front.yousign.server_stamps_error'), 'GET', [
            "X-Cart-Header" => $this->cart->cart_ref,
        ]);
        // }

        $server_stamp_sign_file = "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tGqwXDenS0EEx8QecmjINUlDLpVFE84YE03NoLK6H7nGSEWDJkXwMhebjjC0/XxwCm/L4nF3a4qKWSBlfMfjComz1amiq5Yz0aETwKD7YPbxOl7BQvIMLXAmlJZZomdXhzsgSRqEnXlHjDQA0CsGCfE1B90+cGeEmFHe1w/REvktcJDQ8HH7Sq0xEqUk5mcigUPngUcaTmZwC4QSPTD6nsSRyZI6a0Txa/dz4dGqTQSYkDd1g5RPBpQ3Q+jFGGhLNNu0/wC1gJd+Ixr+KhD47VRxhBNHLF4ZLL2fRkLiYijHyVVJFk3yxKoEI0Hi3CvH5S+UtOBo6xm9J1RKBO24+MPPZ0MQtu84h0Xu3LBKEyNODqWP0fnBp29yWGhBRgxgZK2ESfjkY7wrxOUFljpthyUzSRBFG2fht9Y1y7UkI3QRxbywcT2QzJJqK195jXHoFJE+IWivGI2tkwlkJJT2+WLZfaCen8FxeewgF9geyFbvnIKWC24TM4BQrvgNxIP2PnH4tZRz0fyWVzQYPRAjyDXOQoqQCkibMQ90mUrf2wABo0cNHywqaFT7F/Obnj6RtCBI6bwi7mPH6IGTnebfWHvI+iThxVnl/GR5yPP6YE5HEVPXZAwVr8ZGFaM2r0LbW/MnRcQZSQ6qH0kXE5C9/SPOR9EnI1419EMj6IwjtnCHOMEQ7SYHSnOPMfSBm/cZ74R5yMrF/fPdMI+usjI/2WsH/lJx4bXBjDphZJz+yDvnGTfHI/imYPb1hg1Fbr+aTh+6dSXaKV8BnJlDizSWc9EXvPQqB2by6agdHxZPbk2fummQwjA6BDWiLL3hhcAQAQGJ0BFoofsqu3jBpp4oUe/3VStfLWTaK+67D5PyyZd15nglHSwOfvAkgmihb6lntL4wgzX7qo9qzgCywFPZAccGAovWJCgD8XLVRhf92aUq8NxxGFP7H//aAAwDAQACAAMAAAAQ8kYg08840U8880o04gc0888888888s0YQU4wIsk0sMoUUMgAUw48008w48wgwwcQc0IQUwU8I0AYQM8ogkoIUYMMEo4A0EkUE8gog8ssUU0oIk4YAwkM8888888888woA08888888888888888888888888AQo88888888888888888888888888EAc8888888888888888//8QAFBEBAAAAAAAAAAAAAAAAAAAA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";

        return $client_ss->addServerStamp(
            $file_ss['id'],
            'firma_broker_quietanzamento',
            null,
            'Data firma: ' . Carbon::now()->format('d-m-Y'),
            $server_stamp_sign_file
        );
    }
}
