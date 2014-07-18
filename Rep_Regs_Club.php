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
// Select information regarding active accounts
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccounts = "SELECT account_id, club_name, active FROM account WHERE active = 1 ORDER BY club_name ASC";
$rsAccounts = mysql_query($query_rsAccounts, $DBconnection) or die(mysql_error());
$row_rsAccounts = mysql_fetch_assoc($rsAccounts);

$colname_rsSelectedClub = "";
if (isset($_POST['account_id'])) {
  $colname_rsSelectedClub = $_POST['account_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsSelectedClub = sprintf("SELECT account_id, club_name, active FROM account WHERE account_id = %s", GetSQLValueString($colname_rsSelectedClub, "int"));
$rsSelectedClub = mysql_query($query_rsSelectedClub, $DBconnection) or die(mysql_error());
$row_rsSelectedClub = mysql_fetch_assoc($rsSelectedClub);

// Select information regarding selected account
$colname_rsSelectedClub = "";
if (isset($_POST['account_id'])) {
  $colname_rsSelectedClub = $_POST['account_id'];
}
//Select data from each club in active competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCost = sprintf("SELECT coach_names, COUNT(reg_id), SUM(class_fee) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE account_id = %s AND comp_current = 1", GetSQLValueString($colname_rsSelectedClub, "int"));
$rsCost = mysql_query($query_rsCost, $DBconnection) or die(mysql_error());
$row_rsCost = mysql_fetch_assoc($rsCost);

// Select contestants from selected account and class data from selected account and current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = sprintf("SELECT a.club_name, re.reg_id, re.contestant_height, co.contestant_name, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a USING (account_id) WHERE account_id = %s AND comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, co.contestant_name", GetSQLValueString($colname_rsSelectedClub, "int"));
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
$row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Rapport: samtliga anm&auml;lningar, coacher och kostnad per klubb"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, rapport för samtliga anmälningar, coacher och kostnad per klubb, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
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
<h3>V&auml;lj klubb</h3>
<p>V&auml;l klubb och klicka p&aring; V&auml;lj! Rapporten visar vilka anm&auml;lningar som gjorts till aktuell t&auml;vling, vilka coacher som anm&auml;lts och den sammanlagda kostnaden f&ouml;r vald klubb.</p>
      <form id="SelectClub" name="SelectClub" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="200" border="0">
          <tr>
            <td valign="middle">Klubb</td>
            <td><label>
              <select name="account_id" id="account_id">
                <?php
do {  
?>
                <option value="<?php echo $row_rsAccounts['account_id']?>"<?php if (!(strcmp($row_rsAccounts['account_id'], $row_rsSelectedClub['account_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAccounts['club_name']?></option>
                <?php
} while ($row_rsAccounts = mysql_fetch_assoc($rsAccounts));
  $rows = mysql_num_rows($rsAccounts);
  if($rows > 0) {
      mysql_data_seek($rsAccounts, 0);
	  $row_rsAccounts = mysql_fetch_assoc($rsAccounts);
  }
?>
              </select>
            </label></td>
            <td><input type="submit" name="submit" id="submit" value="V&auml;lj" /></td>
          </tr>
        </table>
      </form>
  <?php if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
    <p>Det finns inget resultat att visa!</p>
  <?php } ?>
  <?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>  
      <table width="80%" border="1">
        <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>T&auml;vlande</strong></td>
          <td><strong>L&auml;ngd (eventuellt)</strong></td>
          <td><strong>T&auml;vlingsklass</strong></td>
        </tr>
        <?php do { ?>
        <tr>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['club_name']; ?></td>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['contestant_name']; ?></td>
          <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
          <td> <?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
      if ($row_rsRegistrations['class_age'] == "") { 
          echo "";          
      } 
      if ($row_rsRegistrations['class_age'] <> "") { 
          echo $row_rsRegistrations['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsRegistrations['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsRegistrations['class_weight_length'] <> "-") {
         echo $row_rsRegistrations['class_weight_length']; 
      }
      ?></td>
        </tr>
        <?php } while ($row_rsRegistrations = mysql_fetch_assoc($rsRegistrations)); ?>
        <tr>
          <td valign="top"><strong>Antal&nbsp;anm&auml;lningar</strong>:<br/><?php echo $totalRows_rsRegistrations;?></td>
          <td valign="top"><strong>Total kostnad</strong>:<br/><?php echo $row_rsCost['SUM(class_fee)'].' kr';?></td>
          <td valign="top" colspan="2"><strong>Coacher</strong>:<br/><?php echo $row_rsCost['coach_names'];?></td>
        </tr>        
    </table>
    <?php
  }
  ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsAccounts);
mysql_free_result($rsRegistrations);
mysql_free_result($rsCost);
mysql_free_result($rsSelectedClub);
?>