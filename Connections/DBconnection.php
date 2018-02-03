<?php
//Adapted sql query to PHP 7 and added minor error handling
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
//Catch anything wrong with DB connection
try { 
//Testsite DB
//$DBconnection = new PDO('mysql:host=localhost;dbname=karatekl_compreg;charset=utf8mb4', 'karatekl_tkc', '4=L|$?fQ1=jx');
//Production DB/Local site
$DBconnection = new PDO('mysql:host=localhost;dbname=tkc_test;charset=utf8mb4', 'karatekl_tkc', '4=L|$?fQ1=jx');
$DBconnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$DBconnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
} 
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>