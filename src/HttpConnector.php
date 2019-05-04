<?php
namespace encryptorcode\httpclient;

class HttpConnector{
	public static function request(HttpRequest $request) : HttpResponse{
		$fullUrl = $request->getUrl();
		$queryParams = $request->getParams();
		if(isset($queryParams)){
			$firstParam = true;
			foreach ($queryParams as $key => $value) {
				if($firstParam){
					$firstParam = false;
					$fullUrl .= '?';
				} else {
					$fullUrl .= '&';
				}
				$fullUrl .=  urlencode($key) . "=" . urlencode($value);
			}
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $fullUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());

		$requestBodyType = $request->getRequestBodyType();
		if(isset($requestBodyType)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getBody());
		}
		
		$headersArray = array();
		$headers = $request->getHeaders();
		if(isset($headers)){
			foreach ($headers as $key => $value) {
				$headersArray[] = $key . ": " . $value;
			}
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headersArray);
		
		$responseBody = curl_exec($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		$response = new HttpResponse($responseCode, $responseBody);
		return $response;
	}
}