<?php 

// Login SQL statment for all Pages

	// for encrypt
	$login_user=@mysql_real_escape_string($login_user);
	$login_password=@mysql_real_escape_string($login_password);
	// Get Login Methods
	//$user_login_method_a=$row_get_user_db_name_and_pass['user_login_method_a'];
	//$user_login_method_b=$row_get_user_db_name_and_pass['user_login_method_b'];
	// deleted because this is isolated system
	$get_user_data="select * from `users` where ( `$user_login_method_a`='$login_user' and `u_password`='$login_password' ) or ( `$user_login_method_b`='$login_user' and `u_password`='$login_password' )";
	$r_get_user_data=@mysql_query($get_user_data,$conn_user);

///////////////////////////////////	
	


?>