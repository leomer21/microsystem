<?php
$sys_db_host = "localhost";
$sys_db_name = "microsystem";
$sys_db_user = "hotspot_hotspot";
$sys_db_pass = 'O2E/pGx5cR9rK[M]';

//////////////////////////////
///// pay mob controller /////
//////////////////////////////

$username = 'microsystem';
$password = 'password';
//$integration_id4cardEGP = '286'; // test EGP
$integration_id4cardEGP = '1234'; // LIVE EGP
// $integration_id4cardUSD = '1150'; // test USD
$integration_id4cardUSD = '1536'; // LIVE USD
// $integration_id4wallet = '1143'; // test Wallet
$integration_id4wallet = '1235'; // LIVE Wallet
// $integration_id4cash = '1152'; // test CASH
$integration_id4cash = '1537'; // LIVE CASH
$iframe_id = '40284';

///////////////////////////////////////////
// accountKitAppID for auto installation //
///////////////////////////////////////////

$accountKitAppID4installation = "accountKitAppID4installation";
$accountKitAppSecret4installation = "accountKitAppSecret4installation";

//////////////////////////////////////////////////////////////////////
//   auto installation controller data  and Mikrotik Unconfigured   //
//////////////////////////////////////////////////////////////////////

$systemMasterIP = "10.16.10.2";
$systemLocalIP = "10.16.10.2";
$webPanelType = "cwp"; // whm
$installation_currency = "EGP";
$installation_url = ".mymicrosystem.com";
$installation_wifi_marketing_state = "0"; // 1: on, 0:off
$installation_priceing_state = "1"; // 1: on, 0:off
$trialPackageID = "6"; //46: 50 concurrent device + WiFi marketing module
$trialPackageDays = "2"; // days
$trialPackageConcurrentDevice = "50";
$trialPackageModules = " Automated internet management modules ";

// WHM Variables
$hotspotAccountName = 'hotspotAccountName';
$hotspotUserName = 'hotspotUserName';
$hotspotPassword = 'hotspotPassword';
$serverDomain = 'system1.microsystem.com.eg';
// CWP Variables
$keyCode = "keyCode";

/////////////////
// Replication //
/////////////////
$noOfBackupServers = 1;
$s1BackupServerIP = '10.16.10.2';
$s2BackupServerIP = '10.16.10.2';
$s3BackupServerIP = '';
$s4BackupServerIP = '';
$s5BackupServerIP = '';
$s6BackupServerIP = '';
$s7BackupServerIP = '';
$s8BackupServerIP = '';
$s9BackupServerIP = '';
$s10BackupServerIP = '';

//////////////////////
// WhatsApp server1 //
//////////////////////
$whatsapp_Srv1_IP = "157.175.50.205";
$whatsapp_Srv1_OPsocketPort = "40"; // for operations: send receive Messages
$whatsapp_Srv1_MasterSocketPort = "40"; // for resrart, registration, 

////////////////////////////////////////////
// Fawry Direct Integration Server2Server //
////////////////////////////////////////////
// $fawryUsername = "Microsystem_admin";
// $fawryPassword = "";
// $fawryIntegrationUrl = "https://atfawry.fawrystaging.com//ECommerceWeb/Fawry/payments/charge"; // staging
// $fawryMerchantCode = "1tSa6uxz2nSGL8WPmUnrmQ=="; // staging
// $fawrySecurityKey = "fa1353fcc58d469fa04c4ad450cae001"; // staging

$fawryIntegrationUrl = "https://www.atfawry.com/ECommerceWeb/Fawry/payments/charge"; // live
$fawryMerchantCode = "fawryMerchantCode"; // live
$fawrySecurityKey = "fawrySecurityKey"; // live

////////////////
// POS rocket //
////////////////
$posRocketClientID = 'posRocketClientID';
$posRocketClientSecret = 'posRocketClientSecret';

?>