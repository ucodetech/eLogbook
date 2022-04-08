<?php
session_start();
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'elogbook'

	),
	'remember' => array(
		'cookie_name' => 'logHash',
		'cookie_expiry' => '604800'
	),
	'session' => array(
		'session_admin' => 'logAdmin',
		'session_supervisor' => 'logSupervisor',
		'session_students' => 'logStudent',
		'token_name' => 'token'
	)
);

//APP ROOT
define('APPROOT', dirname(dirname(__FILE__)));

//URL ROOT

define('URLROOT', 'http://localhost/eLogbook/');

//SITE NAME
define('SITENAME', 'E-Log Book');
define('APPVERSION', '1.0.0');
define('ADMIN', 'CONTROL ROOM');
define('NAVNAME', 'ELB');
define('DASHBOARD', 'ELB Panel');
// define('EMAIL', 'youremail@gmail.com');
// define('PASSWORD', 'passwaord\\\===\\\@');
// define('AUDIOPATH', 'uploads/sermon/');




spl_autoload_register(function ($class) {
	require_once(APPROOT . '/classes/' . $class . '.php');
});


require_once(APPROOT . '/helpers/session_helper.php');
require_once(APPROOT . '/helpers/session.php');
