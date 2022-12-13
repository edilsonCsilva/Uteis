<?php

namespace Analisticsdata\Uteis;

use Exception;




class Jwt{
    private $secretKey;
    public function __construct($secretKey)
    {
        $this->secretKey=$secretKey;
        if(gettype($secretKey)!=='string'){
            throw new Exception("invalid format, expected [String] type.",400);
        }
        if( strlen(trim($this->secretKey)) == 0 ||  is_null($this->secretKey)){
            throw new Exception("the key is null.",400);
        }

        
    }

    public function generated($data=[]){
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
         ];
         $header = json_encode($header);
         $header = base64_encode($header);
       
         $payload = [
            'iss' => 'localhost',
            'server'=>$_SERVER['DOCUMENT_ROOT'],
            'ip'=>$_SERVER['REMOTE_ADDR'],
            'iat'=>date("y-m-d h:m:s"),
            'jti' =>uniqid()
         ];
         foreach($data as $index=>$valor){
            $payload[$index]=$valor;
         }
         
         $payload = json_encode($payload);
         $payload = base64_encode($payload);
        
         $signature = hash_hmac('sha256',"$header.$payload",$this->secretKey,true);
         $signature = base64_encode($signature);
         return  "$header.$payload.$signature";


    }

    public function validated($token){
            $payload=$this->getPlayload($token);
            return ($payload['signature'] == $payload['valid']) ? 1:-1;
    }


    public function getPlayload($token,$inBase64=true){
        $part = explode(".",$token);
        $header =($inBase64) ? $part[0]:base64_decode($part[0]) ;
        $payload =($inBase64) ? $part[1]:base64_decode($part[1]) ;  
        $signature = $part[2];

        $valid = hash_hmac('sha256',"$header.$payload",$this->secretKey,true);
        $valid = base64_encode($valid);
        return array("header"=>$header,"payload"=>$payload,"signature"=>$signature,"valid"=>$valid);
    }

    
}

