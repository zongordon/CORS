<?php

//Changed from karatekl_test for Test DB

# FileName="Connection_php_mysql.htm"

# Type="MYSQL"

# HTTP="true"

//Catch anything wrong with DB connection

try { 

//Testsite DB/Local site

//$DBconnection = new PDO('mysql:host=localhost;dbname=karatekl_test;charset=utf8mb4', 'karatekl_tkc', 'c04^!etT8W0E');

//Production DB/Comp DB

$DBconnection = new PDO('mysql:host=localhost;dbname=karatekl_tkc;charset=utf8mb4', 'karatekl_tkc', 'c04^!etT8W0E');

$DBconnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$DBconnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

} 

catch (PDOException $e) {

    echo 'Connection failed: ' . $e->getMessage();

}

?>