<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use SoapClient, SoapHeader, SoapVar, SoapParam, SoapFault, DOMDocument;

/**
 * Methods
 * DoPrint Login
 * $SessionToken=$doprint->HandShake();
 * Setup Policy using the xml and MD5 file hashes
 * $doprint->ValidationRequest($SessionToken,$nextvalue.'-LB',$customer,$start_date,$end_date,$arr,$wording,$prop);
 * Upload the wording
 * $doprint->UploadRequest($SessionToken,$wording);
 * Upload Proposal form
 * $doprint->UploadRequest($SessionToken,$prop);
 * Create the policy
 * $doprint->TransactionRequest($SessionToken);
 * Download the certificate
 * $doprint->DownloadRequest($SessionToken,$filename);
 */
class LloydsDoprintService
{
    protected $apiKey;
    protected $wsdl;
    public $requestorID;
    public $baseXml;

    public function __construct()
    {
        if (App::environment(['local', 'staging'])) {
            // sandbox
            $api_key = config('doprint.sandbox.api_key');
            $wsdl = config('doprint.sandbox.wsdl');
            $requestor_id = config('doprint.sandbox.requestor_id');
        } else {
            // live
            $api_key = config('doprint.sandbox.api_key');
            $wsdl = config('doprint.sandbox.wsdl');
            $requestor_id = config('doprint.sandbox.requestor_id');
        }
        if (empty($wsdl) || empty($requestor_id) || empty($api_key)) {
            throw new \Exception("Al least one parameter is missing");
        } else {
            $this->wsdl = $wsdl;
            $this->apiKey = $api_key;
            $this->requestorID = $requestor_id;
        }
    }

    public function HandShake()
    {

        $apiKey = $this->apiKey;
        $requestorID = $this->requestorID;
        $clientVersion = '';

        $client = new SoapClient($this->wsdl, array("soap_version" => SOAP_1_1, "trace" => true, "exceptions" => true));

        $messageType = "HandShake";

        try {
            $header = array();
            $header[] = new SoapHeader("http://tempuri.org/", "APIKey", $apiKey);
            $header[] = new SoapHeader("http://tempuri.org/", "MessageType", $messageType);
            $header[] = new SoapHeader("http://tempuri.org/", "RequestorID", $requestorID);
            $client->__setSoapHeaders($header);

            $request = new Message($apiKey, $clientVersion, $requestorID);
            $request = new SoapVar($request, SOAP_ENC_OBJECT, "CVPHandshakeRequest", "http://schemas.datacontract.org/2004/07/Euseco.Mokadom.CVP.Workflow.MessageContracts");
            $request = new SoapParam($request, "Message");
            $response = $client->RouteMessage($request);
            $sessionDuration = $response->SessionDuration;
            $sessionToken = $response->SessionToken;
            $pwExpiringDays = $response->PwExpiringDays;

            // $return = "------$messageType OK\n";
            // $return =  "SessionDuration: " . $sessionDuration . "\n";
            // $return =  "pwExpritingDays: " . $pwExpiringDays . "\n";
            // $return =  "------$messageType OK\n";
            return $sessionToken;
        } catch (SoapFault $exception) {
            return "Exception Thrown: " . $exception->faultstring;
        }
    }

