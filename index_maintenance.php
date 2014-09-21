<?php 
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
require_once('Connections/DBconnection.php');
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompetition = "SELECT comp_raffled FROM competition WHERE comp_current = 1";
$rsCompetition = mysql_query($query_rsCompetition, $DBconnection) or die(mysql_error());
$row_rsCompetition = mysql_fetch_assoc($rsCompetition);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Sajten &auml;r nere f&ouml;r underh&aring;ll!"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="hemsida tuna karate cup, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" /></head>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>