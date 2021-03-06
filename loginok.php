<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

$key = getenv('PUBLIC_TOKEN_K'); /*KEY PUBLICA PARA VERIFICAR FIRMA */
$tenantK = getenv('TENANT_K'); /*KEY DEL TENANT  VERIFICAR TENANT */


$jwt = $_GET["TOKEN"];

if (isset($_GET["REF"])){

	$refreshtoken= CallAPI("GET","https://ssoia.herokuapp.com/JWT/refresh",$tenantK, $jwt);
	$jsonToken=json_decode($refreshtoken);


	$jwt=  str_replace("Bearer ", "", $jsonToken->token);
} 
	

$tks = explode('.', $jwt);       
list($headb64, $bodyb64, $cryptob64) = $tks;


$decoded = JWT::decode($jwt, $key, array('HS512'));
$decoded_array = (array) $decoded;
$userInfo = "";


$userInfo = CallAPI("GET","https://ssoia.herokuapp.com/Usuarios/".$decoded_array["client_id"],$tenantK,$jwt);

function CallAPI($method, $url, $tenantK,$jwt, $data = false)
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
    	'x-api-key:'.$tenantK,
    	'Authorization:Bearer '.$jwt
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

	echo "TOKEN :\n" . $jwt . "\n";

	?>
</p>
<p>
	<?php

	echo "TOKEN INFO:\n" . print_r($decoded_array, true) . "\n";

	?>
</p>
<code>
	<?php

	echo "USER INFO :\n" . str_replace("\n", "<br>", json_encode($userInfo , JSON_PRETTY_PRINT)) . "\n";

	?>
</code>

</br>
<?php
echo '<a href="./loginok.php?TOKEN='.$jwt.'&REF=y">REFRESH TOKEN</a>' ;
?>



</body>
</html> 