<?php 
//Added DB sql for current competition 
//Moved meta description and keywords from individual pages

//Display errors! NOTE! Turn-off for production sites!!
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Catch anything wrong with query
try {
//Find out if competition has been raffled or not
require('Connections/DBconnection.php');           
$query = "SELECT * FROM competition WHERE comp_current = 1";
$stmt_rsCurrentComp = $DBconnection->query($query);
$totalRows_rsCurrentComp = $stmt_rsCurrentComp->rowCount();
$row_rsCurrentComp = $stmt_rsCurrentComp->fetch(PDO::FETCH_ASSOC);
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}
$comp_arranger = $row_rsCurrentComp['comp_arranger'];
$comp_name = $row_rsCurrentComp['comp_name'];
$comp_email = $row_rsCurrentComp['comp_email'];
$comp_url = $row_rsCurrentComp['comp_url'];

$pagedescription=''.$row_rsCurrentComp['comp_name'].' som arrangeras av Eskilstuna Karateklubb i Eskilstuna Munktellarena.';
$pagekeywords=''.$row_rsCurrentComp['comp_name'].', karate, eskilstuna, Munktellarenan, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?php echo $pagedescription ?>"/>
<meta name="keywords" content="<?php echo $pagekeywords ?>" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
<script language="JavaScript" type="text/javascript" src="includes/PopUp.js"></script>
</head>
