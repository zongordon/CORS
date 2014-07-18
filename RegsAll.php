<?php
//Adjusted to display page title

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ><head><?php $pagetitle="Samtliga t&auml;vlande"?>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<?php require_once('Connections/DBconnection.php');
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
// Select all registered contestants for the current competition
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$colname_rsRegistrations = "-1";
$sorting = "class_discipline, class_gender, class_age, class_weight_length, club_startorder, reg_id";
if (isset($_GET['sorting'])) {
  $sorting = $_GET['sorting'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = sprintf("SELECT com.comp_current, com.comp_id, a.club_name, re.reg_id, re.contestant_result, co.contestant_name, re.contestant_height, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN clubregistration AS clu USING (club_reg_id) INNER JOIN account AS a USING (account_id) INNER JOIN competition AS com USING (comp_id) INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE com.comp_current = 1 ORDER BY $sorting", GetSQLValueString($colname_rsRegistrations, "int"));
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
$row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);
?>
<!-- start page -->
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
  <?php if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
    <p>Det finns inga startlistor att visa &auml;n!</p>
    <?php } // Show if recordset empty ?>
  <?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>  
<h3>Samtliga anm&auml;lda till start</h3>
<p>Nedan finns samtliga nuvarande anm&auml;lningar till t&auml;vlingen. &Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
<!-- Sort the table by name of the club, competition class or contestant name!-->
<form action="<?php echo $editFormAction; ?>" method="GET" enctype="application/x-www-form-urlencoded" name="SelectSorting" id="SelectSorting">
  <table width="200" border="0">
    <tr>
      <td valign="middle">Sortering</td>
      <td><label>
        <select name="sorting" id="sorting">
      <option value="club_name, class_discipline, class_gender, class_age, class_weight_length, contestant_name"<?php if (!(strcmp($sorting, "club_name, class_discipline, class_gender, class_age, class_weight_length, contestant_name"))) {echo "selected=\"selected\"";} ?>>Klubb</option>
      <option value="class_discipline, class_gender, class_age, class_weight_length, club_startorder, reg_id"<?php if (!(strcmp($sorting, "class_discipline, class_gender, class_age, class_weight_length, club_startorder, reg_id"))) {echo "selected=\"selected\"";} ?>>T&auml;vlingsklass</option>
      <option value="contestant_name, club_name"<?php if (!(strcmp($sorting, "contestant_name, club_name"))) {echo "selected=\"selected\"";} ?>>T&auml;vlande</option>
        </select>
      </label></td>
      <td><input type="submit" name="submit" id="submit" value="Sortera" /></td>
    </tr>
  </table>
</form>
<table width="100%" border="1">
  <tr>
    <td><strong>Klubb</strong></td>
    <td><strong>T&auml;vlande</strong></td>
    <td><strong>L&auml;ngd (eventuellt)</strong></td>
    <td><strong>T&auml;vlingsklass</strong></td>
    </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsRegistrations['club_name']; ?></td>
      <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
      <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
      <td><?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
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
</table>
    <?php } // Show if recordset not empty ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>