    public function ValidationRequest($sessionToken, $contractType, $productName, $certificateNo, $customer_details, $start_date, $end_date, $proposal_date, $cart_details, $wording, $prop, $prop2)
    {
        $apiKey = $this->apiKey;
        $requestorID = $this->requestorID;
        $messageType = "Validation";
        $xml = new DOMDocument('1.0', 'utf-8');

        //$xml->load("../xml/base.xml");
        $xml_base = Storage::disk('private')->get('lloyds_rinnovi_base.xml');
        $xml->loadXML($xml_base);

        // Document Index
        $DocumentIndex = $xml->getElementsByTagName('DocumentIndex')->item(0);
        $DocumentIndex->getElementsByTagName('ContractType')->item(0)->textContent = $contractType;
        $DocumentIndex->getElementsByTagName('CommercialInsuranceProduct')->item(0)->textContent = $productName;

        // BindingAuthorityDetails
        // PROD
        // $BindingAuthorityDetails = $xml->getElementsByTagName('BindingAuthorityDetails')->item(0);
        // $BindingAuthorityDetails->getElementsByTagName('BARefDate')->item(0)->textContent = '2021-11-04';
        // $BindingAuthorityDetails->getElementsByTagName('BARefNo')->item(0)->textContent = '12';
        // $BindingAuthorityDetails->getElementsByTagName('BABrokersRef')->item(0)->textContent = 'CPG129821';
        // $BindingAuthorityDetails->getElementsByTagName('BAPseudonym')->item(0)->textContent = 'GMB';
        // $BindingAuthorityDetails->getElementsByTagName('BANumber')->item(0)->textContent = '1732';
        // $BindingAuthorityDetails->getElementsByTagName('BAcorr_Pin1')->item(0)->textContent = '115647';
        // $BindingAuthorityDetails->getElementsByTagName('BAcorr_Pin2')->item(0)->textContent = 'XKT';
        // $BindingAuthorityDetails->getElementsByTagName('BAUniqueMarketReference')->item(0)->textContent = 'B1732CPG129821';

        // SANDBOX
        $BindingAuthorityDetails = $xml->getElementsByTagName('BindingAuthorityDetails')->item(0);
        $BindingAuthorityDetails->getElementsByTagName('BARefDate')->item(0)->textContent = '2020-10-09';
        $BindingAuthorityDetails->getElementsByTagName('BARefNo')->item(0)->textContent = '4';
        $BindingAuthorityDetails->getElementsByTagName('BABrokersRef')->item(0)->textContent = 'CPG129820';
        $BindingAuthorityDetails->getElementsByTagName('BAPseudonym')->item(0)->textContent = 'GMB';
        $BindingAuthorityDetails->getElementsByTagName('BANumber')->item(0)->textContent = '1732';
        $BindingAuthorityDetails->getElementsByTagName('BAcorr_Pin1')->item(0)->textContent = '115647';
        $BindingAuthorityDetails->getElementsByTagName('BAcorr_Pin2')->item(0)->textContent = 'XKT';
        $BindingAuthorityDetails->getElementsByTagName('BAUniqueMarketReference')->item(0)->textContent = 'B1732CPG129820';

        // Certificate Number
        $DocumentReferences = $xml->getElementsByTagName('DocumentReferences')->item(0);
        $DocumentReferences->getElementsByTagName('CertificateICNumber')->item(0)->textContent = $certificateNo;

        // Set Policy Holder
        switch ($customer_details['profession']) {
            case '1':
                $profession = "Dirigente Medico";
                break;
            case '2':
                $profession = "Medico Convenzionato";
                break;
            case '3':
                $profession = "Medico Specialista in Formazione (Specializzando)";
                break;
            case '4':
                $profession = "Personale sanitario";
                break;
            case '5':
                $profession = "Dirigente Sanitario non Medico";
                break;
            case '6':
                $profession = "Dirigente Infermieristico";
                break;
            default:
        }

        $PolicyHolder = $xml->getElementsByTagName('PolicyholderDetails')->item(0);
        $PolicyHolder->getElementsByTagName('LastName')->item(0)->textContent = $customer_details['last_name'];
        $PolicyHolder->getElementsByTagName('FirstName')->item(0)->textContent = $customer_details['first_name'];
        $PolicyHolder->getElementsByTagName('AddressLine1')->item(0)->textContent = $customer_details['address'];
        $PolicyHolder->getElementsByTagName('City')->item(0)->textContent = $customer_details['city'];
        $PolicyHolder->getElementsByTagName('PostCode')->item(0)->textContent = $customer_details['postal_code'];
        $PolicyHolder->getElementsByTagName('Region')->item(0)->textContent = $customer_details['province'];
        $PolicyHolder->getElementsByTagName('FiscalCode')->item(0)->textContent = $customer_details['fiscal_code'];
        $PolicyHolder->getElementsByTagName('GenderType')->item(0)->textContent = $customer_details['gender'];
        $PolicyHolder->getElementsByTagName('Country')->item(0)->textContent = $customer_details['country'];
        // $PolicyHolder->getElementsByTagName('JobTitle')->item(0)->textContent = 'aaaaaaaa';

        // Set Insured Details
        $InsuredDetails = $xml->getElementsByTagName('InsuredDetails')->item(0);
        $InsuredDetails->getElementsByTagName('InsuredName')->item(0)->textContent = $customer_details['first_name'] . ' ' . $customer_details['last_name'];

        //Start End Date

        $CertificateDateTimeDetails = $xml->getElementsByTagName('CertificateDateTimeDetails')->item(0);
        $CertificateDateTimeDetails->getElementsByTagName('InceptionDate')->item(0)->textContent = $start_date;
        $CertificateDateTimeDetails->getElementsByTagName('ExpiryDate')->item(0)->textContent = $end_date;
        $CertificateDateTimeDetails->getElementsByTagName('ProposalDate')->item(0)->textContent = $proposal_date;

        $RiskPremiumDetails = $xml->getElementsByTagName('RiskPremiumDetails')->item(0);
        $RiskPremiumDetails->getElementsByTagName('Premium')->item(0)->textContent = $cart_details['price'];
        $RiskPremiumDetails->getElementsByTagName('Accessori')->item(0)->textContent = $cart_details['accessori'];
        $RiskPremiumDetails->getElementsByTagName('Tax')->item(0)->textContent =  $cart_details['tax'];
        $RiskPremiumDetails->getElementsByTagName('Gross')->item(0)->textContent =  $cart_details['price_with_tax'];


        $TotalPremiumDetails = $xml->getElementsByTagName('TotalPremiumDetails')->item(0);
        $TotalPremiumDetails->getElementsByTagName('Premium')->item(0)->textContent =  $cart_details['price'];
        $TotalPremiumDetails->getElementsByTagName('Accessori')->item(0)->textContent = $cart_details['accessori'];
        $TotalPremiumDetails->getElementsByTagName('Tax')->item(0)->textContent =  $cart_details['tax'];
        $TotalPremiumDetails->getElementsByTagName('Gross')->item(0)->textContent =  $cart_details['price_with_tax'];


        $Instalments = $xml->getElementsByTagName('Instalments')->item(0);
        $Instalments->getElementsByTagName('Premium')->item(0)->textContent =  $cart_details['price'];
        $Instalments->getElementsByTagName('Accessori')->item(0)->textContent = $cart_details['accessori'];
        $Instalments->getElementsByTagName('Tax')->item(0)->textContent = $cart_details['tax'];
        $Instalments->getElementsByTagName('Gross')->item(0)->textContent =  $cart_details['price_with_tax'];
        $Instalments->getElementsByTagName('InstalmentDate')->item(0)->textContent = $proposal_date;

        $PremiumReceipt = $xml->getElementsByTagName('PremiumReceipt')->item(0);
        $PremiumReceipt->getElementsByTagName('FirmReceivingPremium')->item(0)->textContent = "Golinucci Srl";
        $PremiumReceipt->getElementsByTagName('AmountPremiumReceived')->item(0)->textContent = $cart_details['price_with_tax'];
        $PremiumReceipt->getElementsByTagName('DatePremiumReceived')->item(0)->textContent = $proposal_date;

        $pdffile = Storage::disk('private')->get($wording);
        $documentHashpdf = base64_encode(md5($pdffile, true));

        $CertificateAttachments = $xml->getElementsByTagName('CertificateAttachments')->item(0);
        $GeneralConditionsAttachment = $CertificateAttachments->getElementsByTagName('RisksDescriptionAttachment')->item(0);
        $GeneralConditionsAttachment->getElementsByTagName('FileHash')->item(0)->textContent = $documentHashpdf;

        // Add Prop
        $prop = Storage::disk('private')->get($prop);
        $propHashpdf = base64_encode(md5($prop, true));
        $ProposalFormAttachment = $CertificateAttachments->getElementsByTagName('OtherAttachment')->item(0);
        $ProposalFormAttachment->getElementsByTagName('FileHash')->item(0)->textContent = $propHashpdf;

        // add 3rd attach
        $prop2 = Storage::disk('private')->get($prop2);
        $prop2Hashpdf = base64_encode(md5($prop2, true));
        $ProposalFormAttachment = $CertificateAttachments->getElementsByTagName('OtherAttachment')->item(1);
        $ProposalFormAttachment->getElementsByTagName('FileHash')->item(0)->textContent = $prop2Hashpdf;

        $strxml = $xml->saveXML();
        $document = base64_encode($strxml);

        $policy_response_folder = 'certificates/user_' . $customer_details['user_id'] . '/cart_' . $cart_details['cart_id'];
        Storage::disk('private')->put($policy_response_folder . '/policy_response.xml', $strxml);

        $documentHash = base64_encode(md5($strxml, true));
        $sessionToken = $sessionToken;
        $client = new SoapClient($this->wsdl, array("soap_version" => SOAP_1_1, "trace" => true, "exceptions" => true));
        try {
            $header = array();
            $header[] = new SoapHeader("http://tempuri.org/", "APIKey", $apiKey);
            $header[] = new SoapHeader("http://tempuri.org/", "MessageType", $messageType);
            $header[] = new SoapHeader("http://tempuri.org/", "RequestorID", $requestorID);
            $client->__setSoapHeaders($header);
            $request = new Message1($document, $documentHash, $sessionToken);
            $request = new SoapVar($request, SOAP_ENC_OBJECT, "CVPValidationRequest", "http://schemas.datacontract.org/2004/07/Euseco.Mokadom.CVP.Workflow.MessageContracts");
            $request = new SoapParam($request, "Message");
            $response = $client->RouteMessage($request);
            $isValid = $response->IsValid;
            if (isset($response->Errors)) {
                //$fault = $response->Errors->fault->string;
                echo "------$messageType INIZIO\n";
                $fault = $response->Errors->fault->string;
                if (array($fault)) {
                    foreach ($fault as $fau)
                        echo $fau . "\n";
                } else {
                    echo $fault . "\n";
                }
                echo "------$messageType FINE\n";
            } else {
                return $isValid;
            }
        } catch (SoapFault $exception) {
            return "Exception Thrown: " . $exception->faultstring;
        }
    }


