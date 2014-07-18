<?php
//Added function for confirmation before deletion for account

//Access level admin
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "true";

$pagetitle="Anv&auml;ndarkonton";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, visa användarkonton, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccounts = "SELECT * FROM account ORDER BY club_name ASC";
$rsAccounts = mysql_query($query_rsAccounts, $DBconnection) or die(mysql_error());
$row_rsAccounts = mysql_fetch_assoc($rsAccounts);
$totalRows_rsAccounts = mysql_num_rows($rsAccounts);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Befintliga konton</h3>
<?php if ($totalRows_rsAccounts > 0) { // Show if recordset not empty ?>
  <p>&Auml;ndra eller ta bort konton genom att klicka p&aring; respektive l&auml;nk.</p>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsAccounts == 0) { // Show if recordset empty ?>
  <p>Det finns inga konton att visa!</p>
  <?php } // Show if recordset empty ?>
    <?php if ($totalRows_rsAccounts > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1">
    <tr>
      <td><strong>Klubbnamn</strong></td>
      <td><strong>Kontaktnamn</strong></td>
      <td><strong>Anv&auml;ndarnamn</strong></td>
      <td><strong>E-post</strong></td>
      <td><strong>Telefon</strong></td>
      <td><strong>Aktivt</strong></td>
      <td><strong>&Auml;ndra</strong></td>
      <td nowrap="nowrap"><strong>Ta bort</strong></td>
    </tr>
    <?php do { ?>
    <tr>
      <td><?php echo $row_rsAccounts['club_name']; ?></td>
      <td><?php echo $row_rsAccounts['contact_name']; ?></td>
      <td><?php echo $row_rsAccounts['user_name']; ?></td>
      <td><?php echo $row_rsAccounts['contact_email']; ?></td>
      <td><?php echo $row_rsAccounts['contact_phone']; ?></td>
      <td valign="middle"><form id="activeaccount_form" name="activeaccount_form" method="post" action="">
          <label>
            <input name="active" type="checkbox" id="active" value="Aktiv" <?php if (!(strcmp($row_rsAccounts['active'],1))) {echo "checked=\"checked\"";} ?> />
          </label>
        </form></td>
      <td><a href="AccountUpdate.php?account_id=<?php echo $row_rsAccounts['account_id']; ?>">&Auml;ndra</a></td>
      <td><a href="#" onclick="return deleteAccount('<?php echo $row_rsAccounts['account_id']; ?>')">Ta bort</a></td>
    </tr>
      <?php } while ($row_rsAccounts = mysql_fetch_assoc($rsAccounts)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
  </div>
  <div class="story"></div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsAccounts);
?>