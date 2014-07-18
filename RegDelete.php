<?php
//Removed search for the registration to delete

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Ta bort anm&auml;lan";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Ta bort anmälan, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

//Select the account_id (forwarding to next page) and then deleted the selected registration
if ((isset($_GET['reg_id'])) && ($_GET['reg_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM registration WHERE reg_id=%s",
                       GetSQLValueString($_GET['reg_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($deleteSQL, $DBconnection) or die(mysql_error());    
  
  $deleteGoTo = "RegsHandleAll.php#registration_delete";

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
<h3>Det finns ingen anm&auml;lan att ta bort!</h3>
<p><a href="RegInsert_reg.php">Tillbaka till Registrera t&auml;vlande</a></p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>