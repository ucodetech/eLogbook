<?php

	class DB{
		//properties
		 public $_pdo;


			//connect to database
		 		 public function __construct(){
		 		 	try{

		 		 	$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host'). ';dbname=' .Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		 		 	}catch(PDOException $e){
		 		 		die($e->getMessage());
		 		 	}
					return $this->_pdo;
		 		 }
		 		 	//create instance to check database connection


				 				 public function test_input($data){
				 					 $data = trim($data);
				 					 $data = stripslashes($data);
				 					 $data = htmlentities($data);
				 					 $data = htmlspecialchars($data);
				 					 return $data;

				 				 }
				 			 //error message

				 			 public function showMessage($type = 'success', $message){
				 				 return '<div class="alert alert-'.$type.' alert-dismissible">
				 							 <button type="button" class="close" data-dismiss="alert">
				 							 &times;
				 							 </button>
				 							 <strong class="text-center">'.$message.'</strong>
				 							 </div>';
				 			 }
	//end of class
	}

try {
	
} catch (Exception $e) {
	
}