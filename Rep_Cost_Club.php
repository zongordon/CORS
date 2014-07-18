<?php
//Removed nowrap for coach column and increased table width to 100%

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

//Select data from each club in active competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCost = "SELECT club_name, coach_names, COUNT(reg_id), SUM(class_fee) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE comp_current = 1 GROUP BY account_id ORDER BY club_name";
$rsCost = mysql_query($query_rsCost, $DBconnection) or die(mysql_error());
$row_rsCost = mysql_fetch_assoc($rsCost);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Rapport: antal anm&auml;lningar och kostnad per klubb"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, rapport med antal anmälningar, coachnamn och kostnad per klubb, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
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
<h3>Kostnad och antal anm&auml;lningar per klubb</h3>
<p>Rapporten visar hur m&aring;nga anm&auml;lningar (kata och/eller kumite) som gjorts till aktuell t&auml;vling och den sammanlagda kostnaden per klubb.</p>
<table width="100%" border="1">
    <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>Antal&nbsp;anm&auml;lningar</strong></td>
          <td><strong>Coacher</strong></td>
          <td><strong>Kostnad</strong></td>
        </tr>
    <?php do { ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_rsCost['club_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsCost['COUNT(reg_id)']; ?></td>
        <td><?php echo $row_rsCost['coach_names']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsCost['SUM(class_fee)'].' kr'; ?></td>
      </tr>
      <?php } while ($row_rsCost = mysql_fetch_assoc($rsCost)); ?>
</table>
      <p>&nbsp;</p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsCost);
?>
