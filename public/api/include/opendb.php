<?php
$conn_user = @mysql_connect($sys_db_host, $sys_db_user, $sys_db_pass, true);
		@mysql_select_db($sys_db_name,$conn_user);
		mysql_set_charset('utf8'); 
?>