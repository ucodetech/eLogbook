<?php 

	class Hash{
		//create hash
		public static function make( $string, $salt = ''){
			return hash('sha256', $string . $salt);
		}
		//create salt
		public static function salt($length){
			return  saltMine($length);
		}
		//create unique key
		public static function unique(){
			return self::make(uniqid());

		}
	}