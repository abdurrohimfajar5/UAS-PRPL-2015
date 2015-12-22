<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
		$hostname_cms_blog = "localhost";
		$database_cms_blog = "website  isc";
		$username_cms_blog = "root";
		$password_cms_blog = "";		
		$cms_blog = mysql_pconnect($hostname_cms_blog, $username_cms_blog, $password_cms_blog) or trigger_error(mysql_error(),E_USER_ERROR); 
?>