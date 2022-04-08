<?php 

/**
 * download file
 */
class Download
{
	private $_db;
	function __construct()
	{
		$this->_db = Database::getInstance();	
	}

	public function download($filepath){

		if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='. basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' .filesize($filepath));
        readfile($filepath);
        exit;

  	  }

	}



	public function totDownloads($table, $id)
	{
		$down = "SELECT * FROM $table WHERE id = '$id' ";
		$to = $this->_db->query($down);
		if ($to->count()) {
			return $to->first();
		}else{
			return false;
		}
	}
}



    
