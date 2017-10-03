<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

  private $connection;
  private $collection = 'facebook_app';

  function __construct() {
    parent::__construct();
    
    $ci = & get_instance();
    $ci->load->config('mongoci');
    $server   = $ci->config->item('mongo_server');
    $port     = $ci->config->item('mongo_port');
    $database = $ci->config->item('mongo_dbname');

    try{
      $client = new MongoDB\Client("mongodb://$server:27017");
      $this->connection = $client->$database;
    } catch(MongoDB\Exception\Exception $e) {
        var_dump($e->getMessage());
    }

  }

  // Read from db - mongodb Facebook app credentials
  function get_credentials() {
    try{
        $collection = $this->collection;
        return $this->connection->$collection->findOne();
    } catch(MongoDB\Exception\Exception $e) {
        var_dump($e->getMessage());
    }
  }

  // Store Facebook App details in a Mongodb document
  // *** !WRONG *** 
  // You are not allowed to keep credentials into source code,
  // but for the sake of this example, when the Api is first initalized,
  // those credentials should be passed to MongoDB 
  function add_credentials() {
    $collection = $this->collection;
    $this->connection->$collection->drop();
    $result = $this->connection->$collection->insertOne( 
      [ 
        'appId' => '103298347092152',
        'appSecret' => 'c9f4c5b4151fb92f3cc8598278c28ec4',
        'accessToken' => 'EAABd8wVIyLgBABH0ZBGyRMVH7MrYuyDysjTcyLJZCkXsIDXrzB12LkZChwOFFqtBXZBWcyoUlg656RuvCkJUZCclaWz0NLHDenPixAObvMaQ5g72ZBaGighI7bbb8D70y5hyhuBUqDCUxOqFFhoLuDzUmqVK0rxHSSMUOP9qWS4nOXkZCM32B92',
        'appVersion' => 'v2.5'
      ]
    );
    echo "Inserted with Object ID '{$result->getInsertedId()}'";
  }

}