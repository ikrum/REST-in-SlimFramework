<?php

// allow api to get request from any domain
header ( "Access-Control-Allow-Origin: *" );

// TimeStamp Zone
date_default_timezone_set ( 'Asia/Dhaka' );

// Requrired Library
require '../libs/Slim/Slim.php';

// ***** Required Files to run single request **** //
require_once '../core/Config.php';
require_once '../core/Collections.php';
require_once '../core/HashGenerator.php';
require_once '../core/Utils.php';

require_once '../core/MongoHandler.php';
require_once '../core/QueryHandler.php';
require_once '../core/Auth.php';

\Slim\Slim::registerAutoloader ();
$app = new \Slim\Slim ();

$app->post(		'/contacts',  		 'verifyApiKey','addContact' );
$app->get( 		'/contacts',  		 'verifyApiKey','getContactList' );
$app->get( 		'/contacts/:id',  	 'verifyApiKey','getContact' );
$app->put( 		'/contacts/:id',  	 'verifyApiKey','updateContact' );
$app->delete( 	'/contacts/:id',  	 'verifyApiKey','deleteContact' );
$app->put( 		'/contacts/:id/star','verifyApiKey','addToFavourite' );
$app->delete( 	'/contacts/:id/star','verifyApiKey','removeFromFavourite' );

$app->get ( '/', function () {
	$response = array (
			STATUS => SUCCESS,
			MESSAGE => "See the documentation"
	);
	echoRespnse ( $response );
} );


	
function addContact() {
	verifyRequiredParams ( array (
			CONTACTS::NAME,
			CONTACTS::NUMBER,
	) );
	$request = Slim\Slim::getInstance ()->request ();
	
	$name = trim ( $request->post ( CONTACTS::NAME ) );
	$email = strtolower ( trim ( $request->post ( CONTACTS::EMAIL ) ) );
	$number = $request->post ( CONTACTS::NUMBER );
	$is_favourite = $request->post (CONTACTS::IS_FAVOURITE);
	
	
	if(empty($is_favourite)){
		$is_favourite=false;
	}else{
		$is_favourite = $is_favourite === 'true'? true: false;
	}

	$contact = array (
			CONTACTS::NAME => $name,
			CONTACTS::EMAIL => $email,
			CONTACTS::NUMBER => $number,
			CONTACTS::IS_FAVOURITE => $is_favourite
	);
	
	$query = new QueryHandler ();
	$response = $query->addContact ( $contact );
	
	echoRespnse ( $response );
}



function getContactList() {
	$request = Slim\Slim::getInstance ()->request ();
	$query = array();
	$type = trim ( $request->get ('type') );

	$query = new QueryHandler ();
	if($type=='favourite')
		$response = $query->getContactList (array(CONTACTS::IS_FAVOURITE=>true));
	else
		$response = $query->getContactList (array());
	
	echoRespnse ( $response );
}

function getContact($id) {
	$id = intval ( $id );

	$query = new QueryHandler ();
	$response = $query->getContact( $id );

	echoRespnse ( $response );
}



function updateContact($id) {
	$request = Slim\Slim::getInstance ()->request ();
	$id = intval ( $id );

	
	verifyRequiredParams ( array (
		CONTACTS::NAME,
		CONTACTS::NUMBER,
	) );
	
	$name = trim ( $request->put ( CONTACTS::NAME ) );
	$email = strtolower ( trim ( $request->put ( CONTACTS::EMAIL ) ) );
	$number = $request->put ( CONTACTS::NUMBER );
	$is_favourite = $request->put (CONTACTS::IS_FAVOURITE);
	$is_favourite = $is_favourite === 'true'? true: false;
	
	$contact = array (
			CONTACTS::NAME => $name,
			CONTACTS::EMAIL => $email,
			CONTACTS::NUMBER => $number,
			CONTACTS::IS_FAVOURITE => $is_favourite
	);
	$contact = removeEmptyFields ( $contact );
	
	
	$query = new QueryHandler ();
	$response = $query->updateContact ($id,$contact);
	echoRespnse ( $response );
}

function deleteContact($id) {
	$id = intval ( $id );

	
	$query = new QueryHandler ();
	$response = $query->deleteContact ($id);
	echoRespnse ( $response );
}

function addToFavourite($id) {
	$request = Slim\Slim::getInstance ()->request ();
	$id = intval ( $id );

	
	$contact = array (
			CONTACTS::IS_FAVOURITE => true
	);
	
	$query = new QueryHandler ();
	$response = $query->updateContact ($id,$contact);
	echoRespnse ( $response );
}

function removeFromFavourite($id) {
	$request = Slim\Slim::getInstance ()->request ();
	$id = intval ( $id );
	$contact = array (
			CONTACTS::IS_FAVOURITE => false
	);

	$query = new QueryHandler ();
	$response = $query->updateContact ($id,$contact);
	echoRespnse ( $response );
}


$app->notFound(function () use ($app) {
	$response = array(STATUS=>NOT_FOUND,MESSAGE=>"Page Not Found");
	echoRespnse($response);
});


$app->run ();