    public function UploadRequest($sessionToken, $pdffilename)
    {
        $apiKey = $this->apiKey;
        $requestorID = $this->requestorID;
        $messageType = "Upload";
        $pdffile = file_get_contents($pdffilename);
        $document = $pdffile;
        $documentHash = base64_encode(md5($pdffile, true));
        $sessionToken = $sessionToken;
        $client = new SoapClient($this->wsdl, array("soap_version" => SOAP_1_1, "trace" => true, "exceptions" => true));
        try {
            $header = array();
            $header[] = new SoapHeader("http://tempuri.org/", "APIKey", $apiKey);
            $header[] = new SoapHeader("http://tempuri.org/", "MessageType", $messageType);
            $header[] = new SoapHeader("http://tempuri.org/", "RequestorID", $requestorID);
            $client->__setSoapHeaders($header);
            $request = new Message1($document, $documentHash, $sessionToken);
            $request = new SoapVar($request, SOAP_ENC_OBJECT, "CVPUploadRequest", "http://schemas.datacontract.org/2004/07/Euseco.Mokadom.CVP.Workflow.MessageContracts");
            $request = new SoapParam($request, "Message");
            $response = $client->RouteMessage($request);
            $isValid = $response->IsValid;
            echo $isValid . "\n";
            if (isset($response->Errors)) {
                $fault = $response->Errors->fault->string;
                echo $fault . "\n";
            } else {
                echo "------$messageType INIZIO\n";
                print_r($response);
                echo "------$messageType FINE\n";
            }
        } catch (SoapFault $exception) {
            echo "------$messageType INIZIO\n";
            echo "Exception Thrown: " . $exception->faultstring . "\n";
            echo "------$messageType FINE\n";
            echo $client->__getLastResponse();
        }
    }

