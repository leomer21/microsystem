<?php
	/*
	 * @autor: MultiFour
	 * @version: 1.0.0
	 */
	session_start();
	error_reporting(0);
	ini_set("display_errors", 0);

	define('SUPRA', 1);
    define('SUPRA_BASE_PATH', __DIR__);	
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	if(!isset($_SESSION["uniqid"])){
		$_SESSION["uniqid"]= uniqid();
	}

	if(explode('=', $actual_link)[1] == "campaign"){
		$_SESSION["operation"] = "campaign";
	}
	if(explode('=', $actual_link)[1] == "landing"){
		$_SESSION["operation"] = "landing";
	}	
	include_once 'include/view.php';
	//echo $_SESSION["uniqid"];
	$view = new View();