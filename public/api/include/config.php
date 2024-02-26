<?php

// for local API
require_once '../config.php';
//$reg1_web_site="http://dna.fci-cu.edu.eg";
$url = "https://domina.microsystem.com.eg";

$reg1_web_site="https://domina.microsystem.com.eg";

$regautofree_web_site = $url."/api/api.php";
$sys_name="Hotspot";
$sys_admin_name="Ehab Ebrahim";

$sys_db_host = "localhost";
$sys_db_name = "microsystem";
$sys_db_user = "hotspot_hotspot";
$sys_db_pass = 'O2E/pGx5cR9rK[M]';

$sys_local_or_web="web";// because this system is isolated department
$me_local_or_web="web";// because this is isolated system
$local_url="";// because this is isolated system
$sys_state="running";
$mobileChargeTransfer="1";//user can transfer Mobile creadit(android)
$mobileChargeCard="1";//user can charge mobile card (android)
$mikrotikIP="10.0.0.1";// for ping check for login page (android)
// Get Login Methods used in include/sql/sql.php
$user_login_method_a="u_uname";
$user_login_method_b="u_mobile";

?>