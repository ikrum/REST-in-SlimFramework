<?php

define("PRIVATE_KEY", "3e03b4bc5d81956709ff2565c2b27ce7"); # MD5[Daak_1082154_PrivateKey]

class HashGenerator {
	
	// Login, Register, forgotPassword all other non-login request will be authorized by  $userID = 0
	public function getAuthHash($data) {
		return hash_hmac ( "sha1", $data, PRIVATE_KEY );
	}
	
	public function generatePin(){
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$numbers = "0123456789";
		$pin_length = 8;
		$pin = '';
		for ($i = 0; $i < $pin_length; $i++) {
			if($i%2==0)
				$pin .= $characters[rand(0, strlen($characters) - 1)];
			else
				$pin .= $numbers[rand(0, strlen($numbers) - 1)];
		}
		return $pin;
	}
	
	public function generateAccessToken($userID){
		$apiCode = "Daak";
		$salt = "1082154";
		$time = time();
		$algo = $apiCode.$salt.$userID.$time;
		return  md5($algo);	
	}
	

	
}

?>
