<?php
//Removed query for selecting data from current competition
//Moved meta description and keywords to header.php

if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$colname_rsAccountId = "";
if (isset($_SESSION['MM_AccountId'])) {
  $colname_rsAccountId = $_SESSION['MM_AccountId'];
}

//Catch anything wrong with query
try {
require('Connections/DBconnection.php');                
//Select all data regarding the selected account 
$query2 = "SELECT * FROM account WHERE account_id = :account_id";
$stmt_rsAccount = $DBconnection->prepare($query2);
$stmt_rsAccount->execute(array(':account_id' => $colname_rsAccountId));
$row_rsAccount = $stmt_rsAccount->fetch(PDO::FETCH_ASSOC);
$totalRows_rsAccount = $stmt_rsAccount->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
}       

$pagetitle="Kontouppgifter";
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
//Kill statement and DB connection
$stmt_rsAccount->closeCursor();
$DBconnection = null; 
?>