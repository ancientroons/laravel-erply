<?php 

namespace Mochaka\Erply;

use Config;
use Session;
use Mochaka\Erply\Error;
use Guzzle\Http\Client;


class Erply {
	protected $url;
	protected $clientCode;
	protected $username;
	protected $password;

	public function __construct($clientCode, $username, $password)
	{
		$this->clientCode = $clientCode;
		$this->username = $username;
		$this->password = $password;
		$this->url = "https://".$this->clientCode.".erply.com/api/"; 
	}

	private function sendRequest($request, $parameters = array())
	{

		//validate that all required parameters are set
		if(!$this->url OR !$this->clientCode OR !$this->username OR !$this->password){
			throw new Exception('Missing parameters');
		}
	
		//add extra params
		$parameters['request'] = $request;
		$parameters['clientCode'] = $this->clientCode;
		$parameters['version'] = '1.0';
		if($request != "verifyUser") $parameters['sessionKey'] = $this->getSessionKey();
		if($request != "verifyUser" && !$parameters['sessionKey'])  return false;

		$client = new Client($this->url);
		$response = $client->createRequest('POST', $this->url, null, $parameters)->send()->json();
		
		if($response['status']['errorCode'] != 0)
		{
			throw new \Exception(Error::code($response['status']['errorCode']), $response['status']['errorCode']);
			return false;
		}
		else
		{
			return $response;
		}
	}

	protected function getSessionKey() 
	{
		$sessionKey = 'ErplySessionKey_'.$this->clientCode.'_'.$this->username;
		$sessionExpiresKey = 'ErplySessionKeyExpires_'.$this->clientCode.'_'.$this->username;

		if(!Session::get($sessionKey) || !Session::get($sessionExpiresKey) || Session::get($sessionExpiresKey) < time()) 
		{
			$response = $this->sendRequest("verifyUser", array("username" => $this->username, "password" => $this->password));
			print_r($response);

			if(!isset($response['records'][0]['sessionKey'])) 
			{
				Session::forget($sessionKey);			
				Session::forget($sessionExpiresKey);			
				throw new \Exception('Verify user failure');
			}

			Session::put($sessionKey ,$response['records'][0]['sessionKey']);
			Session::put($sessionExpiresKey, (time() + $response['records'][0]['sessionLength'] - 30));	
		}

		return Session::get($sessionKey);
	}

	public function __call($method, $parameters = array())
	{
		return $this->sendRequest($method, $parameters);
	}

}