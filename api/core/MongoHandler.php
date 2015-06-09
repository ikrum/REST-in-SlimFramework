<?php
define('DB_NAME',"contact_app");

class MongoHandler {
	private $db;
	private $collection;
	public function __construct() {
		global $db;
		
		$db = DB_NAME;
		$conn = new MongoClient ();
		$this->db = $conn->$db;
	}
	
	// Get collectoin of this collectoin name or specified collection from parameter
	public function selectCollection($name = null) {
		$col = $this->collection;
		if ($name != null)
			$col = $name;
		
		return $this->db->$col;
	}
	
	// setting up this collection name
	public function setCollectionName($name) {
		$this->collection = $name;
	}
	
	// getting the next id for a specific collection
	public function getSequence($sequence) {
		$c = $this->selectCollection ( "counter" );
		$ret = $c->findAndModify ( Array (
				'_id' => $sequence 
		), Array (
				'$inc' => Array (
						'seq' => 1 
				) 
		), null, Array (
				"new" => "true" 
		) );
		if (isset ( $ret ['seq'] ))
			return $ret ['seq'];
		else {
			$c->insert ( Array (
					"_id" => $sequence,
					"seq" => 2 
			) );
			return 1;
		}
	}
}

?>
