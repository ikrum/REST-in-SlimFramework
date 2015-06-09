<?php
	
	/*
	 * Definition of collection objects and properties [table and coloumns / fields]
	 * Hard coding the names into the classes would be complex in term of changing the tables or fields
	 * Use the references for the table and fields
	 */
	
	
	// Names of MongoDB Collections
	class COLLECTIONS{
		const USER = 'user';
		const CONTACTS = 'contacts';
	}
	
	// Fields of USER collection
	class USER{
		const USER_ID='user_id';
		const API_KEY = "api_key";
		const NAME='name';
		const EMAIL='email';
	}
	
	// Fields of CONCTACTS collection
	class CONTACTS{
		const CONTACT_ID = 'contact_id';
		const NAME='name';
		const EMAIL='email';
		const NUMBER = 'number';
		const IS_FAVOURITE = 'is_favourite';
	}
?>