<?php
	
	function verifyApiKey(\Slim\Route $route){
		
	}


	function authenticate(\Slim\Route $route) {
	
		$app = \Slim\Slim::getInstance();
		$query = new QueryHandler();
		$auth = new HashGenerator();
		
		// Getting request headers
		$headers = apache_request_headers();
		$requestURI =  $_SERVER['REQUEST_URI'];
		$requestMethod = $app->request->getMethod();
		$params = $route->getParams();
		try {
			$userId = intval($params['userId']);
			if(!$userId>0)
				$userId = DEFAULT_USER;
		} catch (Exception $e) {
			$userId = DEFAULT_USER;
		}
		
		// TEST CODE ****************************
		$testParams = implode(',', getRequestParams());
		echo "<h3>$testParams</h3>";
		// END TEST CODE ************************
		
		// Get Handshake KEY
		if(!isset($headers['Authorization'])){
			// api key is missing in header
			exitApp(BAD_REQUEST,"Authorization key is misssing");
		}
		
		// Get User Access Key
		if(!isset($headers['AccessKey']) && $userId!== DEFAULT_USER){
			// api key is missing in header
			exitApp(BAD_REQUEST,"Access key is misssing");
			
		}
		
		$auth_key = $headers['Authorization'];
		@$accessKey = $headers['AccessKey'];
		
		$stringParams = implode(',', getRequestParams());


		// AUTHORIZE ADMIN OPERATION
		$adminData = "admin". $requestURI."#".$stringParams;
		$adminHash = $auth->getAuthHash($adminData);
		
		$userData = $userId.$requestURI."#".$stringParams;
// 		echo $userData;
		$userHash = $auth->getAuthHash($userData);
		
		// route the authorization for USER or ADMIN
		switch ($auth_key){
			case $adminHash:
				// check if admin is valid
				$admin = $query->getAdmin($accessKey);
				if(empty($admin)){
					exitApp(UNAUTHORIZED,"Admin not found!");
				}

				//Check admin access level
				if($admin[ADMIN_FIELDS::ACCESS_LEVEL=="read"] && $requestMethod != "GET"){
					exitApp(UNAUTHORIZED,"Limited admin access !");
				}
				// admin is verified
				break;
				
				
			case $userHash:
				//non-user operation
				if($userId==DEFAULT_USER)
					break;
					
				// UserOperatoin: check if user is valid
				$user_array = $query->getUser(array(
					USER_FIELDS::ACCESS_KEY => $accessKey
				));
				
				if(empty($user_array)){
					exitApp(UNAUTHORIZED,"Invalid access key!");
				}
				
				if($user_array[USER_FIELDS::IS_ACTIVE]==false){
					
					
					// if requesting login
					if (strpos($requestURI,'login') !== false) {
						$message= "Please activate your account";
					}
					// for other operation
					$message = "Your account has been deactivated.";
					exitApp(UNAUTHORIZED,$message);
				}
				
				if($user_array[USER_FIELDS::USER_ID]!=$userId)
					exitApp(UNAUTHORIZED,"You are not authorized to access others data");
					
				break;
			
			default:
				exitApp(UNAUTHORIZED, "Invalid authorization key !");
		}
		
	}
	
	function echoRespnse($response) {
		$app = \Slim\Slim::getInstance();
		// Http response code
		$app->status($response[STATUS]);
	
		// setting response content type to json
		$app->contentType('application/json');
	
		echo json_encode($response);
	}
	
	function exitApp($status,$message){
		$response[STATUS] = $status;
		$response[ERROR] = $message;
		echoRespnse($response);
		
		$app = \Slim\Slim::getInstance();
		$app->stop();
	}
	/**
	 * Verifying required params posted or not
	 */
	function verifyRequiredParams($required_fields) {
		$error = false;
		$error_fields = "";
		$request_params = getRequestParams();
		foreach ($required_fields as $field) {
			if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
				$error = true;
				$error_fields .= $field . ', ';
			}
		}
	
		if ($error) {
			// Required field(s) are missing or empty
			// echo error json and stop the app
			
			$message = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing';
			exitApp(BAD_REQUEST, $message);
		}
	}
	
	function getRequestParams(){
		$request_params = array();
		$request_params = $_REQUEST;
		// Handling PUT request params
		if ($_SERVER['REQUEST_METHOD'] != 'GET') {
			$app = \Slim\Slim::getInstance();
			parse_str($app->request()->getBody(), $request_params);
		}
		return $request_params;
	}

?>