<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;

$jwt = $_GET["TOKEN"];
$key = getenv('PUBLIC_TOKEN_K');



$data = JWT::decode($jwt, $key, array('HS256'));

var_dump($data);


?>