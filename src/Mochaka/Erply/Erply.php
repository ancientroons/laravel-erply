<?php

namespace Mochaka\Erply;

use Log;
use Config;
use Session;
use Mochaka\Erply\Error;
use GuzzleHttp;


class Erply {
	/**
	 * endpoint url
	 * @var string
	 */
	protected $url;

	/**
	 * unique client id
	 * @var int
	 */
	protected $clientCode = 0;

	/**
	 * api auth username
	 * @var string
	 */
	protected $username;

	/**
	 * api auth password
	 * @var string
	 */
	protected $password;

	/**
	 * array of bulk calls to be made
	 * @var array
	 */
	protected $requests;


	/**
	 * assign all variables in construct
	 * @param int $clientCode
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($clientCode, $username, $password)
	{
		//Log::info('client code?: '.config('erply.clientcode'));
		$this->clientCode = $clientCode;
		$this->username = $username;
		$this->password = $password;
		$this->url = "https://".$this->clientCode.".erply.com/api/";
	}

	/**
	 * send the actual request off to the api
	 * @param  string $request
	 * @param  array  $parameters
	 * @return array
	 */
	private function sendRequest($request, $parameters = array())
	{

		if(!$this->url || !$this->clientCode || $this->clientCode == 0 || !$this->username || !$this->password){
			throw new \Exception('Missing parameters, please check your config file.');
		}

		$parameters['request'] = $request;
		$parameters['clientCode'] = $this->clientCode;
		$parameters['version'] = '1.0';
		if($request != "verifyUser") $parameters['sessionKey'] = $this->getSessionKey();
		if($request != "verifyUser" && !$parameters['sessionKey'])  return array();

		//$client = new Client($this->url);
		//$response = $client->createRequest('POST', $this->url, null, $parameters)->send()->json();
		$client = new GuzzleHttp\Client();
		$response = $client->request('POST', $this->url, ['form_params' => $parameters]);
//Log::info('parameters: '.print_r($parameters, true));

		//Log::info('response body: '.print_r($response->getBody()->getContents(), true));

		//if(isset($response['status']['errorCode']) && $response['status']['errorCode'] != 0)
		if($response->getStatusCode() !== 200)
		{
			throw new \Exception(Error::code($response->getStatusCode()), $response->getStatusCode());
		}
		else
		{
			$contents = json_decode($response->getBody()->getContents());
			//$contents = $response->getBody()->getContents();
			//Log::info('response records: '.print_r($contents->records, true));

			return $contents;
		}
	}

	/**
	 * add an api call to the array for bulk calls
	 * @param array $data
	 */
	public function addCall($data)
	{
		$this->requests[] = $data;
	}

	/**
	 * send a bulk api call using the requests array
	 * @return array
	 */
    public function callBulk()
    {
        $count = count($this->requests);
        if($count >= 100)
        {
            $arr = array_chunk($this->requests, 99, true);
            foreach($arr as $a)
            {
                $response[] = $this->sendRequest('', array('requests'=>json_encode($a)));
            }
            $response['count'] = $count;
            $response['split'] = ceil($count / 99);
            $response = json_encode($response);
        }
        else
        {
                $response = $this->sendRequest('', array('requests'=>json_encode($this->requests)));
        }

        return $response;
    }


    /**
     * get's a temporary api key for entered user
     * @return string
     */
	protected function getSessionKey()
	{
		$sessionKey = 'ErplySessionKey_'.$this->clientCode.'_'.$this->username;
		$sessionExpiresKey = 'ErplySessionKeyExpires_'.$this->clientCode.'_'.$this->username;

		if(!Session::get($sessionKey) || !Session::get($sessionExpiresKey) || Session::get($sessionExpiresKey) < time())
		{
			$response = $this->sendRequest("verifyUser", array("username" => $this->username, "password" => $this->password));

			if(!isset($response->records[0]->sessionKey))
			{
				Session::forget($sessionKey);
				Session::forget($sessionExpiresKey);
				throw new \Exception('Verify user failure');
			}

			Session::put($sessionKey ,$response->records[0]->sessionKey);
			Session::put($sessionExpiresKey, (time() + $response->records[0]->sessionLength - 30));
		}

		return Session::get($sessionKey);
	}

	/**
	 * magic function for calling the api based on input method
	 * @param  string $method
	 * @param  array  $parameters
	 * @return array
	 */
	public function __call($method, $parameters = array())
	{
		$param = (isset($parameters[0]) ? $parameters[0] : $parameters);
		return $this->sendRequest($method, $param);
	}

}
