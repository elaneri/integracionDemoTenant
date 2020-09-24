<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

$jwt = $_GET["TOKEN"];
$key = getenv('PUBLIC_TOKEN_K');


$tks = explode('.', $jwt);       
list($headb64, $bodyb64, $cryptob64) = $tks;
$header = \Firebase\JWT\JWT::jsonDecode(Firebase\JWT\JWT::urlsafeB64Decode($headb64));
var_dump($header->alg);


$data = JWT::decode($jwt, $key, array('HS512'));
print_r($data);
var_dump($data);


?>