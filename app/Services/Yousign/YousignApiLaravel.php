<?php

namespace App\Services\Yousign;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Psr7;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class YousignApiLaravel
{

    protected $baseUrl;
    protected $baseUrlWithoutSlash;
    protected $apikey;
    protected $apiBaseUrl;
    protected $procedure_id;

    public function __construct()
    {
        $yousign_config = config('yousign');
        $this->setApikey($yousign_config['key']);
        $this->apiBaseUrl = $yousign_config['api_url'];
        $this->baseUrlWithoutSlash = substr($yousign_config['api_url'], 0, -1);
    }

    protected $_procedure = array(
        'name' => '',
        'description' => '',
        'start' => false,
        'expiresAt' => null,
        'template' => false,
        'ordered' => false,
        'metadata' => [],
        'config' => array(),
        'archive' => false
    );

    protected $_webhook = array();

    // default content SMS
    protected $_operationModeSmsConfig = array(
        "content" => "SIGNATURE ELECTRONIQUE : {{code}} est votre code de sécurité pour confirmer la signature de vos documents."
    );

    /**
     * Set API KEY from Yousign
     * @param $apikey
     */
    private function setApikey($apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * Set procedure key/value 
     * 
     * @param $apikey
     */

    public function setProcedureKeyValue($key, $value)
    {
        $this->_procedure[$key] = $value;
    }

    /**
     * Set webhook value 
     * 
     * @param $apikey
     */

    private function setWebhook($key, $value)
    {
        $this->_webhook[$key] = $value;
    }

    /**
     * Set add SMS Content  
     * 
     * @param $apikey
     */

    public function addSmsContent($value)
    {
        $this->_operationModeSmsConfig['content'] = $value;
    }

    /**
     * get numPage from a pdf file
     */

    public function getNumPagesInPDF($file)
    {
        $file_headers = @get_headers($file);
        //if ($file_headers[0] == 'HTTP/1.1 404 Not Found') return null;
        if (!$fp = @fopen($file, "r")) return null;
        $max = 0;
        while (!feof($fp)) {
            $line = fgets($fp, 255);
            if (preg_match('/\/Count [0-9]+/', $line, $matches)) {
                preg_match('/[0-9]+/', $matches[0], $matches2);
                if ($max < $matches2[0]) $max = $matches2[0];
            }
        }
        fclose($fp);
        return (int)$max;
    }

    /**
     * Set add Webhook  
     * 
     * @param $apikey
     */

    public function addWebhook($type, $url, $method, $headers = [])
    {
        $webhook = array(
            array(
                "url" => $url,
                "method" => $method,
                "headers" => $headers
            )
        );
        $this->setWebhook($type, $webhook);
    }

    /**
     * @return mixed
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    private function apiRequest($path, $method, $params = [], $removeSlash = false, $asBody = false)
    {
        if ($removeSlash) {
            $baseUrl = $this->baseUrlWithoutSlash;
        } else {
            $baseUrl = $this->apiBaseUrl;
        }

        try {
            if ($asBody) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $baseUrl . $path,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer " . $this->getApikey(),
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    return "cURL Error #:" . $err;
                } else {
                    return $response;
                }
            } else {
                $response = Http::withOptions(['debug' => false])
                    ->acceptJson()
                    ->withToken($this->getApikey())
                    ->$method($baseUrl . $path, $params);
                return $response->throw()->json();
            }
        } catch (RequestException $e) {
            activity()
                ->causedBy(auth()->user())
                ->event('signature')
                ->log("An error occurred on signing the documents;" . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @parms
     * @return mixed
     */

    public function createProcedure($name, $expiration_date, $webhook = false,$webhookMethod = '',$webhookUrl = '',$webhookHeader = '')
    {
        $custom_experience_id = '6aac0841-701a-4407-a0ea-ac59e0c03e25';
        $parameters = array('name'=> $name,
        'expiration_date' => $expiration_date,
        'delivery_mode'=> "none",
        'ordered_signers'=> false,
        'timezone'=> "Europe/Paris",
        'custom_experience_id'=> $custom_experience_id,
        //'workspace_id'=> $this->workspaceID,
        'external_id'=> '96');
            $conf = array();

            if($webhook == true){
            $conf["webhook"] = array(
            "procedure.finished" => array(
            array(
                "url" => $webhookUrl,
                "method" => $webhookMethod,
                "headers" => array(
                    "X-Custom-Header" => $webhookHeader
                )
            )
            )
            );
            // $parameters['config'] = $conf; // There is no config in v3
            }


            $curl = curl_init();

            $params = json_encode($parameters,true);

            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl."signature_requests",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->getapiKey()
            ),
            ));
            //dd($curl); 
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
            return "cURL Error #:" . $err;
            } else {
            $rtab = json_decode($response,true);
            
            $procedure_id = $rtab['id'];
            return $procedure_id;
            }
    }

    /**
     * @params 
     * @return mixed
     */

    public function getProcedure($procedureId)
    {
        $method = 'GET';
        $path = 'procedures';

        $this->apiRequest($path . '/' . $procedureId, $method);
    }

    /**
     * @params name string name of the file
     * @params content string s3Filename
     * @params procedureId string the procedure id from yousign
     * @params isAttachment boolean if the file is only readonly not for a signature
     * @return mixed
     */

    public function addFile($name, $filepath, $procedureId = null, $isAttachment = false)
    {
        
        $fileType = "application/pdf";

       
        
        $curlFile = new \CURLFILE($filepath, $fileType, $name);
        $parameters = array(
            'file'=> $curlFile);
            //'nature' => 'signable_document');

        $curl = curl_init();
        //        $params = json_encode($parameters,true);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl."electronic_seal_documents",//.$this->idAdvProc."/documents",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $parameters,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->getapiKey()
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);


        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            $rtab = json_decode($response,true);
            //$this->idfile = $rtab['id'];

            //print_r($response);
            return $response;
        }
    }

    public function addServerStamp($page, $position, $idfile, $procedure_id)
    {
        $fieldPosition = explode(",", $position);

        $parameter = array();

        $parameter['document_id'] = $idfile;
        $parameter['image_id'] = '4a7c59e9-97eb-4ff9-ac87-69d102f18c0a';
        $parameter['fields']= [['type'=> "seal",
                                    'height'=> (int)$fieldPosition[3] - (int)$fieldPosition[1],
                                    'width'=> (int)$fieldPosition[2] - (int)$fieldPosition[0],
                                    'page' => (int)$page,
                                    'x'=> (int)$fieldPosition[0],
                                    'y'=> 842 - (int)$fieldPosition[3],
                                    'reason' => 'Data-'. Carbon::now()->format('d-m-Y')]];
        
        $param = json_encode($parameter,true);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->apiBaseUrl.'electronic_seals',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$param,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer ".$this->getapiKey()
        ),
        ));

        $response = curl_exec($curl);
        //dd($response);
        curl_close($curl);
        
        //---------------------------------------------------------------------------------------------------------------

        sleep(10);

        try{
            $curl = curl_init();
            $parameters = array(
                "electronic_seal_document_id" => $idfile,
                "name" => "applications",
                "nature" => "signable_document",
                //"insert_after_id" => "null",
            );
            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl."signature_requests/".$procedure_id."/documents",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($parameters, true),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$this->getapiKey()
            ),
            ));
            
            $response2 = curl_exec($curl);
            //print_r($response2);
            //   die;
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $rtab = json_decode($response2,true);
                //$this->idfile = $rtab['id'];
                return $rtab['id'];
            }
        }
        catch (\Exception $e){
            return false;
        }
    }

    /**
     * @params firstname string
     * @params lastname string
     * @params email string
     * @params phone string
     * @params procedureId string
     * @params type string (default = 'signer') other 'validator'
     * @params position integer To determine the position of the signer for ordered procedure see https://dev.yousign.com/#dfe29009-0f87-41d4-a16d-3dbbb7e9c1db
     * 
     * @return mixed
     */

    public function addMember($firstname, $lastname, $email, $phone, $procedureId = null)
    {
        $member = array(
            "info"=>[
                "locale"=> "it",
                "first_name" => $firstname,
                "last_name" => $lastname,
                "email" => $email,
                "phone_number" => $phone,
                //                "procedure" => $this->idAdvProc);
            ],
            "signature_level"=> "electronic_signature",
            "signature_authentication_mode"=> "otp_sms");

        $curl = curl_init();

        $param = json_encode($member,true);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl."signature_requests/".$procedureId."/signers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$param,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                'Authorization: Bearer '.$this->getapiKey()
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            $rtab = json_decode($response,true);
            if(array_key_exists('violations',$rtab)){
                throw new \Exception($response);

            }
            $member = $rtab['id'];
            //print $response;
            $responseArray = array('id'=>$rtab['id'], 'info'=> array(
                'first_name'=> $rtab['info']['first_name'],
                'last_name'=> $rtab['info']['last_name'],
                'phone_number'=> $rtab['info']['phone_number'],
                'email'=> $rtab['info']['email']));
            //            return $rtab['id'];
            return $member;
        }
    }

    /**
     * @params fileId string
     * @params memberId string
     * @params reason string see https://dev.yousign.com/#ba613ed7-08fa-45ea-9b5f-5e850aa367dc
     * @params procedureId string
     * @params fieldName string field name where to put the signature
     * @params type int (2 types of fields: signature (default value) or text which will be used for text fields.)
     * @params contentRequired bool see https://dev.yousign.com/#3e7c6772-e92a-4b3d-98d0-84c22a64f3d7
     * @params content string|null see https://dev.yousign.com/#3e7c6772-e92a-4b3d-98d0-84c22a64f3d7
     * @params mention1 string
     * @params mention2 string
     * 
     * @return mixed
     */

    public function addFileObjectViaFieldName($fileId, $memberId, $reason, $fieldName, $type = 'signature', $contentRequired = true, $content = null, $mention = null, $mention2 = null)
    {
        $method = 'POST';
        $path = 'file_objects';

        $parameter = array(
            "file" => $fileId,
            "member" => $memberId,
            "fieldName" => $fieldName,
            "mention" => $mention,
            "mention2" => $mention2,
            "reason" => $reason,
            "content" => $content,
            "contentRequired" => $contentRequired,
        );

        return $this->apiRequest($path, $method, $parameter);
    }

    public function setProcedureId($procedure_id){
        $this->procedure_id = $procedure_id;
    }

    /**
     * @params fileId string
     * @params memberId string
     * @params position string positionning signature see https://placeit.yousign.fr/
     * @params reason string see https://dev.yousign.com/#ba613ed7-08fa-45ea-9b5f-5e850aa367dc
     * @params procedureId string
     * @params page int num page where to put the signature
     * @params type int (2 types of fields: signature (default value) or text which will be used for text fields.)
     * @params contentRequired bool see https://dev.yousign.com/#3e7c6772-e92a-4b3d-98d0-84c22a64f3d7
     * @params content string|null see https://dev.yousign.com/#3e7c6772-e92a-4b3d-98d0-84c22a64f3d7
     * @params mention1 string
     * @params mention2 string
     * 
     * @return mixed
     */

    public function addFileObjectViaPosition($fileId, $memberId, $position, $reason, $page, $type = 'signature', $contentRequired = true, $content = null, $mention = null, $mention2 = null)
    {
        //conversione delle coordinate alla v3
        $fieldPosition = explode(",", $position);

        $v3coordinates = array('x'=> (int)$fieldPosition[0],
                               'y'=> 842 - (int)$fieldPosition[3],
                               'height'=> (int)$fieldPosition[3] - (int)$fieldPosition[1],
                               'width'=> $fieldPosition[2] - $fieldPosition[0]);
                               //print_r($v3coordinates);die;
        //unset($parameter["position"]);

        $parameter['type'] = "signature";
        $parameter['page'] = $page;
        $parameter['x'] = $v3coordinates['x'];
        $parameter['y'] = $v3coordinates['y'];
        $parameter['height'] = 37;
        $parameter['width'] = $v3coordinates['width'];
        $parameter['signer_id'] = $memberId;
        $param = json_encode($parameter,true);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl."signature_requests/".$this->procedure_id."/"."documents"."/".$fileId."/fields",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$this->getapiKey(),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        //print_r($response);
        //die;
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $rtab = json_decode($response,true);
            //$this->fileobject = $rtab['id'];

            //print "informazioni SUL FILE BJJEE------------------------";
            //print_r($response);
            //die;
            return $response;
        }

    }

    /**
     *  used for start the procedure and get signature list
     * 
     */

    public function launchProcedure($procedureId)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl."signature_requests/".$procedureId."/activate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            //           CURLOPT_POSTFIELDS =>"{\n   \"start\": true\n}",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->getapiKey()
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    /**
     *  Consumption
     */

    public function consumption()
    {
        $method = 'PUT';
        $path = 'consumptions/metrics';

        return $this->apiRequest($path, $method);
    }

    /**
     *  getFileSigned
     */

    public function fileSigned($fileId, $binaryMode = true)
    {
        $method = 'GET';

        if ($binaryMode) {
            $path =  $fileId . "/download?alt=media";
        } else {
            $path =  $fileId . "/download";
        }

        return $this->apiRequest($path, $method, [], true, true);
    }

    public function getFile($filedId)
    {
        $method = 'GET';
        $path = $filedId;

        return $this->apiRequest($path, $method, [], true);
    }
}
