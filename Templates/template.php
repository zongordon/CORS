<?php require_once('../Connections/DBconnection.php'); ?>
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

$colname_rsClasses = "-1";
if (isset($_GET['class_id'])) {
  $colname_rsClasses = $_GET['class_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = sprintf("SELECT * FROM classes WHERE class_id = %s", GetSQLValueString($colname_rsClasses, "int"));
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$row_rsClasses = mysql_fetch_assoc($rsClasses);
$totalRows_rsClasses = mysql_num_rows($rsClasses);
 ob_start();?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Tuna Karate Cup 2010"?>
<meta http-equiv="Content-Type" content="; charset=" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />

</head>
<?php include("includes/header.php"); ?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
	<!-- div id="breadCrumb"><a href="http://tunacup.karateklubben.com/" target="_self">tunacup.karateklubben.com</a>&nbsp;></div-->
		<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>V&auml;lkomna till Tuna Karate Cup 2009 </h3>
      <p> L&ouml;rdagen den 24 januari 10.00 i Eskilstuna Sporthall h&ouml;lls f&ouml;r allra f&ouml;rsta g&aring;ngen Tuna Karate Cup. En t&auml;vling &ouml;ppen f&ouml;r samtliga karatestilar.  </p>
  </div>
  <div class="story">
    <h3>Sporthallen i Eskilstuna</h3>
    <table width="100%">
      <tr>
        <td><form action="<?php echo $editFormAction; ?>" method="post" enctype="application/x-www-form-urlencoded" name="form1" id="form1">
          <label>
            <select name="class_id" id="class_id">
              <?php
do {  
?>
              <option value="<?php echo $row_rsClasses['class_id']?>"<?php if (!(strcmp($row_rsClasses['class_id'], $colname_rsClasses))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClasses['class_id']?></option>
              <?php
} while ($row_rsClasses = mysql_fetch_assoc($rsClasses));
  $rows = mysql_num_rows($rsClasses);
  if($rows > 0) {
      mysql_data_seek($rsClasses, 0);
	  $row_rsClasses = mysql_fetch_assoc($rsClasses);
  }
?>
            </select>
          </label>
        </form></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($rsClasses);
?>
