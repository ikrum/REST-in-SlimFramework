<?php
	/**
	 * Remove empty array elements
	 * @param array $array the array need to be cleaned
	 * @return clean array
	 */
	function removeEmptyFields($array){
		$newArray = array();
		foreach ($array as $key => $value){
			if($value != null)
				$newArray[$key] = $value;
		}
		return $newArray;
	}
	
?>