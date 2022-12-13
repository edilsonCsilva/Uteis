<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


use  Analisticsdata\Uteis\Jwt;


require_once(__DIR__."/vendor/autoload.php");


echo "<pre>";
try{
    $payload = [
        'name' => 'Diogo',
        'email' => 'diogo.fragabemfica@gmail.com'
     ];
$jwt =  new Jwt("123");
$t =$jwt->generated($payload);
echo "<hr><br>";
echo $t;
 
echo "<hr><br>";
echo $jwt->validated($t);
echo "<hr><br>";
print_r($jwt->getPlayload($t));






}catch(Exception $e){
    echo $e->getMessage();
}


?>