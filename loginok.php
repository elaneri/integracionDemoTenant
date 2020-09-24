<?php


$recievedJwt = $_GET["TOKEN"];
$secret_key = getenv('PUBLIC_TOKEN_K');



// Split a string by '.' 
$token = explode(".", $recievedJwt);




$header = json_decode(base64_decode(strtr($token[0],'-_','+/')),true);
$signature = bin2hex(base64_decode(strtr($token[2],'-_','+/')));


echo $token[0]." \n";
echo $token[1]." \n";
echo $token[2]." \n";
echo $signature." \n";
echo hash_hmac('sha512',"$token[0].$token[1]",$secret_key)." \n";


if ($signature!=hash_hmac('sha512',"$token[0].$token[1]",$secret_key)) return false;




?>