    public function TransactionRequest($sessionToken)
    {
        $apiKey = $this->apiKey;
        $messageType = "Transaction";
        $requestorID = $this->requestorID;
        $client = new SoapClient($this->wsdl, array("soap_version" => SOAP_1_1, "trace" => true, "exceptions" => true));
        try {
            $header = array();
            $header[] = new SoapHeader("http://tempuri.org/", "APIKey", $apiKey);
            $header[] = new SoapHeader("http://tempuri.org/", "MessageType", $messageType);
            $header[] = new SoapHeader("http://tempuri.org/", "RequestorID", $requestorID);
            $client->__setSoapHeaders($header);
            $request = new Message2($sessionToken);
            $request = new SoapVar($request, SOAP_ENC_OBJECT, "CVPTransactionRequest", "http://schemas.datacontract.org/2004/07/Euseco.Mokadom.CVP.Workflow.MessageContracts");
            $request = new SoapParam($request, "Message");
            $response = $client->RouteMessage($request);
            $isValid = $response->IsValid;
            echo $isValid . "\n";
            if (isset($response->Errors)) {
                $fault = $response->Errors->fault->string;
                echo $fault . "\n";
            } else {
                echo "------$messageType INIZIO\n";
                print_r($response);
                echo "------$messageType FINE\n";
            }
        } catch (SoapFault $exception) {
            echo "------$messageType INIZIO\n";
            echo "Exception Thrown: " . $exception->faultstring . "\n";
            echo "------$messageType FINE\n";
            //echo $client->__getLastRequest();
            echo $client->__getLastResponse();
        }
    }

