<?php
//Removed kill DB as it's included in footer.php

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Inloggad - admin";
// Includes several code functions
include_once('includes/functions.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');

//Fetch sessions account_id
$colname_rsAccountId = "";
if (isset($_SESSION['MM_AccountId'])) {
  $colname_rsAccountId = $_SESSION['MM_AccountId'];
}

//Catch anything wrong with query 
try {
require('Connections/DBconnection.php');        
//Select user data based on account_id 
$query1 = "SELECT contact_name, access_level FROM account WHERE account_id = :account_id";
$stmt_rsAccount = $DBconnection->prepare($query1);
$stmt_rsAccount->execute(array(':account_id' => $colname_rsAccountId));
$row_rsAccount = $stmt_rsAccount->fetch(PDO::FETCH_ASSOC);
}   
catch(PDOException $ex) {
echo "An Error occured: ".$ex->getMessage();
}  

//Catch anything wrong with query
try {
//Select the current competition
$query2 = "SELECT comp_id FROM competition WHERE comp_current = 1";
$stmt_rsCompActive = $DBconnection->query($query2);
$row_rsCompActive = $stmt_rsCompActive->fetch(PDO::FETCH_ASSOC);
$colname_rsCompActive = $row_rsCompActive['comp_id'];
    }   
    catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
    }   

//Catch anything wrong with query 
try {
//Insert login data in DB 
$query3 = "INSERT INTO loginlog (account_id, comp_id, login_timestamp, ip_address) VALUES (:account_id, :comp_id, NOW(), :ip_address)";
$stmt = $DBconnection->prepare($query3);
$stmt->bindValue(':account_id', $colname_rsAccountId, PDO::PARAM_INT);
$stmt->bindValue(':comp_id', $colname_rsCompActive, PDO::PARAM_INT);
$stmt->bindValue(':ip_address', $user_ip, PDO::PARAM_STR);
$stmt->execute();
}   
catch(PDOException $ex) {
echo "An Error occured: ".$ex->getMessage();
}      
        
//Creating Session variable
$_SESSION['MM_Level'] = $row_rsAccount['access_level'];

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
<h3>V&auml;lkommen <?php echo $row_rsAccount['contact_name']; ?>!</h3>
<p> Du &auml;r nu inloggad och kan l&auml;gga in nya anm&auml;lningar eller kontrollera redan inlagda.</p>
  </div>
  <div class="story">
  </div>
</div>
<?php 
//Kill statement and DB connection
$stmt->closeCursor();
include_once("includes/footer.php");?>
</body>
</html>