<?php
//Added ob_start(); and ob_end_flush();
ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Ta bort t&auml;vling";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, ta bort tävling, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

if ((isset($_GET['comp_id'])) && ($_GET['comp_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM competition WHERE comp_id=%s",
                       GetSQLValueString($_GET['comp_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($deleteSQL, $DBconnection) or die(mysql_error());

  $deleteGoTo = "CompetitionList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
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
<h3>Det finns ingen t&auml;vling att ta bort!</h3>
<p><a href="CompetitionList.php">Tillbaka till T&auml;vlingar</a> </p>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>