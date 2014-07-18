<?php
//Changed from $pageAccessLevel = "1" to $MM_authorizedUsers = "1" and $MM_donotCheckaccess = "false" due to redirect loop

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

//Moved html head and several other code functions to includes/functions.php
$pagetitle="Ta bort t&auml;vlingsklass";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, ta bort tävlingsklasser, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

if ((isset($_GET['class_id'])) && ($_GET['class_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM classes WHERE class_id=%s",
                       GetSQLValueString($_GET['class_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($deleteSQL, $DBconnection) or die(mysql_error());

  $deleteGoTo = "ClassesList.php";
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
<h3>Det finns ingen t&auml;vlingsklass att ta bort!</h3>
<p><a href="ClassesList.php">Tillbaka till T&auml;vlingsklasser</a> </p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>