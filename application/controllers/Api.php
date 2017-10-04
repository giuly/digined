<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	private $fb, $accessToken;
	protected $pageId = 'cocacolanetherlands';
	protected $top = 5;
	protected $postLimit = 20;

	public function __construct() {
		parent::__construct();

		// Load helper
		$this->load->helper('json_output_helper');
		// Load Model
		$this->load->model('Api_model');
		// *** !IMPORTANT *** 
		// Init - add Facebook credentials to Mongodb 
		// Uncoment below line when you first use de API then comment it back :)
		//$this->Api_model->add_credentials(); exit;

		// GET Facebook credentials
		$credentials = $this->Api_model->get_credentials();
		$this->accessToken = $credentials->accessToken;	

		// Instantiate PHP FBK SDK
		$fb = new Facebook\Facebook([
	    'app_id'     => $credentials->appId,
	    'app_secret' => $credentials->appSecret,
	    'default_graph_version' => $credentials->appVersion,
	    'default_access_token' => 'access_token'
    ]);

    $this->fb = $fb;
	}

	public function index(){
		// Do nothing
		echo 'This is a Public API - you must ask for actions, no defaults - GoAwAY';
	}

	/*############################################### API Methods ######################################################*/

	// Latest	20 posts by the	CocaColaNetherlands
	public function get_latest_posts() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400, array('status' => 400,'message' => 'Bad request.'));
		} else {
			try {  
	  		$response = $this->fb->get('/'.$this->pageId.'/posts?limit='.$this->postLimit.'', $this->accessToken );
	  		$data = $response->getDecodedBody()['data'];
	  		$status = $response->getHttpStatusCode();

	  		json_output($status, array('status' => $status,'data' => $data));
			} catch (Facebook\Exceptions\FacebookResponseException $e) {
			  $errorCode = $e->getHttpStatusCode();
				json_output($errorCode, array('status' => $errorCode,'message' => $e->getMessage()));
			}
		}	
	}

  // Top	5	users	who	have	liked	most	of	these	20	posts
	public function get_users_posts_likes() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400, array('status' => 400,'message' => 'Bad request.'));
		} else {
			$likes = array();

			try {  
	  		$tmpResponse = $this->fb->get('/'.$this->pageId.'/posts?limit='.$this->postLimit.'&fields=likes.limit(1).summary(true)', $this->accessToken );
	  		$tmpData = $tmpResponse->getDecodedBody()['data'];
	  		$maxLikes = $this->getMaxLikes($tmpData);

  			$response = $this->fb->get('/'.$this->pageId.'/posts?limit='.$this->postLimit.'&fields=likes.limit('.$maxLikes.')', $this->accessToken );
  			$dataLikes = $response->getDecodedBody()['data'];
  			$status = $response->getHttpStatusCode();

	  		$data = $this->getTopLikes($dataLikes, $this->top); 
	  		json_output($status,array('status' => $status,'data' => $data));

			} catch (Facebook\Exceptions\FacebookResponseException $e) {
			  $errorCode = $e->getHttpStatusCode();
				json_output($errorCode, array('status' => $errorCode,'message' => $e->getMessage()));
			}
		}	
	}

	// Data structure of latest 20 posts, ordered based on the number of likes they receive, along with the number of likes each post has received. 
	public function get_posts_ordered() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'){
			json_output(400,array('status' => 400,'message' => 'Bad request.'));
		} else {
			try {  
	  		$response = $this->fb->get('/'.$this->pageId.'/posts?limit='.$this->postLimit.'&fields=id,message,created_time,likes.limit(1).summary(true)', $this->accessToken );
	  		$data = $response->getDecodedBody()['data'];
	  		$status = $response->getHttpStatusCode();

	  		$data = $this->orderByLikes($data);
	  		json_output($status, array('status' => $status,'data' => $data));

			} catch (Facebook\Exceptions\FacebookResponseException $e) {
			  $errorCode = $e->getHttpStatusCode();
				json_output($errorCode, array('status' => $errorCode,'message' => $e->getMessage()));
			}
		}	
	}


	/*############################################# Bussiness Logic ######################################################*/
	/**
	* Get elements occurrence within a set of arrays
	* @param - array
	* @param - int
	* @return - array
	*/
	private function getTopLikes($arrays, $top) {
		$uniqueUsers = array();
		$allUsers    = array();
		$structure   = array();

		// Build unique users array and all users occurrence array 
		foreach ($arrays as $values) {
			foreach ($values['likes']['data'] as $user) {
				array_push($allUsers, $user['id']);
				$uniqueUsers[$user['id']] = $user['name'];
			}
		}

		// Count, Sort, Slice, Reverse top likes
		$likesPerUser = array_count_values($allUsers);
		asort($likesPerUser);
		$likesPerUser = array_slice ($likesPerUser, (int)(count($likesPerUser)-$top), (int)(count($likesPerUser)), true);
		$likesPerUser = array_reverse($likesPerUser, true);
			
		// Build array structure to return user and his number of likes
		foreach ($likesPerUser as $id => $likes) {
			array_push($structure, array('id' => $id, 'name' =>$uniqueUsers[$id], 'likes' => $likes));
		}		
		return $structure;
	}

	/**
	* Order a set of posts by their number of likes
	* @param - array
	* @return - array
	*/
	private function orderByLikes($data) {	
		$structure = array();
		foreach ($data as $values) {
			array_push(
				$structure, 
			  array(
		  		'id' => $values['id'],
				  'message' => $values['message'],
				  'created_time' => $values['created_time'],
				  'number_of_likes' => $values['likes']['summary']['total_count']
				)
			);
		}
		// Sort by provided callback
		usort($structure, function($a, $b) {
		    return $b['number_of_likes'] - $a['number_of_likes'];
		});	
		$structure = array_reverse($structure, true);
		return $structure;
	}

	/**
	* Return max value from an associative array
	* @param - array
	* @return - int
	*/
	private function getMaxLikes($data) {
		$max = 0;
		foreach ($data as $values) {
			$likesNr = $values['likes']['summary']['total_count'];
			if($max < $likesNr) {
				$max = $likesNr;
			}
		}
		return $max;
	}

}