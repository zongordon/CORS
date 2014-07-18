<?php
//Adjusted to display page title

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ><head><?php $pagetitle="T&auml;vlingsresultat"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<?php require_once('Connections/DBconnection.php'); 

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$sorting = "class_discipline, class_gender, class_age, class_weight_length, contestant_result";
if (isset($_GET['sorting'])) {
  $sorting = $_GET['sorting'];
}
$colname_rsResult = "-1";

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsResult = "SELECT com.comp_current, com.comp_id, a.club_name, re.reg_id, re.contestant_result, co.contestant_name, re.contestant_height, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN clubregistration AS clu USING (club_reg_id) INNER JOIN account AS a USING (account_id) INNER JOIN competition AS com USING (comp_id) INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE com.comp_current = 1 AND re.contestant_result > 0 ORDER BY $sorting";
$rsResult = mysql_query($query_rsResult, $DBconnection) or die(mysql_error());
$row_rsResult = mysql_fetch_assoc($rsResult);
$totalRows_rsResult = mysql_num_rows($rsResult);
?>
<!-- start page -->
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature"><img height="199" width="300" alt="" src="img/rotating/rotate.php" /> 
<h3>Resultat</h3> 
        <p>H&auml;r finns alla t&auml;vlingsresultat fr&aring;n t&auml;vlingen!</p> 
  </div>        
<div class="story">
  <?php if ($totalRows_rsResult == 0) { // Show if recordset empty ?>
    <p>Det finns inget resultat att visa &auml;n!</p>
    <?php } ?>
  <?php if ($totalRows_rsResult > 0) { // Show if recordset not empty ?>  
<h3>Samtliga resultat</h3>
<p>Nedan finns samtliga resultat vid t&auml;vlingen. &Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
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
    <td><strong>Placering</strong></td>
    <td><strong>T&auml;vlingsklass</strong></td>
    </tr>
  <?php do { 
	if (($row_rsResult['contestant_result'] > 0) && ($row_rsResult['contestant_result'] < 4)) { ?>
    <tr>
      <td><?php echo $row_rsResult['club_name']; ?></td>
      <td><?php echo $row_rsResult['contestant_name']; ?></td>
      <td align="center"><?php echo $row_rsResult['contestant_result'].':a'; ?></td>
<td><?php echo $row_rsResult['class_discipline'].' | '.$row_rsResult['class_gender_category'].' | '.$row_rsResult['class_category'].' | '; 
      if ($row_rsResult['class_age'] == "") { 
          echo "";          
      } 
      if ($row_rsResult['class_age'] <> "") { 
          echo $row_rsResult['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsResult['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsResult['class_weight_length'] <> "-") {
         echo $row_rsResult['class_weight_length']; 
      }
      ?>
</td>
</tr>

<?php      
    }   
		} while ($row_rsResult = mysql_fetch_assoc($rsResult));
?>      
</table>
    <?php } // Show if recordset not empty ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsResult);
?>