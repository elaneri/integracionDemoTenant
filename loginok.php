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


$decoded = JWT::decode($jwt, $key, array('HS512'));
$decoded_array = (array) $decoded;
$userInfo = "";
$userInfo = CallAPI("GET","https://ssoia.herokuapp.com/Usuarios/".$decoded_array["client_id"],$key);

function CallAPI($method, $url, $apkkey, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$headers = array(
    	'Content-type: application/json',
    	'x-api-key:'.$key,
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}



?>

<!DOCTYPE html>
<html>
<body>

<h1>Login Test</h1>
<p>
	<?php

	echo "TOKEN INFO:\n" . print_r($decoded_array, true) . "\n";

	?>
</p>
<p>
	<?php

	echo "USER INFO :\n" . $userInfo . "\n";

	?>
</p>


</body>
</html> 