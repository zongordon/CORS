<?php 
//Adjusted to display page title
ob_start();
 
if (!isset($_SESSION)) {
  session_start();
}
require_once('Connections/DBconnection.php'); ?>
<?php
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
?> 
<?php
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = sprintf("SELECT cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age, COUNT(class_id) FROM classes AS cl INNER JOIN registration AS re USING (class_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1 GROUP BY class_id ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length", GetSQLValueString($colname_rsRegistrations, "int"));
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
$row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Rapport: antal anm&auml;lningar per t&auml;vlingsklass"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />

</head>
<?php include("includes/header.php"); ?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
		<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Antal anm&auml;lningar per t&auml;vlingsklass</h3>
<p>Rapporten visar hur m&aring;nga anm&auml;lningar som gjorts till aktuell t&auml;vling per t&auml;vlingsklass.</p>
<table width="80%" border="1">
    <tr>
      <td><strong>T&auml;vlingsklass</strong></td>
          <td><strong>Antal t&auml;vlande</strong></td>
          <td><strong>Totalt ber&auml;knad tid (min)</strong></td>
      </tr>
        <?php do { ?>
        <tr>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_weight_length'].' | '.$row_rsRegistrations['class_age'].' &aring;r'?></td>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['COUNT(class_id)']; ?></td>
          <td nowrap="nowrap">
<?php 
if ($row_rsRegistrations['class_discipline'] == "Kata") {
$time_class = 3;
}
if (($row_rsRegistrations['class_discipline'] == "Kumite") && ($row_rsRegistrations['class_category'] == "Barn")) {
$time_class = 4;
}
if (($row_rsRegistrations['class_discipline'] == "Kumite") && ($row_rsRegistrations['class_category'] <> "Barn")) {
$time_class = 5;
}
echo (($row_rsRegistrations['COUNT(class_id)']-1) * $time_class); ?></td>
        </tr>
      <?php } while ($row_rsRegistrations = mysql_fetch_assoc($rsRegistrations)); ?>        
    </table>
      <p>&nbsp;</p>
  </div>
  <div class="story">
    <p>&nbsp;</p>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsRegistrations);
?>