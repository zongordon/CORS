<?php
//Added log function of every login

//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Inloggad!";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Inloggad!, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
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

//Select the current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompActive = "SELECT comp_id FROM competition WHERE comp_current = 1";
$rsCompActive = mysql_query($query_rsCompActive, $DBconnection) or die(mysql_error());
$row_rsCompActive = mysql_fetch_assoc($rsCompActive);

//Insert login data in DB
        $insertSQL = sprintf("INSERT INTO loginlog (account_id, comp_id, login_timestamp, ip_address) VALUES (%s,%s,%s,%s)",
                       GetSQLValueString($colname_rsAccountId, "int"),
                       GetSQLValueString($row_rsCompActive['comp_id'], "int"),
                       GetSQLValueString($now, "date"),
                       GetSQLValueString($user_ip, "text"));
        mysql_select_db($database_DBconnection, $DBconnection);
        $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());

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