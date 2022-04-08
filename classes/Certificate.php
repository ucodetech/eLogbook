<?php 

	/**
	 * certificate
	 */
class Certificate
{
	private $_db;

	function __construct()
	{
		$this->_db = Database::getInstance();
	}

public function create($fields = array())
{
	if (!$this->_db->insert('memberCerts', $fields)) {
		throw new Exception("Error uploading ceritificate", 1);
		
	}
}

public function fetchCerti()
{
	$get = $this->_db->get('memberCerts', array('deleted', '=', 0));
	if ($get->count()) {
		return $get->results();
	}else{
		return false;
	}
}

public function checkUser($userid)
{
	$get = $this->_db->get('memberCerts', array('user_id', '=', $userid));
	if ($get->count()) {
		return true;
	}else{
		return false;
	}
}


public function getUsers()
{
	$get = $this->_db->get('members', array('approved', '=', 1));
	if ($get->count()) {
		return $get->results();
	}else{
		return false;
	}
}

}//end of class