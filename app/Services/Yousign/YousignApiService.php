<?php

namespace App\Services\Yousign;

use Carbon\Carbon;
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
        $storagePath  = Storage::disk('private')->getDriver()->getAdapter()->getPathPrefix();
        // non posso usare lo storage perché la libreria usa get_contents e si spacca
        //$certificate_pdf = Storage::disk('private')->get($certificate_folder . '/certificate.pdf');
        $certificate_pdf = $storagePath . $certificate_folder . '/certificate.pdf';

        $client = new YousignApiLaravel();

        // see all possibilities at https://dev.yousign.com/
        $client->setProcedureKeyValue('name', 'Polizza Medici Colpa Grave');
        $client->setProcedureKeyValue('description', 'Polizza Medici Colpa Grave ' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')');
        // to set default expiration date to the signature
        $client->setProcedureKeyValue('expiresAt', '2022-05-24');

        // add webhooks 
        // you can add different headers
        $webhookUrl = env('YOUSIGN_API_URL') . 'yousign/';
        $client->addWebhook('member.finished', $webhookUrl . 'signature', 'GET', array(
            //"X-Custom-Header" => $type . '-signature',
        ));
        $client->addWebhook('procedure.refused', $webhookUrl . 'refused', 'GET', array(
            //"X-Custom-Header" => $type . '-signature',
        ));
        // procedure created but not ready for signature
        $procedure = $client->createProcedure();

        // Allows you to define the content of SMS. {{code}} will be used to define the security code managed by Yousign.
        // up to 150 characters
        $client->addSmsContent('GOLINUCCI SRL - FIRMA DIGITALE - Ecco il tuo codice di sicurezza per firmare il documento: {{code}}.');

        // add files to procedure
        $file = $client->addFile('certificate.pdf', $certificate_pdf, $procedure['id']);

        // add member to sign the documents
        $member_signer = $client->addMember('Marco', 'Masi', 'masiorama@gmail.com', '+393299858148', $procedure['id'], 'signer', 1);
        $validator_config = config('yousign.validator');
        $member_validator = $client->addMember($validator_config['first_name'], $validator_config['last_name'], $validator_config['email'], $validator_config['phone'], $procedure['id'], 'validator', 2);

        // to determine the last page of your file
        $lastPageNumber = $client->getNumPagesInPDF($certificate_pdf);

        $reason_signer = "Signed by " . $user->firstname . " " . $user->lastname . " (Yousign)";
        $reason_validator = "Signed by " . $validator_config['first_name'] . " " . $validator_config['last_name'] . " (Yousign)";

        // to determine position see https://placeit.yousign.fr/
        $position = '252,545,352,586';
        $fileObject_signer = $client->addFileObject($file['id'], $member_signer['id'], $position, $reason_signer, $lastPageNumber, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));
        $fileObject_validator = $client->addFileObject($file['id'], $member_validator['id'], $position, $reason_validator, $lastPageNumber, 'signature', true, null, null, 'Data firma: ' . Carbon::now()->format('d-m-Y'));

        // fileObject ritorna ciò
        // array(13) { ["mention"]=> NULL ["mention2"]=> NULL ["member"]=> array(17) { ["id"]=> string(45) "/members/b3ee30b9-f643-4a94-8470-cfd85f2030eb" ["user"]=> NULL ["type"]=> string(6) "signer" ["firstname"]=> string(5) "Marco" ["lastname"]=> string(4) "Masi" ["email"]=> string(19) "masiorama@gmail.com" ["phone"]=> string(13) "+393299858148" ["position"]=> int(1) ["createdAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["updatedAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["startedAt"]=> NULL ["finishedAt"]=> NULL ["status"]=> string(7) "pending" ["comment"]=> NULL ["parent"]=> NULL ["contact"]=> NULL ["fields"]=> array(2) { ["company"]=> NULL ["job_title"]=> NULL } } ["fieldName"]=> NULL ["reason"]=> string(21) "Signed by (Yousign)" ["id"]=> string(50) "/file_objects/cfcb2839-1070-4e43-8dc5-239b9def60a9" ["type"]=> string(9) "signature" ["file"]=> string(43) "/files/9894b0b5-2f8b-4cfa-9d17-b4d36e9f0b6f" ["page"]=> int(1) ["position"]=> string(15) "252,545,352,586" ["createdAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["updatedAt"]=> string(25) "2022-05-17T15:52:07+02:00" ["parent"]=> NULL }

        // start the signature process
        $client->launchProcedure($procedure['id']);

        return $fileObject_signer;
    }
}
