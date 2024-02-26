<?php
date_default_timezone_set("Africa/Cairo");
$today_charging = date("Y-m-d"); // No 1
$today_time = date("H:i:s");
$created_at = date("Y-m-d H:i:s");
$url=$_SERVER['HTTP_HOST'];
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

include '../../config.php';

$conn_microsystem=mysqli_connect($sys_db_host, $sys_db_user, $sys_db_pass, $sys_db_name);

//@mysqli_select_db($sys_db_name;
if($_SESSION['Identify']){
	$customerDatabase=$_SESSION['Identify'];
	$getCustomerDB="select * from `customers` where `database`='$customerDatabase'";
}else{
	$getCustomerDB="select * from `customers` where `url`='$url'";
}
$r_getCustomerDB=@mysqli_query($conn_microsystem,$getCustomerDB);
if(@mysqli_num_rows($r_getCustomerDB)>0 or $_SESSION['Identify']) {
    
    $row_CustomerDB=@mysqli_fetch_array($r_getCustomerDB);
    $customerDatabase=$row_CustomerDB['database'];
	$customer_id=$row_CustomerDB['id'];

    //$conn_customer = @mysqli_connect($sys_dbhost, $sys_db_user, $sys_db_pass, true);
    //@mysqli_select_db($customerDatabase, $conn_customer);
    
	//mkdir("landing/".uniqid(), 0755);
	$unzip = new ZipArchive;
	
	
	if ($unzip->open('tmp/'.$file_name) === TRUE && isset($_SESSION["uniqid"])) {
		// get customer ID
		
		//$r_getCustomerID=@mysqli_query($conn_microsystem,"select * from 'settings' where `type`='customer_id'");
		//$row_getCustomerID=@mysqli_fetch_array($r_getCustomerID,$conn_customer);
		//$customer_id=$row_getCustomerID['value'];
		$fullURL = $url."/builder/landing/".$uniqid;

		// step 1 : insert record in Microsystem DB
		//echo "gjhgjdfsdsjnbhjghdfgdfasdfghjklasdfghjyrffyio";

		@mysqli_query($conn_microsystem,"insert into `landing_pages` (customer_id,url,created_at) values ('$customer_id','$fullURL','$created_at')");
		
		$type = $_SESSION["operation"];
		$already_exists = "select * from $customerDatabase.history where `details` like '%$uniqid%'";
		$result_already_exists = @mysqli_query($conn_microsystem,$already_exists);

		if(@mysqli_num_rows($result_already_exists) == 0){  
			// step 2 : insert record in User DB
			@mysqli_query($conn_microsystem,"insert into $customerDatabase.history (`operation`,`type1`,`type2`,`details`,`add_date`,`add_time`,`notes`) values ('custom_landing_page','hotspot','admin','$fullURL','$today_charging','$today_time','$type')");
			//echo "insert into $customerDatabase.history (`operation`,`type1`,`type2`,`details`,`add_date`,`add_time`,`notes`) values ('custom_landing_page','hotspot','admin','$fullURL','$today_charging','$today_time','$type')";
		}  
		// step 3 : unzip file

		$unzip->extractTo("landing/".$uniqid);
		$unzip->close();
		$dir = __DIR__;
        
        $src = $dir."/landing/".$uniqid;
        $dst = explode('builder', $dir)[0]."custom-landing";
        if(is_dir($dst.'/'.$uniqid)){
        	$old_folder = "rm -fr $dst/$uniqid";
        	exec("$old_folder");
        	$function="cp -r $src $dst";
	        $remove = "rm -fr $dst/$uniqid/index.blade.php";
	        exec("$function");
	        exec("$remove");		
        }else{
	        $function="cp -r $src $dst";
	        $remove = "rm -fr $dst/$uniqid/index.blade.php";
	        exec("$function");
	        exec("$remove");
		}
	    $src2 = $dir."/landing/".$uniqid;
        $dst2 = explode('public/builder', $dir)[0]."resources/views/front-end/custom-landing";
        if(is_dir($dst.'/'.$uniqid)){
        	$old_folder2 = "rm -fr $dst2/$uniqid";
        	exec("$old_folder2");
        	$function2="cp -r $src2 $dst2";
	        $remove2 = "rm -fr $dst2/$uniqid/js $dst2/$uniqid/css $dst2/$uniqid/fonts $dst2/$uniqid/images";
	        exec("$function2");
	        exec("$remove2"); 	
        }else{
        	$function2="cp -r $src2 $dst2";
	        $remove2 = "rm -fr $dst2/$uniqid/js css fonts images";
	        exec("$function2");
	        exec("$remove2"); 
        }

        if($_SESSION["operation"] == "landing"){
	        //$remove_bootstrap = "rm -fr $dst/$uniqid/css/bootstrap.css";
	        //$remove_icons = "rm -fr $dst/$uniqid/css/icons.css";
	        $remove_js = "rm -fr $dst/$uniqid/js/jquery-2.1.4.min.js";
	        /*exec("$remove_bootstrap"); exec("$remove_icons");*/ exec("$remove_js");

	        $old_header_ligt = '<div class="form-container bg-1-color-light light">';
			$old_header_dark = '<div class="form-container bg-1-color-light light">';
			$new_header_ligt = '<div class="form-container bg-1-color-light light">'." @include('...front-end.landing.custom_auth') ";
			$new_header_dark = '<div class="form-container bg-1-color-light light">'." @include('...front-end.landing.custom_auth') ";

			if(isset($old_header_dark)){
				$old_replace = $old_header_dark;
				$new_replace = $new_header_dark;
			}
			if(isset($old_header_ligt)){
				$old_replace = $old_header_ligt;
				$new_replace = $new_header_ligt;
			}
			//read the entire string
			$str0 = file_get_contents(explode('public/builder', $dir)[0]."resources/views/front-end/custom-landing/".$uniqid.'/index.blade.php');

			//replace something in the file string - this is a VERY simple example
			$str0 = str_replace($old_replace, $new_replace, $str0);

			//write the entire string
			file_put_contents(explode('public/builder', $dir)[0]."resources/views/front-end/custom-landing/".$uniqid.'/index.blade.php', $str0);

			$oldMessage2 = '</header>';
			$newMessage2 = '</header>'." @include('...front-end.landing.custom_auth_js') ";

			//read the entire string
			$str1 = file_get_contents(explode('public/builder', $dir)[0]."resources/views/front-end/custom-landing/".$uniqid.'/index.blade.php');

			//replace something in the file string - this is a VERY simple example
			$str1 = str_replace($oldMessage2, $newMessage2, $str1);

			//write the entire string
			file_put_contents(explode('public/builder', $dir)[0]."resources/views/front-end/custom-landing/".$uniqid.'/index.blade.php', $str1);




			/*$oldcss = '.dark a:not(.btn):not(.gallery-box):not(.goodshare) {';
			$newcss = '.darkaaaaaaaaaa a:not(.btn):not(.gallery-box):not(.goodshare) {';

			//read the entire string
			$str_css = file_get_contents(explode('builder', $dir)[0]."custom-landing/".$uniqid.'/css/custom.css');

			//replace something in the file string - this is a VERY simple example
			$str_css = str_replace($oldcss, $newcss, $str_css);

			//write the entire string
			file_put_contents(explode('builder', $dir)[0]."custom-landing/".$uniqid.'/css/custom.css', $str_css);


			$oldcss = '.dark  {';
			$newcss = '.darkaaaaaaaaaa  {';

			//read the entire string
			$str_css = file_get_contents(explode('builder', $dir)[0]."custom-landing/".$uniqid.'/css/custom.css');

			//replace something in the file string - this is a VERY simple example
			$str_css = str_replace($oldcss, $newcss, $str_css);

			//write the entire string
			file_put_contents(explode('builder', $dir)[0]."custom-landing/".$uniqid.'/css/custom.css', $str_css);			
			*/

		}
	}
	

}
?>