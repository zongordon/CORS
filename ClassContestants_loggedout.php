<?php
//Adjusted to display contestant start number

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="T&auml;vlande i klassen"?>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, visa tävlande i respektive klass, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" /></head>
<?php require_once('Connections/DBconnection.php'); ?>
<?php
// Adjust strings from input forms to sql queries
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
// Select the contestants and their information for the selected class
$colname_rsRegistrations = "";
if (isset($_GET['class_id'])) {
  $colname_rsRegistrations = $_GET['class_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = sprintf("SELECT a.club_name, re.reg_id, re.contestant_height, re.contestant_startnumber, co.contestant_name, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = %s ORDER BY club_startorder, reg_id", GetSQLValueString($colname_rsRegistrations, "int"));
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
$row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
    <?php if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
  <h3>Det finns ingen t&auml;vlande i klassen!</h3>
  <p><a href="ClassesList_loggedout.php">Tillbaka till T&auml;vlingsklasser</a></p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>  
  <h3>
<?php 
echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'];
if ($row_rsRegistrations['class_age'] == "") { echo ""; } 
if ($row_rsRegistrations['class_age'] <> "") { 
    echo ' | '.$row_rsRegistrations['class_age'].' &aring;r'.'  ';     
}
if ($row_rsRegistrations['class_weight_length'] == "") { 
    echo "";     
} 
if ($row_rsRegistrations['class_weight_length'] <> "") { 
echo ' | '.$row_rsRegistrations['class_weight_length'];
}
?></h3>
  <table width="80%" border="1">
    <tr>
      <td><strong>Startnr.</strong></td>        
      <td><strong>Klubb</strong></td>
      <td><strong>T&auml;vlande</strong></td>
      <td><strong>L&auml;ngd (eventuellt)</strong></td>
      </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>          
        <td><?php echo $row_rsRegistrations['club_name']; ?></td>
        <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
        <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
</tr>
      <?php } while ($row_rsRegistrations = mysql_fetch_assoc($rsRegistrations)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsRegistrations);
?>