<?php header("Content-type: application/json; charset=utf-8");?>
<?php //header("Content-type: application/json; charset=utf-8");
//header("Content-type: application/json;");
//<meta http-equiv='Content-Type' content='application/json; charset=windows-1256'/>
?>
<?php 

//error_reporting(0);
	include 'include/lang.php';
	date_default_timezone_set("Africa/Cairo");
	$today = date("Y-m-d");
    $today_time = date("g:i a");
	$last_day= date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$month_table_name = date("F_Y"); // name of month and year
	$last_hour=date("g:i a", strtotime("-1 hour", strtotime(date("g:i a")))) ;		// Last Hour
	
	
	
/////////////////////////////////////////////////////////////////////////	

if($_GET['op']=="login"){
	include 'include/api/login.php';
}//if($_GET['op']=="login")

/////////////////////////////////////////////////////////////////////////	

if($_GET['op']=="forgetPass"){
	include 'include/api/forget_pass.php';
}//if($_GET['op']=="login")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="regSms"){
	include 'include/api/regsms.php';
}//if($_GET['op']=="regSms")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="reg1"){
	include 'include/api/reg1.php';
}//if($_GET['op']=="reg1")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="reg2"){
	include 'include/api/reg2.php';
}//if($_GET['op']=="reg2")

//Pharaoh	
if($_GET['op']=="regAutoFree"){
	include'include/api/regAutoFree.php';
}	
	
/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="charge"){
	include 'include/api/charge.php';
}//if($_GET['op']=="charge")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="sendSMS"){
	include 'include/api/send_sms.php';
}//if($_GET['op']=="sendSMS")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getPackages"){
	include 'include/api/getPackages.php';
}//if($_GET['op']=="getPackages")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="buyPackage"){
	include 'include/api/buyPackage.php';
}//if($_GET['op']=="buyPackage")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="mobileCards1"){
	include 'include/api/mobileCards1.php';
}//if($_GET['op']=="buyPackage")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="mobileCards2"){
	include 'include/api/mobileCards2.php';
}//if($_GET['op']=="buyPackage")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="updateUserProfile"){
	include 'include/api/updateUserProfile.php';
}//if($_GET['op']=="buyPackage")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="sendMessage"){
	include 'include/api/sendMessage.php';
}//if($_GET['op']=="sendMessage")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="receiveMessages"){
	include 'include/api/receiveMessages.php';
}//if($_GET['op']=="sendMessage")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getPackagesSMS"){
	include 'include/api/getPackagesSMS.php';
}//if($_GET['op']=="getPackagesSMS")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getPackagesInternetMonthly"){
	include 'include/api/getPackagesInternetMonthly.php';
}//if($_GET['op']=="getPackagesSMS")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getPackagesInternetValidity"){
	include 'include/api/getPackagesInternetValidity.php';
}//if($_GET['op']=="getPackagesSMS")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getPackagesInternetTime"){
	include 'include/api/getPackagesInternetTime.php';
}//if($_GET['op']=="getPackagesSMS")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getPackagesInternetExtraBandwidth"){
	include 'include/api/getPackagesInternetExtraBandwidth.php';
}//if($_GET['op']=="getPackagesSMS")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="sendSMSconfirm"){
	include 'include/api/send_sms_confirm.php';
}//if($_GET['op']=="sendSMSconfirm")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="confirmPIN"){
	include 'include/api/confirmPIN.php';
}//if($_GET['op']=="sendSMSconfirm")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="setMessagesReaded"){
	include 'include/api/setMessagesReaded.php';
}//if($_GET['op']=="setMessagesReaded")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getHistoryChargedCards"){
	include 'include/api/getHistoryChargedCards.php';
}//if($_GET['op']=="getHistoryChargedCards")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getHistoryChargedPackages"){
	include 'include/api/getHistoryChargedPackages.php';
}//if($_GET['op']=="getHistoryChargedPackages")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="getHistoryChargedMobileCards"){
	include 'include/api/getHistoryChargedMobileCards.php';
}//if($_GET['op']=="getHistoryChargedMobileCards")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="transferCreadit"){
	include 'include/api/transferCreadit.php';
}//if($_GET['op']=="transferCreadit")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="transferCreaditConfirm"){
	include 'include/api/transferCreaditConfirm.php';
}//if($_GET['op']=="transferCreaditConfirm")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="receiveMessagesNew"){
	include 'include/api/receiveMessagesNew.php';
}//if($_GET['op']=="receiveMessagesNew")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="deleteMessages"){
    include 'include/api/deleteMessages.php';
}//if($_GET['op']=="deleteMessages")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="sendMessageReply"){
    include 'include/api/sendMessageReply.php';
}//if($_GET['op']=="sendMessageReply")

/////////////////////////////////////////////////////////////////////////
if($_GET['op']=="test")
{
		include 'include/api/test.php';
}//if($_GET['op']=="test")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="adminAddUser"){
    include 'include/api/adminAddUser.php';
}//if($_GET['op']=="adminAddUser")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="adminDeleteUser"){
    include 'include/api/adminDeleteUser.php';
}//if($_GET['op']=="adminDeleteUser")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="adminAddMac"){
    include 'include/api/adminAddMac.php';
}//if($_GET['op']=="adminAddMac")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="adminDeleteMac"){
    include 'include/api/adminDeleteMac.php';
}//if($_GET['op']=="adminDeleteMac")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="adminModifyUser"){
    include 'include/api/adminModifyUser.php';
}//if($_GET['op']=="adminModifyUser")

/////////////////////////////////////////////////////////////////////////

if($_GET['op']=="adminGetUsers"){
    include 'include/api/adminGetUsers.php';
}//if($_GET['op']=="adminGetUsers")

/////////////////////////////////////////////////////////////////////////

?>