<?php
//Changed from using $_SESSION['MM_Username'] to $_SESSION['MM_Accountid'] to prevent problems if changing user_name
//Changed from query name $query_rsAccountId to $query_rsAccount  
//Removed HTML header and use includes/functions.php instead

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Inloggad - admin";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, inloggad som administratör, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$colname_rsAccountId = "";
if (isset($_SESSION['MM_AccountId'])) {
  $colname_rsAccountId = $_SESSION['MM_AccountId'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccount = sprintf("SELECT contact_name, access_level FROM account WHERE account_id = %s", GetSQLValueString($colname_rsAccountId, "int"));
$rsAccount = mysql_query($query_rsAccount, $DBconnection) or die(mysql_error());
$row_rsAccount = mysql_fetch_assoc($rsAccount);

//Creating Session variable
$_SESSION['MM_Level'] = $row_rsAccount['access_level'];
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
  <div class="feature">
<h3>V&auml;lkommen <?php echo $row_rsAccount['contact_name']; ?>!</h3>
<p> Du &auml;r nu inloggad och kan l&auml;gga in nya anm&auml;lningar eller kontrollera redan inlagda.</p>
  </div>
  <div class="story">
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsAccount);
?>