<?php
/*
* Purpose : passing Authentication config object to the configuration
*/
namespace CyberSource;
require_once __DIR__. DIRECTORY_SEPARATOR .'vendor/autoload.php';


class ExternalConfiguration
{
        //initialize variable on constructor
        function __construct()
        {
                $this->authType = "http_signature";//http_signature/jwt
                $this->enableLog = true;
                $this->logSize = "1048576";
                $this->logFile = "Log";
                $this->logFilename = "cybs.log";
                $this->merchantID = "rkings_ecom2_sit";
                $this->apiKeyID = "98ceb103-6f5e-4886-997d-f847a0ea34dd";
                $this->secretKey = "G/pB1u/ojjb3TbSSxqNs5jbgyiP5qL2vPsK2EiY4B8k=";
                $this->keyAlias = "rkings_ecom2_sit";
                $this->keyPass = "rkings_ecom2_sit";
                $this->keyFilename = "rkings_ecom2_sit";
                $this->keyDirectory = "Resources/";
                $this->runEnv = "apitest.cybersource.com";
                $this->merchantConfigObject();
        }
        //creating merchant config object
        function merchantConfigObject()
        {
                $config = new \CyberSource\Authentication\Core\MerchantConfiguration();
                if(is_bool($this->enableLog))
                        $confiData = $config->setDebug($this->enableLog);

                $confiData = $config->setLogSize(trim($this->logSize));
                $confiData = $config->setDebugFile(trim(__DIR__ . DIRECTORY_SEPARATOR . $this->logFile));
                $confiData = $config->setLogFileName(trim($this->logFilename));
                $confiData = $config->setauthenticationType(strtoupper(trim($this->authType)));
                $confiData = $config->setMerchantID(trim($this->merchantID));
                $confiData = $config->setApiKeyID($this->apiKeyID);
                $confiData = $config->setSecretKey($this->secretKey);
                $confiData = $config->setKeyFileName(trim($this->keyFilename));
                $confiData = $config->setKeyAlias($this->keyAlias);
                $confiData = $config->setKeyPassword($this->keyPass);
                $confiData = $config->setKeysDirectory(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $this->keyDirectory);
                $confiData = $config->setRunEnvironment($this->runEnv);
                $config->validateMerchantData($confiData);
                return $config;
        }

        function ConnectionHost()
        {
                $merchantConfig = $this->merchantConfigObject();
                $config = new Configuration();
                $config = $config->setHost($merchantConfig->getHost());
                $config = $config->setDebug($merchantConfig->getDebug());
                $config = $config->setDebugFile($merchantConfig->getDebugFile() . DIRECTORY_SEPARATOR . $merchantConfig->getLogFileName());
                return $config;
        }

        function FutureDate($format){
                if($format){
                        $rdate = date("Y-m-d",strtotime("+7 days"));
                        $retDate = date($format,strtotime($rdate));
                }
                else{
                        $retDate = date("Y-m",strtotime("+7 days"));
                }
                echo $retDate;
                return $retDate;
        }

        function CallTestLogging($testId, $apiName, $responseMessage){
                $runtime = date('d-m-Y H:i:s');
                $file = fopen("./CSV_Files/TestReport/TestResults.csv", "a+");
                fputcsv($file, array($testId, $runtime, $apiName, $responseMessage));
                fclose($file);
        }

        function downloadReport($downloadData, $fileName){
                $filePathName = __DIR__. DIRECTORY_SEPARATOR .$fileName;
                $file = fopen($filePathName, "w");
                fwrite($file, $downloadData);
                fclose($file);
                return __DIR__.'\\'.$fileName;
        }
}

?>
