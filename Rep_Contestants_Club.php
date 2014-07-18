<?php 
//Adjusted to display page title

ob_start();

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
$query_rsContestants = "SELECT club_name, COUNT(reg_id) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE comp_current = 1 GROUP BY account_id ORDER BY club_name";
$rsContestants = mysql_query($query_rsContestants, $DBconnection) or die(mysql_error());
$row_rsContestants = mysql_fetch_assoc($rsContestants);
$totalRows_rsContestants= mysql_num_rows($rsContestants);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Rapport: antal t&auml;vlande som anm&auml;lts till aktuell t&auml;vling, per klubb"?>
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
<h3>Antal t&auml;vlande (som &auml;r anm&auml;lda till n&aring;gon klass) per klubb</h3>
<p>Rapporten visar antal t&auml;vlande (som anm&auml;lts till n&aring;gon t&auml;vlingsklass) vid aktuell t&auml;vling per klubb.</p>
<table width="40%" border="1">
    <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>Antal&nbsp;t&auml;vlande</strong></td>
        </tr>
    <?php do { ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_rsContestants['club_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsContestants['COUNT(reg_id)']; ?></td>
        </tr>
      <?php } while ($row_rsContestants = mysql_fetch_assoc($rsContestants));?>
</table>
      <p>&nbsp;</p>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
<p>&nbsp;</p>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsContestants);
?>