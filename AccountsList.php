<?php
//Removed kill DB as it's included in footer.php

//Access level admin
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "true";

//Catch anything wrong with query
try {
require('Connections/DBconnection.php');            
//Select data regarding all accounts
$query = "SELECT * FROM account ORDER BY club_name ASC";
$stmt_rsAccounts = $DBconnection->query($query);
$totalRows_rsAccounts = $stmt_rsAccounts->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
}   

$pagetitle="Anv&auml;ndarkonton";
// Includes several code functions
include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Befintliga konton</h3>
<?php if ($totalRows_rsAccounts > 0) { // Show if recordset not empty ?>
  <p>&Auml;ndra eller ta bort konton genom att klicka p&aring; respektive l&auml;nk.</p>
<?php } // Show if recordset not empty 
        if ($totalRows_rsAccounts == 0) { // Show if recordset empty ?>
  <p>Det finns inga konton att visa!</p>
<?php   } // Show if recordset empty 
if ($totalRows_rsAccounts > 0) { // Show if recordset not empty ?>
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
    <?php while($row_rsAccounts = $stmt_rsAccounts->fetch(PDO::FETCH_ASSOC)) {;?>
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
    <?php }?>
  </table>
<?php 
} // Show if recordset not empty ?>
  </div>
  <div class="story"></div>
</div>
<?php 
//Kill statement
$stmt_rsAccounts->closeCursor();
include("includes/footer.php");
?>
</body>
</html>