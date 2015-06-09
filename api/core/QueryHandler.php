<?php
class QueryHandler {
	private $db;
	private $collection;
	public function __construct() {
		$this->db = new MongoHandler ();
		// Default collection
		$this->collection = $this->db->selectCollection ( COLLECTIONS::CONTACTS );
	}
	public function verifyApiKey() {
		$this->collection = $this->db->selectCollection ( COLLECTIONS::USER );
		return true;
	}
	
	/*
	 * Add new contact 
	 * @param array $contact parameters of the contact 
	 * return response array
	 */
	public function addContact($contact) {
		// Get the next Contact ID
		$index_id = $this->db->getSequence ( CONTACTS::CONTACT_ID );
		$contact [CONTACTS::CONTACT_ID] = $index_id;
		
		// Insert contact
		$this->collection->insert ( $contact );
		
		$response [STATUS] = SUCCESS;
		$response [MESSAGE] = "Contact added !";
		
		return $response;
	}
	function getContactList($array) {
// 		var_dump($array);
		$fields = array(
			CONTACTS::CONTACT_ID,
			CONTACTS::NAME,
			CONTACTS::NUMBER,
			CONTACTS::EMAIL,
			CONTACTS::IS_FAVOURITE
		);
		
		$cursor = $this->collection->find ($array);
// 		var_dump($cursor);
		$response [STATUS] = SUCCESS;
		$response ['contacts'] =  $this->parseContact($cursor);
		
		return $response;
	}
	
	function getContact($id) {
		$cursor = $this->collection->find( array (CONTACTS::CONTACT_ID=>$id) );
	
		$response [STATUS] = SUCCESS;
		$response ['contacts'] = $this->parseContact($cursor);;
	
		return $response;
	}
	
	function updateContact($id,$contact) {
		$this->collection->update ( array (
				CONTACTS::CONTACT_ID => $id 
		), array (
				'$set' => $contact
		) );
		
		$response [STATUS] = SUCCESS;
		$response [MESSAGE] = "Update successful";
		
		return $response;
	}
	
	function deleteContact($id){
		
		$contact = $this->collection->findOne( array (CONTACTS::CONTACT_ID=>$id) );
		
		
		if($contact){
			$this->collection->remove(array(CONTACTS::CONTACT_ID => $id), array("justOne" => true));
			$response [STATUS] = SUCCESS;
			$response [MESSAGE] = "Delete successful";
		}else{
			$response [STATUS] = NOT_FOUND;
			$response [MESSAGE] = "Contact not found";
		}
		return $response;
	}
	
	function parseContact($cursor){
		$array = array();
		foreach ($cursor as $doc) {
			$item[CONTACTS::CONTACT_ID] = $doc[CONTACTS::CONTACT_ID];
			$item[CONTACTS::NAME] = $doc[CONTACTS::NAME];
			$item[CONTACTS::NUMBER] = $doc[CONTACTS::NUMBER];
			$item[CONTACTS::EMAIL] = $doc[CONTACTS::EMAIL];
			$item[CONTACTS::IS_FAVOURITE] = $doc[CONTACTS::IS_FAVOURITE];
			$array[] = $item;
		}
		return $array;
	}
}

?>