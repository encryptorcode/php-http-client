<?php
namespace encryptorcode\httpclient;

class HttpRequest{
	private $method;
	private $url;
	private $requestBodyType;
	private $params;
	private $headers;
	private $body;

	public static function get($url) : HttpRequest{
		return new HttpRequest("GET",$url);
	}

	public static function post($url) : HttpRequest{
		return new HttpRequest("POST",$url);
	}

	public static function put($url) : HttpRequest{
		return new HttpRequest("PUT",$url);
	}

	public static function patch($url) : HttpRequest{
		return new HttpRequest("PATCH",$url);
	}

	public static function delete($url) : HttpRequest{
		return new HttpRequest("PATCH",$url);
	}

	private function __construct($method, $url){
		$this->method = $method;
		$this->url = $url;
	}

	public function param($key, $value) : HttpRequest{
		$this->params[$key] = $value;
		return $this;
	}

	public function header($key, $value) : HttpRequest{
		$this->headers[$key] = $value;
		return $this;
	}

	public function formParam($key, $value) : HttpRequest{
		if($this->method == "GET"){
			throw new HttpException("FORM_DATA not supported for method ".$this->method);
		}

		if(isset($this->requestBodyType)){
			if($this->requestBodyType != "FORM_DATA"){
				throw new HttpException("Request already has a body of type ".$this->requestBodyType);
			}
		} else {
			$this->requestBodyType = "FORM_DATA";
			$this->body = array();
		}
		
		$this->body[$key] = $value;
		return $this;
	}

	public function jsonData($data) : HttpRequest{
		if($this->method == "GET"){
			throw new HttpException("JSON_BODY not supported for method ".$this->method);
		}

		if(isset($this->requestBodyType)){
			if($this->requestBodyType != "JSON_BODY"){
				throw new HttpException("Request already has a body of type ".$this->requestBodyType);
			}
		}

		if(gettype($data) !== "string"){
			$this->body = json_encode($data);
		} else {
			$this->body = $data;
		}

		$this->requestBodyType = "JSON_BODY";
		$this->headers["Content-Type"] = "application/json";
		return $this;
	}

	public function getResponse() : HttpResponse{
		return HttpConnector::request($this);
	}

	public function getParams() : ?array{
		return $this->params;
	}

	public function getHeaders() : ?array{
		return $this->headers;
	}

	public function getMethod() : string{
		return $this->method;
	}

	public function getUrl() : string{
		return $this->url;
	}

	public function getRequestBodyType() : ?string{
		return $this->requestBodyType;
	}

	public function getBody(){
		return $this->body;
	}

}