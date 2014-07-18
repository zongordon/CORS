<?php
//Added ob_start(); and ob_end_flush()

ob_start();
//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Ta bort anm&auml;lan";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Ta bort anmälan, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

if ((isset($_GET['reg_id'])) && ($_GET['reg_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM registration WHERE reg_id=%s",
                       GetSQLValueString($_GET['reg_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($deleteSQL, $DBconnection) or die(mysql_error());

  $deleteGoTo = "RegInsert_reg.php#registration_delete";
/*  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
*/  }
  header(sprintf("Location: %s", $deleteGoTo));
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
  <div class="feature">
<h3>Det finns ingen anm&auml;lan att ta bort!</h3>
<p><a href="RegInsert_reg.php">Tillbaka till Registrera t&auml;vlande</a></p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>