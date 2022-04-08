<?php

	class Cookie{
		//check if cookie exsits
		public static function exists($name){
			return (isset($_COOKIE[$name])) ? true :false;
		}
		//get cookie if exist
		public static function get($name){
			return $_COOKIE[$name];
		}
		//put cookie
		public static function put($name, $value, $expiry){
			if (setcookie($name, $value, time() + $expiry, '/')) {
				return true;
			}
		}
		//delete cookie
		public static function delete($name){
			self::put($name, '', time() - 1);
		}
	}
