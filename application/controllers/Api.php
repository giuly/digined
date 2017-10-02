<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	private $fb, $appId, $appSecret, $accessToken;
	protected $pageId    = 'cocacolanetherlands';
	protected $appVersion = 'v2.4';

	public function __construct() {
		parent::__construct();

		// Load helpers
		$this->load->helper('json_output_helper');

		// Read from db - mongodb Facebook app credentials
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->digined->facebook_app;
		$result = $collection->findOne();

		$this->appId = $result->appId;
		$this->appSecret = $result->appSecret;
		$this->accessToken = $result->accessToken;		
		
		// Instantiate PHP FBK SDK
		$fb = new Facebook\Facebook([
	    'app_id'     => $this->appId,
	    'app_secret' => $this->appSecret,
	    'default_graph_version' => $this->appVersion,
	    'default_access_token' => 'access_token'
    ]);

    $this->fb = $fb;
	}

	public function index(){
		// Do nothing
		echo 'This is an Public API - you must ask for actions, no default - GoAwAY';
	}

	public function getLastPost() {

		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			try {  
	  		$response = $this->fb->get('/'.$this->pageId.'/posts?limit=20', $this->accessToken );
	  		$data = $response->getDecodedBody()['data'];
	  		json_output(200,array('status' => 200,'data' => $data));
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  json_output(400,array('status' => 400,'message' => 'Graph returned an error: ' . $e->getMessage()));
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  json_output(400,array('status' => 400,'message' => 'Graph returned an error: ' . $e->getMessage()));
			  exit;
			}
		}	
	}

	/*
	// Store Facebook App details in a Mongodb document
	public function addToMongo() {
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->digined->facebook_app;
		$result = $collection->insertOne( [ 'appId' => '103298347092152', 'appSecret' => 'c9f4c5b4151fb92f3cc8598278c28ec4', 'accessToken' => 'EAABd8wVIyLgBABH0ZBGyRMVH7MrYuyDysjTcyLJZCkXsIDXrzB12LkZChwOFFqtBXZBWcyoUlg656RuvCkJUZCclaWz0NLHDenPixAObvMaQ5g72ZBaGighI7bbb8D70y5hyhuBUqDCUxOqFFhoLuDzUmqVK0rxHSSMUOP9qWS4nOXkZCM32B92' ] );
		echo "Inserted with Object ID '{$result->getInsertedId()}'";
	}*/

}
