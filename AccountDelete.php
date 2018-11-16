<?php 
//Removed kill DB as it's included in footer.php
ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

if (filter_input(INPUT_GET,'account_id') != "") {
    //Catch selected account_id
    $account_id = filter_input(INPUT_GET,'account_id');
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');            
    //Delete selected account
    $query = "DELETE FROM account WHERE account_id = :account_id";
    $stmt_rsDeleteAccount = $DBconnection->prepare($query);
    $stmt_rsDeleteAccount->bindValue(':account_id', $account_id, PDO::PARAM_INT);   
    $stmt_rsDeleteAccount->execute();
    }   
    catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
    }
    
  $deleteGoTo = "AccountsList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
//Kill statement and DB connection
$stmt_rsDeleteAccount->closeCursor();
}

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle="Ta bort anv&auml;ndarkonton - admin";
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
<h3>Det finns inget konto att ta bort!</h3>
<p><a href="AccountsList.php">Tillbaka till konton</a> </p>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush()?>