<?php
//Replaced "<td>" in header with "<th>"

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

$pagetitle="Lista anv&auml;ndarkonton - admin";
// Includes several code functions
include_once('includes/functions.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
      <div class="story">
<h3>Befintliga konton</h3>
<?php if ($totalRows_rsAccounts > 0) { // Show if recordset not empty ?>
  <p>&Auml;ndra eller ta bort konton genom att klicka p&aring; respektive l&auml;nk.</p>
<?php } // Show if recordset not empty 
        if ($totalRows_rsAccounts == 0) { // Show if recordset empty ?>
  <p>Det finns inga konton att visa!</p>
<?php   } // Show if recordset empty 
if ($totalRows_rsAccounts > 0) { // Show if recordset not empty ?>
  <table class="wide_tbl" border="1">
    <tr>
      <th>Klubbnamn</th>
      <th>Kontakt</th>
      <th>Anv&auml;ndarnamn</th>
      <th>E-post</th>
      <th>Telefon</th>
      <th>Aktivt</th>
      <th>&Auml;ndra</th>
      <th>Ta bort</th>
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
            <input name="active" type="checkbox" disabled="disabled" id="active" value="Aktiv" <?php if (!(strcmp($row_rsAccounts['active'],1))) {echo "checked=\"checked\"";} ?> />
          </label>
        </form></td>
      <td nowrap="nowrap"><a href="AccountUpdate.php?account_id=<?php echo $row_rsAccounts['account_id']; ?>">&Auml;ndra</a></td>
      <td><a href="#" onclick="return deleteAccount('<?php echo $row_rsAccounts['account_id']; ?>')">Ta bort</a></td>
    </tr>
    <?php }?>
  </table>
<?php 
} // Show if recordset not empty ?>
    </div>  
  </div>
</div>    
<?php 
//Kill statement
$stmt_rsAccounts->closeCursor();
include("includes/footer.php");
?>
</body>
</html>