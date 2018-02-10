<?php 
//Moved meta description and keywords to header.php
//Granted access to all levels of registered users
if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
$MM_authorizedUsers = $_SESSION['MM_Level']; 
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Select clubs, for the active competition
require('Connections/DBconnection.php');           
$query_rsAccounts = "SELECT a.account_id FROM account AS a INNER JOIN clubregistration AS cl USING(account_id) INNER JOIN competition AS c USING(comp_id) WHERE comp_current = 1 ORDER BY account_id";
$stmt_rsAccounts = $DBconnection->query($query_rsAccounts);
$row_rsAccounts = $stmt_rsAccounts->fetch(PDO::FETCH_ASSOC);
$totalRows_rsAccounts = $stmt_rsAccounts->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Select classes, for the active competition
require('Connections/DBconnection.php');           
$query_rsClasses = "SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, co.comp_name FROM classes AS c INNER JOIN competition AS co USING (comp_id) WHERE comp_current = 1";
$stmt_rsClasses = $DBconnection->query($query_rsClasses);
$row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClasses = $stmt_rsClasses->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
//Catch anything wrong with query
try {
// Select registrations for the active competition
require('Connections/DBconnection.php');           
$query_rsRegistrations = "SELECT re.reg_id FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a USING (account_id) WHERE comp_current = 1";
$stmt_rsRegistrations = $DBconnection->query($query_rsRegistrations);
$row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC);
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

global $colname_rsContestants;
//Catch anything wrong with query
try {
// Select contestants for the active competition
require('Connections/DBconnection.php');           
$query_rsContestants = "SELECT co.contestant_id FROM contestants AS co INNER JOIN account AS ac USING(account_id) INNER JOIN clubregistration AS cl USING(account_id) INNER JOIN competition AS com USING(comp_id) WHERE comp_current = 1 ORDER BY contestant_id";
$stmt_rsContestants = $DBconnection->query($query_rsContestants);
$row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC);
$totalRows_rsContestants = $stmt_rsContestants->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
$pagetitle="Rapport: summering &ouml;ver antal anm&auml;lda klubbar, t&auml;vlingsklasser, anm&auml;lningar och t&auml;vlande";
// Includes Several code functions
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
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<h3>Summeringsrapport</h3>
<p> H&auml;r &auml;r en summering av antal klubbar, t&auml;vlingsklasser, anm&auml;lningar och t&auml;vlande. Nedan finns l&auml;nkar till andra rapporter.</p>
<table width="200">
  <tr>
    <td>Antal klubbar:</td>
    <td><?php echo $totalRows_rsAccounts;?></td>
  </tr>
  <tr>
    <td>Antal t&auml;vlingsklasser:</td>
    <td><?php echo $totalRows_rsClasses;?></td>
  </tr>
  <tr>
    <td>Antal anm&auml;lda startande:</td>
    <td><?php echo $totalRows_rsRegistrations;?></td>
  </tr>
  <tr>
      <td>Antal registrerade t&auml;vlande fr&aring;n klubbar (inte n&ouml;dv&auml;ndigtvis anm&auml;lda &auml;n):</td>
    <td><?php echo $totalRows_rsContestants;?></td>
  </tr>
</table>
<p>&nbsp;</p>
  </div>
  <div class="story">
    <ul>
      <li><a href="Rep_Regs_Time_Class.php">Antal t&auml;vlande och ber&auml;knad tid per klass</a></li>
      <li><a href="Rep_Regs_Club.php">Vilka anm&auml;lningar av t&auml;vlande och coacher som gjorts samt kostnad f&ouml;r klubben</a></li>
      <li><a href="Rep_Cost_Club.php">Summering &ouml;ver antal anm&auml;lda och kostnad per klubb</a></li>
      <li><a href="Rep_Contestants_Club.php">Summering &ouml;ver antal t&auml;vlande per klubb vid aktuell t&auml;vling</a></li>
    </ul>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html><?php
//Kill statements and DB connection
$stmt_rsAccounts->closeCursor();
$stmt_rsClasses->closeCursor();
$stmt_rsContestants->closeCursor();
$stmt_rsRegistrations->closeCursor();
$DBconnection = null;
?>
