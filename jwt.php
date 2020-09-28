<?php
$header = json_encode(['typ' => 'JWT','alg' => 'HS256']);

        $payload = json_encode([
            'sub' => '1',
            'name'=> 'Guest 1',
            'iss'=> '<YOUR_ISS>'
        ]);
    //exp 1609459200 = Jan 1st 2021

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $secret = base64_decode("<YOUR_SECRET>");
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    //Code above does not work. Three variants below don't work either.
        //$base64UrlSignature = $signature;
        //$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);
        //$base64UrlSignature = base64_encode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
		
		echo "JWT: \n" . $jwt . "\n";

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
	        echo "Login response: \n" . $response;
	      }
?>