<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

$jwt = $_GET["TOKEN"];
$key = getenv('PUBLIC_TOKEN_K');



$data = JWT::decode($jwt, $key, array('HS256'));
print_r($data);
var_dump($data);


?>