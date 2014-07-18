<?php
//Removed two out of three identical queries and one unnecessary
//Changed from using $_SESSION['MM_Username'] to $_SESSION['MM_AccountId'] to prevent problems if changing user_name
//Changed from query name $query_rsAccountId to $query_rsAccount and $rsAccountId to $rsAccount 

//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Kontouppgifter";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, visa uppgifterna i valt användarkonto, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$colname_rsAccountId = "";
if (isset($_SESSION['MM_AccountId'])) {
  $colname_rsAccountId = $_SESSION['MM_AccountId'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccount = sprintf("SELECT * FROM account WHERE account_id = %s", GetSQLValueString($colname_rsAccountId, "int"));
$rsAccount = mysql_query($query_rsAccount, $DBconnection) or die(mysql_error());
$row_rsAccount = mysql_fetch_assoc($rsAccount);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
  <div class="feature">
<h3>Informationen om kontot</h3>
      <p>Detta &auml;r informationen som har sparats om kontot.</p>
      <form id="account" name="account" method="post">
        <table width="400" border="0">
          <tr>
            <td>Klubbens namn</td>
            <td><?php echo $row_rsAccount['club_name']; ?></td>
          </tr>
          <tr>
            <td>Kontaktperson</td>
            <td valign="top"><?php echo $row_rsAccount['contact_name']; ?></td>
          </tr>
          <tr>
            <td>E-post</td>
            <td valign="top"><?php echo $row_rsAccount['contact_email']; ?></td>
          </tr>
          <tr>
            <td>Telefon</td>
            <td><?php echo $row_rsAccount['contact_phone']; ?></td>
          </tr>
          <tr>
            <td>Anv&auml;ndarnamn</td>
            <td><?php echo $row_rsAccount['user_name']; ?></td>
          </tr>
        </table>
    </form>
  </div>
  <div class="story">
    <h3><a href="AccountUpdate_reg.php">Om du inte &auml;r n&ouml;jd, s&aring; &auml;ndra h&auml;r!</a></h3>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsAccount);
?>