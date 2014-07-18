<?php 
//Adjusted to display page title

if (!isset($_SESSION)) {
  session_start();
}
require_once('Connections/DBconnection.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccounts = "SELECT a.account_id FROM account AS a INNER JOIN clubregistration AS cl USING(account_id) INNER JOIN competition AS c USING(comp_id) WHERE comp_current = 1 ORDER BY account_id";  
$rsAccounts = mysql_query($query_rsAccounts, $DBconnection) or die(mysql_error());
//$row_rsAccounts = mysql_fetch_assoc($rsAccounts);
$totalRows_rsAccounts = mysql_num_rows($rsAccounts);

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = "SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, co.comp_name FROM classes AS c INNER JOIN competition AS co USING (comp_id) WHERE comp_current = 1";
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
//$row_rsClasses = mysql_fetch_assoc($rsClasses);
$totalRows_rsClasses = mysql_num_rows($rsClasses);

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = "SELECT re.reg_id FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a USING (account_id) WHERE comp_current = 1";
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
//$row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);

global $colname_rsContestants;
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsContestants = sprintf("SELECT co.contestant_id FROM contestants AS co INNER JOIN account AS ac USING(account_id) INNER JOIN clubregistration AS cl USING(account_id) INNER JOIN competition AS com USING(comp_id) WHERE comp_current = 1 ORDER BY contestant_id", GetSQLValueString($colname_rsContestants, "text"));
$rsContestants = mysql_query($query_rsContestants, $DBconnection) or die(mysql_error());
//$row_rsContestants = mysql_fetch_assoc($rsContestants);
$totalRows_rsContestants = mysql_num_rows($rsContestants);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Rapport: summering &ouml;ver antal anm&auml;lda klubbar, t&auml;vlingsklasser, anm&auml;lningar och t&auml;vlande"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<h3>Summeringsrapport</h3>
<p> H&auml;r &auml;r en summering av antal klubbar, t&auml;vlingsklasser, anm&auml;lningar och t&auml;vlande. Nedan finns l&auml;nkar till andra rapporter.</p>
<table width="200">
  <tr>
    <td>Antal klubbar:</td>
    <td><?php echo $totalRows_rsAccounts;?></td>
  </tr>
  <tr>
    <td>Antal t&auml;vlingsklasser:</td>
    <td><?php echo $totalRows_rsClasses;?></td>
  </tr>
  <tr>
    <td>Antal anm&auml;lda startande:</td>
    <td><?php echo $totalRows_rsRegistrations;?></td>
  </tr>
  <tr>
      <td>Antal registrerade t&auml;vlande fr&aring;n klubbar (inte n&ouml;dv&auml;ndigtvis anm&auml;lda &auml;n):</td>
    <td><?php echo $totalRows_rsContestants;?></td>
  </tr>
</table>
<p>&nbsp;</p>
  </div>
  <div class="story">
    <ul>
      <li><a href="Rep_Regs_Time_Class.php">Antal t&auml;vlande och ber&auml;knad tid per klass</a></li>
      <li><a href="Rep_Regs_Club.php">Vilka anm&auml;lningar av t&auml;vlande och coacher som gjorts samt kostnad f&ouml;r klubben</a></li>
      <li><a href="Rep_Cost_Club.php">Summering &ouml;ver antal anm&auml;lda och kostnad per klubb</a></li>
      <li><a href="Rep_Contestants_Club.php">Summering &ouml;ver antal t&auml;vlande per klubb vid aktuell t&auml;vling</a></li>
    </ul>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html><?php
mysql_free_result($rsAccounts);
mysql_free_result($rsClasses);
mysql_free_result($rsContestants);
mysql_free_result($rsRegistrations);
?>
