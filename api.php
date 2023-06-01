<?php
require_once("classes/constants.php");

$api = new API();
$api->scheme = [
	"success" => [ "title" => "Success", "text" => ""],
	"entities" => [	
		"users" => [	"patch" => [ "fields" => "id, login, password, name, email, role, address", ]],
		"templates" => [	"patch" => [ "fields" => "id, title, posada, text", ]],
	],
];

switch ( $api->request ) {
	
	case "get/auth":		$api->getAuth(); 	break;
	case "post/auth":	$api->postAuth();	break;
	case "delete/auth":	$api->deleteAuth();	break;
	
	case "get/data":		$api->getData(); 	break;
	
	case "get/templates":		$api->getScheme( "templates" ); 	break;
	case "post/templates":		$api->postScheme( "templates" ); 	break;
	case "patch/templates":		$api->patchScheme( "templates" ); 	break;
	case "delete/templates":		$api->deleteScheme( "templates" ); 	break;			

	case "get/templates/detail":		$api->getTemplatesDetail(); 	break;
		// api_request end 
	
	case "post/copy/rows":		$api->copyRows(); 	break;
	case "get/modifications":		$api->getModifications(); 	break;
	
	default: $api->error( "Unknown request \"{$api->request}\"" );
};

$api->output();
