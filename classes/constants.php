<?php


$session_time = 30000;

session_set_cookie_params($session_time, "/", false, false);
//ini_set('session.cookie_path', '/');
ini_set('session.gc_maxlifetime', $session_time);
//ini_set('session.cookie_lifetime', 5000000);
ini_set('session.use_cookies', 1);
session_start();


mb_internal_encoding("UTF-8");
/* Константы по работе с БД */


	

	define("IS_LOCAL_SERVER", false);
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'doc_templates');	


//ini_set('display_errors', 1); 
error_reporting(E_ALL & ~E_DEPRECATED);	



$self_fn = str_ireplace('\\','/',__FILE__);
define("ROOT", str_ireplace( "classes/".basename($self_fn ), "", $self_fn ));
define("TESTER", true);

define('DB_CHARSET','utf8');
define('DB_COLLATION','utf8_general_ci');



// ~~~~~~~~~~~~~~~~~~~~~~~		Автозагрузка
function main_autoload($class_name) {
	$filename = ROOT . 'classes/'.$class_name.'.php';
	
	if ($res = file_exists($filename)) require_once($filename); 
	else trigger_error("Not found = {$filename}", E_USER_WARNING);
};
spl_autoload_register('main_autoload');


