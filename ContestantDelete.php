<?php
//Moved $MM_authorizedUsers and $MM_donotCheckaccess from includes/functions.php
//Removed HTML header and use includes/functions.php instead
//Changed not to have admin account as default

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Ta bort deltagare - admin";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Ta bort deltagare, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

if ((isset($_GET['contestant_id'])) && ($_GET['contestant_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM contestants WHERE contestant_id=%s",
                       GetSQLValueString($_GET['contestant_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($deleteSQL, $DBconnection) or die(mysql_error());

  $deleteGoTo = "RegsHandleAll.php#registration_insert";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
//    $deleteGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Det finns ingen t&auml;vlande att ta bort!</h3>
<p><a href="RegsHandleAll.php">Tillbaka till Registrera t&auml;vlande</a></p>
  </div>
</div>
</body>
</html>