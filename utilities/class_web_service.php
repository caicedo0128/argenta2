<?php

class WebService{

	function callPOST($parameters, $url)
	{
		ob_start();
		$curl_request = curl_init();

		curl_setopt($curl_request, CURLOPT_URL, $url);
		curl_setopt($curl_request, CURLOPT_POST, 1);
		curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl_request, CURLOPT_HEADER, 1);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

		$jsonEncodedData = json_encode($parameters);	
		
		if ($url=="https://a31wkyf2w0.execute-api.us-east-1.amazonaws.com/2021_01_22_INIT/fact-e/crearEvento"){

		}
		
		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $jsonEncodedData);
		$result = curl_exec($curl_request);		
		
		curl_close($curl_request);			

		$result = explode("\r\n\r\n", $result, 2);
		$response = json_decode($result[1]);
		ob_end_flush();					

		return $response;
	}    

}

?>