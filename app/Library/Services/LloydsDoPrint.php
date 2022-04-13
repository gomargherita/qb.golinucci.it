<?php

namespace App\Library\Services;
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
class LloydsDoPrint
{
    public $apiKey = '********';
    public $wsdl = "https://sandboxdoprint.lloyds.com:2152/ContractValidationEngine.xamlx?wsdl";
    public $requestorID = "*******";

    public function HandShake()
    {

        $apiKey = $this->apiKey;
        $requestorID = $this->requestorID;

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
            // echo $sessionDuration . "\n";
            return $sessionToken;
            //echo $pwExpiringDays . "\n";
        } catch (SoapFault $exception) {
            echo "Exception Thrown: " . $exception->faultstring . "\n";
            echo $client->__getLastResponse();
        }
    }

    public function ValidationRequest($sessionToken, $certificateNo, $customer_details, $start_date, $end_date, $cart_details, $wording, $prop)
    {
        $apiKey = $this->apiKey;
        $requestorID = $this->requestorID;
        $messageType = "Validation";
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->load("golinucci_test.xml");

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
        $PolicyHolder->getElementsByTagName('LastName')->item(0)->textContent = $customer_details['surname'];
        $PolicyHolder->getElementsByTagName('FirstName')->item(0)->textContent = $customer_details['name'];
        $PolicyHolder->getElementsByTagName('AddressLine1')->item(0)->textContent = $customer_details['address_1'];
        $PolicyHolder->getElementsByTagName('City')->item(0)->textContent = $customer_details['city'];
        $PolicyHolder->getElementsByTagName('PostCode')->item(0)->textContent = $customer_details['post_code'];
        $PolicyHolder->getElementsByTagName('Region')->item(0)->textContent = $customer_details['provincia'];
        $PolicyHolder->getElementsByTagName('FiscalCode')->item(0)->textContent = $customer_details['codice_fiscale'];
        $PolicyHolder->getElementsByTagName('GenderType')->item(0)->textContent = 'M';
        $PolicyHolder->getElementsByTagName('JobTitle')->item(0)->textContent = 'aaaaaaaa';

        // Set Insured Dtails

        $InsuredDetails = $xml->getElementsByTagName('InsuredDetails')->item(0);
        $InsuredDetails->getElementsByTagName('InsuredName')->item(0)->textContent = $customer_details['name'] . ' ' . $customer_details['surname'];

        //Start End Date

        $CertificateDateTimeDetails = $xml->getElementsByTagName('CertificateDateTimeDetails')->item(0);
        $CertificateDateTimeDetails->getElementsByTagName('InceptionDate')->item(0)->textContent = $start_date;
        $CertificateDateTimeDetails->getElementsByTagName('ExpiryDate')->item(0)->textContent = $end_date;
        $CertificateDateTimeDetails->getElementsByTagName('ProposalDate')->item(0)->textContent = date('Y-m-d');

        $RiskPremiumDetails = $xml->getElementsByTagName('RiskPremiumDetails')->item(0);




        $RiskPremiumDetails->getElementsByTagName('Premium')->item(0)->textContent = $cart_details['price'];
        $RiskPremiumDetails->getElementsByTagName('Accessori')->item(0)->textContent = '0';
        $RiskPremiumDetails->getElementsByTagName('Tax')->item(0)->textContent =  $cart_details['tax'];
        $RiskPremiumDetails->getElementsByTagName('Gross')->item(0)->textContent =  $cart_details['price_with_tax'];


        $TotalPremiumDetails = $xml->getElementsByTagName('TotalPremiumDetails')->item(0);
        $TotalPremiumDetails->getElementsByTagName('Premium')->item(0)->textContent =  $cart_details['price'];
        $TotalPremiumDetails->getElementsByTagName('Accessori')->item(0)->textContent = '0';
        $TotalPremiumDetails->getElementsByTagName('Tax')->item(0)->textContent =  $cart_details['tax'];
        $TotalPremiumDetails->getElementsByTagName('Gross')->item(0)->textContent =  $cart_details['price_with_tax'];


        $Instalments = $xml->getElementsByTagName('Instalments')->item(0);
        $Instalments->getElementsByTagName('Premium')->item(0)->textContent =  $cart_details['price'];
        $Instalments->getElementsByTagName('Accessori')->item(0)->textContent = '0';
        $Instalments->getElementsByTagName('Tax')->item(0)->textContent = $cart_details['tax'];
        $Instalments->getElementsByTagName('Gross')->item(0)->textContent =  $cart_details['price_with_tax'];
        $Instalments->getElementsByTagName('InstalmentDate')->item(0)->textContent = date('Y-m-d');

        $PremiumReceipt = $xml->getElementsByTagName('PremiumReceipt')->item(0);
        $PremiumReceipt->getElementsByTagName('AmountPremiumReceived')->item(0)->textContent = $cart_details['price_with_tax'];
        $PremiumReceipt->getElementsByTagName('DatePremiumReceived')->item(0)->textContent = date('Y-m-d');

        $pdffile = file_get_contents($wording);
        $documentHashpdf = base64_encode(md5($pdffile, true));
        $CertificateAttachments = $xml->getElementsByTagName('CertificateAttachments')->item(0);
        $GeneralConditionsAttachment = $CertificateAttachments->getElementsByTagName('GeneralConditionsAttachment')->item(0);
        $GeneralConditionsAttachment->getElementsByTagName('FileHash')->item(0)->textContent = $documentHashpdf;

        // Add Prop

        $prop = file_get_contents($prop);
        $propHashpdf = base64_encode(md5($prop, true));
        $ProposalFormAttachment = $CertificateAttachments->getElementsByTagName('ProposalFormAttachment')->item(0);
        $ProposalFormAttachment->getElementsByTagName('FileHash')->item(0)->textContent = $propHashpdf;



        $strxml = $xml->saveXML();
        $document = base64_encode($strxml);



        $clientfolder = '/var/www/clients/client0/web1/private/clientstorage/' . $customer_details['customer_id'] . '/' . $cart_details['cart_id'] . '/';
        if (!file_exists($clientfolder)) {
            mkdir($clientfolder, 0777, true);
        }


        $xml->save($clientfolder . '/policy_response.xml');





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
            echo $isValid . "\n";
            if (isset($response->Errors)) {
                $fault = $response->Errors->fault->string;
                echo $fault . "\n";
            } else {
                print_r($response);
            }
        } catch (SoapFault $exception) {
            echo "Exception Thrown: " . $exception->faultstring . "\n";
            echo $client->__getLastResponse();
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
                print_r($response);
            }
        } catch (SoapFault $exception) {
            echo "Exception Thrown: " . $exception->faultstring . "\n";
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
                print_r($response);
            }
        } catch (SoapFault $exception) {
            echo "Exception Thrown: " . $exception->faultstring . "\n";
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
            file_put_contents($docName,  $response->Document);
        } catch (SoapFault $exception) {
            echo "Exception Thrown: " . $exception->faultstring . "\n";
            //echo $client->__getLastRequest();
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

/* responsexml
<?xml version="1.0" encoding="UTF-8"?>
<mstns:ImportDataSet xmlns:mstns="http://tempuri.org/ImportDataSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="Schema.xsd">
	<mstns:Document mstns:ModuleType="B" mstns:LastUpdatedDate="2011-02-25">

		<mstns:DocumentIndex>
			<mstns:ContractType>RC Professionale</mstns:ContractType>
			<mstns:CommercialInsuranceProduct>Ingegnere</mstns:CommercialInsuranceProduct>
			<mstns:PolicyVersionNumber>2019</mstns:PolicyVersionNumber>
			<mstns:IndexItem>
				<mstns:ItemText>Scheda di Copertura</mstns:ItemText>
				<mstns:PageNumber>6</mstns:PageNumber>
			</mstns:IndexItem>
			<mstns:IndexItem>
				<mstns:ItemText>Glossario</mstns:ItemText>
				<mstns:PageNumber>7</mstns:PageNumber>
			</mstns:IndexItem>
			<mstns:IndexItem>
				<mstns:ItemText>Condizioni di Polizza</mstns:ItemText>
				<mstns:PageNumber>9</mstns:PageNumber>
			</mstns:IndexItem>
			<mstns:IndexItem>
				<mstns:ItemText>Esclusioni</mstns:ItemText>
				<mstns:PageNumber>10</mstns:PageNumber>
			</mstns:IndexItem>
			<mstns:IndexItem>
				<mstns:ItemText>Procedure in caso di sinistro</mstns:ItemText>
				<mstns:PageNumber>12</mstns:PageNumber>
			</mstns:IndexItem>
			<mstns:IndexItem>
				<mstns:ItemText>Questionario</mstns:ItemText>
				<mstns:PageNumber>16</mstns:PageNumber>
			</mstns:IndexItem>
		</mstns:DocumentIndex>

		<mstns:BindingAuthorityDetails>
			<mstns:BARefDate>2020-02-04</mstns:BARefDate>
			<mstns:BARefNo>532</mstns:BARefNo>
			<mstns:BABrokersRef>123434</mstns:BABrokersRef>
			<mstns:BAPseudonym>AAA</mstns:BAPseudonym>
			<mstns:BANumber>9999</mstns:BANumber>
			<mstns:BAcorr_Pin1>115647</mstns:BAcorr_Pin1>
			<mstns:BAcorr_Pin2>XKT</mstns:BAcorr_Pin2>
			<mstns:BAUniqueMarketReference>B05320123456789</mstns:BAUniqueMarketReference>
		</mstns:BindingAuthorityDetails>
			
		<mstns:DocumentReferences>
			<mstns:CertificateICNumber>GVRCP1900000001906213-LB</mstns:CertificateICNumber>
			<mstns:ICNumber/>
			<mstns:ICStatus>1</mstns:ICStatus>
			<mstns:ICType>
				<mstns:DocumentType>0</mstns:DocumentType>
			</mstns:ICType>
			
			<mstns:RenewedICNumber/>
			<mstns:OriginalICNumber/>
			<mstns:RelatedInceptionDate>0001-01-01</mstns:RelatedInceptionDate>
			<mstns:RelatedInceptionTime>00:00</mstns:RelatedInceptionTime>
			<mstns:RelatedExpiryDate>0001-01-01</mstns:RelatedExpiryDate>
			<mstns:RelatedExpiryTime>00:00</mstns:RelatedExpiryTime>
		</mstns:DocumentReferences>
	
		<mstns:PolicyholderDetails>
			<mstns:FiscalCode>crtfnc94c26c573J</mstns:FiscalCode>
			<mstns:VATNumber>10655700150</mstns:VATNumber>
			<mstns:GenderType>M</mstns:GenderType>
			<mstns:LastName>croatti</mstns:LastName>
			<mstns:FirstName>francesco</mstns:FirstName>
			<mstns:JobTitle>aaaaaaaa</mstns:JobTitle>
			<mstns:AddressLine1>Via Garibaldi 116</mstns:AddressLine1>
			<mstns:AddressLine2/>
			<mstns:City>RIMINI</mstns:City>
			<mstns:PostCode>47921</mstns:PostCode>
			<mstns:Region>RN</mstns:Region>
			<mstns:Country>Italia</mstns:Country>
		</mstns:PolicyholderDetails>
		
		<mstns:InsuredDetails>
			<mstns:InsuredName>francesco croatti</mstns:InsuredName>
		</mstns:InsuredDetails>
		
		<mstns:CertificateSecurityDetails>
			<mstns:SecurityType>1</mstns:SecurityType>
			<mstns:LloydsPercentage>100</mstns:LloydsPercentage>
			<mstns:LloydsSecurityDetails>
				<mstns:SyndicateCode>5320</mstns:SyndicateCode>
				<mstns:SyndicateShare>100</mstns:SyndicateShare>
			</mstns:LloydsSecurityDetails>
		</mstns:CertificateSecurityDetails>
		
		<mstns:CertificateDateTimeDetails>
			<mstns:InceptionDate>2020-12-01</mstns:InceptionDate>
			<mstns:InceptionTime>24:00</mstns:InceptionTime>
			<mstns:ExpiryDate>2021-12-01</mstns:ExpiryDate>
			<mstns:ExpiryTime>24:00</mstns:ExpiryTime>
			<mstns:ProposalDate>2020-12-01</mstns:ProposalDate>
			<mstns:TacitRenewal>false</mstns:TacitRenewal>
			<mstns:TimeZone>
				<mstns:TimeZoneCode>UTC1</mstns:TimeZoneCode>
			</mstns:TimeZone>
		</mstns:CertificateDateTimeDetails>
		
		<mstns:RiskDetailsLB>
			<mstns:ObjectRiskInsured>Gli Assicurati indicati in polizza nell'esercizio delle funzioni di auditor, revisore fiscale ...</mstns:ObjectRiskInsured>
			<mstns:CountryJurisdiction>Italia</mstns:CountryJurisdiction>
			<mstns:LimitIndemnitySumInsured>Attività ordinaria €1.000.000, Auditor €500.000</mstns:LimitIndemnitySumInsured>
			<mstns:DeductibleExcessAmountUninsured>vedi schema di franchigia a pag.6</mstns:DeductibleExcessAmountUninsured>
		</mstns:RiskDetailsLB>
		
		<mstns:PolicySectionsWording>
			<mstns:SectionsWording>Art.1,2,6,10</mstns:SectionsWording>
		</mstns:PolicySectionsWording>
		
		<mstns:RiskPremiumDetails>
			<mstns:BusinessType>4</mstns:BusinessType>
			<mstns:RiskReference>4.245</mstns:RiskReference>
			<mstns:Premium>3908.02</mstns:Premium>
			<mstns:Accessori>0</mstns:Accessori>
			<mstns:Tax>869.53</mstns:Tax>
			<mstns:Gross>4777.55</mstns:Gross>
		</mstns:RiskPremiumDetails>
		
		<mstns:TotalPremiumDetails>
			<mstns:Currency>Euro</mstns:Currency>
			<mstns:Premium>3908.02</mstns:Premium>
			<mstns:Accessori>0</mstns:Accessori>
			<mstns:Tax>869.53</mstns:Tax>
			<mstns:Gross>4777.55</mstns:Gross>
			<mstns:NumberOfInstalments>1</mstns:NumberOfInstalments>
		</mstns:TotalPremiumDetails>
		
		<mstns:PremiumReceipt>
			<mstns:FirmReceivingPremium>Coverholder</mstns:FirmReceivingPremium>
			<mstns:AmountPremiumReceived>4777.55</mstns:AmountPremiumReceived>
			<mstns:DatePremiumReceived>2020-12-01</mstns:DatePremiumReceived>
		</mstns:PremiumReceipt>
		
		<mstns:PaymentTerms>Payment to Coverholder</mstns:PaymentTerms>
		
		<mstns:Instalments>
			<mstns:InstalmentNumber>1</mstns:InstalmentNumber>
			<mstns:InstalmentDate>2020-12-01</mstns:InstalmentDate>
			<mstns:Premium>3908.02</mstns:Premium>
			<mstns:Accessori>0</mstns:Accessori>
			<mstns:Tax>869.53</mstns:Tax>
			<mstns:Gross>4777.55</mstns:Gross>
		</mstns:Instalments>
		
		<mstns:CertificateConditions>
			<mstns:PolicyDescription>RC Professionale</mstns:PolicyDescription>
			<mstns:GeneralConditions>vedi allegati</mstns:GeneralConditions>
			<mstns:SpecialConditions>vedi Allegato 1 - Clausa di esclusione etc...</mstns:SpecialConditions>
			<mstns:PaymentPlanDescription>Soluzione unica</mstns:PaymentPlanDescription>
			<mstns:PaymentClause>Come disposto dalla Legge, se ...</mstns:PaymentClause>
			<mstns:ContactsNotification>info@lossadjuster.it - 02/0123456789</mstns:ContactsNotification>
			<mstns:ContactsQuestions>info@coverholderemail.it - 02/0123456789</mstns:ContactsQuestions>
		</mstns:CertificateConditions>
		
		<mstns:ConditionsReceived>Fascicolo</mstns:ConditionsReceived>
		
		<mstns:CertificateAttachments>
			<mstns:FrontJacket>S</mstns:FrontJacket>
<mstns:GeneralConditionsAttachment>
				<mstns:FileRef>Condizioni di Polizza</mstns:FileRef>
				<mstns:FileHash>tq8KGFNuJJHU71J7mNbVOQ==</mstns:FileHash>
			</mstns:GeneralConditionsAttachment>
			<mstns:ProposalFormAttachment>
				<mstns:FileRef>Questionario</mstns:FileRef>
				<mstns:FileHash>c+e8NBp9T1VBsoqwbHgXYw==</mstns:FileHash>
			</mstns:ProposalFormAttachment>

			<mstns:RearJacket>S</mstns:RearJacket>
		</mstns:CertificateAttachments>
		
		<mstns:RiskDetails>
			<mstns:RiskDescription/>
			<mstns:RiskLocation/>
			<mstns:SumInsured>0</mstns:SumInsured>
			<mstns:DeductibleOrExcess/>
			
			<mstns:OtherRiskDetails>
				<mstns:RiskDetailDescription/>
				<mstns:RiskDetailValue/>
			</mstns:OtherRiskDetails>
			
		</mstns:RiskDetails>

</mstns:Document>
</mstns:ImportDataSet> 
 */