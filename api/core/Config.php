<?php
	

	
/*********** HTTP Response Codes *************/	
	// Successfully executed
	define('SUCCESS',200);
	
	//Missing parameters
	define('PROCESS_NOT_COMPLETED',202);
	
	// Invalid request/header format :: Missing required fields
	define('BAD_REQUEST',400);
	
	//Authorization key or access Token is invalid, Account not active:: not allowed to access the URL
	define('UNAUTHORIZED',401);
	
	//Resource doesn't exists
	define('NOT_FOUND',404);
	
	//Server Overload & maintanance error
	define('INTERNAL_ERROR',500);
/******************END***********************/
	
	
/*********** Response keys *************/	
	define('STATUS','status');
	define('MESSAGE','message');
	define('ERROR','error');
	
?>
