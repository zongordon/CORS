<?php 
//Added $comp_start_time and $comp_limit_roundrobin 
//Truncated $row_rsCurrentComp['comp_start_time'] to 5 characters

//Set timezone
date_default_timezone_set("Europe/Stockholm");

//Display errors! NOTE! Turn-off for production sites!!
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Catch anything wrong with query
try {
// Select all data for the active competition
require('Connections/DBconnection.php');           
$query_rsCurrentComp = "SELECT * FROM competition WHERE comp_current = 1";
$stmt_rsCurrentComp = $DBconnection->query($query_rsCurrentComp);
$row_rsCurrentComp = $stmt_rsCurrentComp->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
$comp_id = $row_rsCurrentComp['comp_id'];
$comp_name = $row_rsCurrentComp['comp_name'];
$comp_start_date = $row_rsCurrentComp['comp_start_date'];
$comp_end_date = $row_rsCurrentComp['comp_end_date'];
$comp_end_reg_date = $row_rsCurrentComp['comp_end_reg_date'];
$comp_arranger = $row_rsCurrentComp['comp_arranger'];
$comp_email = $row_rsCurrentComp['comp_email'];
$comp_url = $row_rsCurrentComp['comp_url'];
$comp_raffled = $row_rsCurrentComp['comp_raffled'];
$comp_max_regs = $row_rsCurrentComp['comp_max_regs'];
$comp_start_time = substr($row_rsCurrentComp['comp_start_time'],0,5);
$comp_limit_roundrobin = $row_rsCurrentComp['comp_limit_roundrobin'];

$pagedescription="$comp_name som arrangeras av $comp_arranger";
$pagekeywords="$pagetitle, $comp_arranger, $comp_name, karate, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?php echo $pagedescription ?>"/>
<meta name="keywords" content="<?php echo $pagekeywords ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
<script language="JavaScript" type="text/javascript" src="includes/PopUp.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>


