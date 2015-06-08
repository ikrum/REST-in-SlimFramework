<?php

// allow api to get request from any domain
header ( "Access-Control-Allow-Origin: *" );

// TimeStamp Zone
date_default_timezone_set ( 'Asia/Dhaka' );

// Requrired Library
require '../libs/Slim/Slim.php';


\Slim\Slim::registerAutoloader ();
$app = new \Slim\Slim ();



$app->get ( '/', function () {
	$response = array (
			STATUS => SUCCESS,
			MESSAGE => "See the documentation"
	);
	echoRespnse ( $response );
} );



$app->notFound(function () use ($app) {
	$response = array(STATUS=>NOT_FOUND,MESSAGE=>"Page Not Found");
	echoRespnse($response);
});


$app->run ();
