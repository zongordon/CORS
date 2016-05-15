<?php
//Fixed bugg with links to elimination ladder, showing the same ladder regardless of link

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="T&auml;vlingsklasser"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, visa tävlingsklasser, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
<script language="JavaScript" type="text/javascript" src="includes/PopUp.js"></script>
</head>
<?php require_once('Connections/DBconnection.php'); ?>
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

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = "SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, com.comp_raffled FROM classes AS c INNER JOIN competition AS com USING (comp_id) WHERE comp_current = 1 ORDER BY  class_discipline, class_gender, class_age, class_weight_length, class_gender_category";
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$row_rsClasses = mysql_fetch_assoc($rsClasses);
$totalRows_rsClasses = mysql_num_rows($rsClasses);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
    <div class="feature">
  <?php if ($totalRows_rsClasses > 0) { // Show if recordset not empty ?>
    <h3>Befintliga t&auml;vlingsklasser</h3>
  <?php if ($row_rsClasses['comp_raffled'] == 0) {
    echo '<p>Se startlistan &ouml;ver t&auml;vlande genom att klicka p&aring; l&auml;nken. <strong>Obs! T&auml;vlingsstegarna visas efter sista anm&auml;lningsdagen och d&aring; lottningen &auml;r gjord!</strong></p>';     
    }?>
  <?php if ($row_rsClasses['comp_raffled'] == 1) {
    echo '<p>Se startlistan &ouml;ver t&auml;vlande eller hela t&auml;vlingsstegen genom att klicka p&aring; respektive l&auml;nk. <strong>Lottningen &auml;r nu avklarad!</strong></p>';     
    }?>    
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rsClasses == 0) { // Show if recordset empty ?>
    <p>Det finns inga t&auml;vlingsklasser att visa &auml;n!</p>
    <?php } // Show if recordset empty ?>
  <?php if ($totalRows_rsClasses > 0) { // Show if recordset not empty ?>
    <table width="100%" border="1">
      <tr>
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>Startlista</strong></td>
        <?php if ($row_rsClasses['comp_raffled'] == 1) {
        echo "<td><strong>T&auml;vlingsstege</strong></td>";
              } ?>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rsClasses['class_discipline']; ?></td>
          <td><?php echo $row_rsClasses['class_gender_category']; ?></td>
          <td><?php echo $row_rsClasses['class_category']; ?></td>
          <td><?php echo $row_rsClasses['class_age']; ?></td>
          <td><?php echo $row_rsClasses['class_weight_length']; ?></td>
          <td><a href="ClassContestants_loggedout.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">Startlista</a></td>
          <?php if ($row_rsClasses['comp_raffled'] == 1) {
                echo "<td><a href=javascript:MM_openBrWindow('ElimLadder.php?class_id=".$row_rsClasses['class_id']."','T&auml;vlingsstege','',1131,800,'true')>T&auml;vlingsstege</a></td>";
                } ?>          
        </tr>
        <?php } while ($row_rsClasses = mysql_fetch_assoc($rsClasses)); ?>
    </table>
    <?php 
      mysql_free_result($rsClasses);    
      } // Show if recordset not empty ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>