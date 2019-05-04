<?php
namespace encryptorcode\httpclient;

class HttpResponse{
	private $status;
	private $body;

	public function __construct(int $status, string $body){
		$this->status = $status;
		$this->body = $body;
	}

	public function getStatus() : int {
		return $this->status;
	}

	public function getBody() : string {
		return $this->body;
	}

	public function getJsonBody() : array {
		return json_decode($this->body);
	}
}