    public function DownloadRequest($sessionToken, $docName)
    {
        $apiKey = $this->apiKey;

        $messageType = "Download";
        $requestorID = $this->requestorID;
        $client = new SoapClient($this->wsdl, array("soap_version" => SOAP_1_1, "trace" => true, "exceptions" => true));
        try {
            $header = array();
            $header[] = new SoapHeader("http://tempuri.org/", "APIKey", $apiKey);
            $header[] = new SoapHeader("http://tempuri.org/", "MessageType", $messageType);
            $header[] = new SoapHeader("http://tempuri.org/", "RequestorID", $requestorID);
            $client->__setSoapHeaders($header);
            $request = new Message2($sessionToken);
            $request = new SoapVar($request, SOAP_ENC_OBJECT, "CVPDownloadRequest", "http://schemas.datacontract.org/2004/07/Euseco.Mokadom.CVP.Workflow.MessageContracts");
            $request = new SoapParam($request, "Message");
            $response = $client->RouteMessage($request);
            $isValid = $response->IsValid;
            echo "------$messageType INIZIO\n";
            echo $isValid;
            echo "------$messageType FINE\n";
            file_put_contents($docName,  $response->Document);
        } catch (SoapFault $exception) {
            echo "------$messageType INIZIO\n";
            echo "Exception Thrown: " . $exception->faultstring . "\n";
            //echo $client->__getLastRequest();
            echo "------$messageType FINE\n";
            echo $client->__getLastResponse();
        }
    }
} // End Class

class Message
{
    function __construct($apiKey, $clientVersion, $requestorID)
    {
        $this->APIKey = $apiKey;
        $this->ClientVersion = $clientVersion;
        $this->RequestorID = $requestorID;
    }
}

class Message1
{
    function __construct($document, $documentHash, $sessionToken)
    {
        $this->Document = $document;
        $this->DocumentHash = $documentHash;
        $this->SessionToken = $sessionToken;
    }
}

class Message2
{
    function __construct($sessionToken)
    {
        $this->SessionToken = $sessionToken;
    }
}
