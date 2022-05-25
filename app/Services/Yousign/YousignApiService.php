<?php

namespace App\Services\Yousign;

use App\Models\Signature;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class YousignApiService
{
    protected $cart;

    function __construct($cart)
    {
        if (empty($cart)) {
            throw new \Exception("Missing required cart parameter for Yousign precedure");
        }
        $this->cart = $cart;
    }

    public function startYousignClientAndLaunchProcedure()
    {
        $user = auth()->user();

        // $users = Yousign::getUsers();
        $certificate_folder = 'certificates/user_' . $user->id . '/cart_' . $this->cart->id;
        $storagePath  = Storage::disk('private')->getAdapter()->getPathPrefix();
        // non posso usare lo storage perché la libreria usa get_contents e si spacca
        //$certificate_pdf = Storage::disk('private')->get($certificate_folder . '/certificate.pdf');
        $certificate_pdf = $storagePath . $certificate_folder . '/certificate.pdf';

        // procedure for server stamp
        $client_ss = new YousignApiLaravel();
        $procedure_ss = $client_ss->createProcedure();
        // add files to procedure
        $file_ss = $client_ss->addFile('certificate.pdf', $certificate_pdf, $procedure_ss['id']);

        $serverStampObject = $client_ss->addServerStamp(
            $file_ss['id'],
            'firma_broker_quietanzamento',
            null,
            'Data firma: ' . Carbon::now()->format('d-m-Y'),
            "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"
        );

        if ($serverStampObject['id']) {
            $fileWithServerStamp = $serverStampObject['file'];

            $fileServerStampSigned = $client_ss->fileSigned($fileWithServerStamp);
            Storage::disk('private')->put($certificate_folder . '/certificate_with_server_stamp.pdf', $fileServerStampSigned);

            // procedure for client signature
            $client = new YousignApiLaravel();

            // see all possibilities at https://dev.yousign.com/
            $client->setProcedureKeyValue('name', 'Polizza Medici Colpa Grave');
            $client->setProcedureKeyValue('description', 'Polizza Medici Colpa Grave ' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')');
            // to set default expiration date to the signature // TODO 1 settimana?
            $client->setProcedureKeyValue('expiresAt', Carbon::now()->addWeek(1));

            // add webhooks 
            // you can add different headers
            if (App::environment(['local'])) {
                $client->addWebhook('member.finished', 'https://webhook.site/74125933-f7d0-471d-a1cb-d9ed998dae0c', 'GET', array(
                    //"X-Custom-Header" => $type . '-signature',
                ));
            } else {
                $client->addWebhook('member.finished', route('front.yousign.member_finished', $this->cart), 'GET', array(
                    //"X-Custom-Header" => $type . '-signature',
                ));
            }
            $client->addWebhook('procedure.finished', route('front.yousign.procedure_finished', $this->cart), 'GET', array(
                //"X-Custom-Header" => $type . '-signature',
            ));
            $client->addWebhook('procedure.refused', route('front.yousign.procedure_refused', $this->cart), 'GET', array(
                //"X-Custom-Header" => $type . '-signature',
            ));
            // procedure created but not ready for signature
            $procedure = $client->createProcedure();

            // Allows you to define the content of SMS. {{code}} will be used to define the security code managed by Yousign.
            // up to 150 characters
            $client->addSmsContent('GOLINUCCI SRL - FIRMA DIGITALE - Ecco il tuo codice di sicurezza per firmare il documento: {{code}}.');

            // add files to procedure
            $file = $client->addFile('certificate_with_server_stamp.pdf', $storagePath . $certificate_folder . '/certificate_with_server_stamp.pdf', $procedure['id']);

            // add member to sign the documents
            if (App::environment(['local', 'staging'])) {
                $member_signer = $client->addMember($user->clientDetails->first_name, $user->clientDetails->last_name, 'masiorama@gmail.com', '+393299858148', $procedure['id'], 'signer', 1);
            } else {
                $member_signer = $client->addMember($user->clientDetails->first_name, $user->clientDetails->last_name, $user->email, $user->clientDetails->phone, $procedure['id'], 'signer', 1);
            }

            $reason_signer = "Signed by " . $user->firstname . " " . $user->lastname . " (Yousign)";

            // to determine position see https://placeit.yousign.fr/
            $field_name_signer = 'firma_cliente_contratto';
            $fileObject_signer = $client->addFileObjectViaFieldName($file['id'], $member_signer['id'], $reason_signer, $field_name_signer, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
            $field_name_signer = 'firma_cliente_precontrattuale';
            $fileObject_signer = $client->addFileObjectViaFieldName($file['id'], $member_signer['id'], $reason_signer, $field_name_signer, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
            $field_name_signer = 'firma_cliente_privacy';
            $fileObject_signer = $client->addFileObjectViaFieldName($file['id'], $member_signer['id'], $reason_signer, $field_name_signer, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

            Signature::create([
                'certificate_id' => $this->cart->certificate->id,
                'procedure_id' => $procedure['id'],
                'member_signer_id' => $fileObject_signer['member']['id'],
                'status' => $procedure['status'],
            ]);

            // start the signature process
            $client->launchProcedure($procedure['id']);

            return $fileObject_signer['member']['id'];
        }
    }
}
