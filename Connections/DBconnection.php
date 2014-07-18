<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_DBconnection = "localhost";
//local DB
//$database_DBconnection = "karatekl_compreg";
//Production DB
$database_DBconnection = "karatekl_tkc";
$username_DBconnection = "karatekl_tkc";
$password_DBconnection = "4=L|$?fQ1=jx";
$DBconnection = mysql_pconnect($hostname_DBconnection, $username_DBconnection, $password_DBconnection) or trigger_error(mysql_error(),E_USER_ERROR); 
//mysql_query("SET NAMES 'utf8' COLLATE 'utf8_swedish_ci'");
?>