<?php
$header = json_encode(['typ' => 'JWT','alg' => 'HS256']);

// When generating a JWT the "sub" value determines if the guest user is going to be unique. 
// If the same "sub" is used and you just update "name" then you're just changing the name of the existing guest user. 
// If you define a unique "sub" value and a new "name" value then you're creating a new guest user. 

$payload = json_encode([
	'sub' => '1',
	'name' => 'Guest 1',
	// Enter your ISS
	'iss' => '<YOUR_ISS>',
	'exp' =>  (time() + 50) 
		]);

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Enter your secret
$secret = base64_decode("<YOUR_SECRET>");

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

// Print generated JWT to console
echo "JWT: \n" . $jwt . "\n\n";

// POST to exchange JWT for access token
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://webexapis.com/v1/jwt/login",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_HTTPHEADER => array(
	"authorization: Bearer " . $jwt,
	"content-type: application/json"
),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo "Login response: \n" . $response . "\n";
}
?>
