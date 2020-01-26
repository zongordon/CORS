<?php 
//Excluded teams when counting contestants for the active competition

if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
$MM_authorizedUsers = $_SESSION['MM_Level']; 
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Count clubs with registered contestants, for the active competition
require('Connections/DBconnection.php');           
$query_rsAccounts = "SELECT COUNT(DISTINCT account_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) "
        . "INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE comp_current = 1";
$stmt_rsAccounts = $DBconnection->query($query_rsAccounts);
$row_rsAccounts = $stmt_rsAccounts->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Count classes with registered contestants, for the active competition
$query_rsClasses = "SELECT COUNT(DISTINCT class_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) "
        . "INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE comp_current = 1";
$stmt_rsClasses = $DBconnection->query($query_rsClasses);
$row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
//Catch anything wrong with query
try {
// Count registrations for the active competition
$query_rsRegistrations = "SELECT COUNT(reg_id) as total FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE comp_current = 1";
$stmt_rsRegistrations = $DBconnection->query($query_rsRegistrations);
$row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Count potential contestants for the active competition  
$query_rsMembers = "SELECT COUNT(DISTINCT contestant_id) as total FROM contestants AS co INNER JOIN account AS ac "
        . "USING(account_id) INNER JOIN clubregistration AS cl USING(account_id) INNER JOIN competition AS com USING(comp_id) "
        . "WHERE comp_current = 1";
$stmt_rsMembers = $DBconnection->query($query_rsMembers);
$row_rsMembers = $stmt_rsMembers->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
//Catch anything wrong with query
try {
// Count contestants for the active competition, excluding teams
$query_rsContestants = "SELECT COUNT(DISTINCT contestant_id) as total FROM registration AS re INNER JOIN classes AS cl "
        . "USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com "
        . "USING (comp_id) INNER JOIN account as a USING (account_id) WHERE comp_current = 1 AND contestant_team = 0";
$stmt_rsContestants = $DBconnection->query($query_rsContestants);
$row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Count contestants in Kata in the active competition
$query_rsKata = "SELECT COUNT(reg_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE class_discipline = 'Kata' AND comp_current = 1";
$stmt_rsKata = $DBconnection->query($query_rsKata);
$row_rsKata = $stmt_rsKata->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Count adult contestants in Kata in the active competition
$query_rsKataAdult= "SELECT COUNT(reg_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE class_discipline = 'Kata' AND class_category <> 'Barn' AND comp_current = 1";
$stmt_rsKataAdult = $DBconnection->query($query_rsKataAdult);
$row_rsKataAdult = $stmt_rsKataAdult->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
 
//Catch anything wrong with query
try {
// Count children contestants in Kata in the active competition
$query_rsKataChildren = "SELECT COUNT(reg_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE class_discipline = 'Kata' AND class_category = 'Barn' AND comp_current = 1";
$stmt_rsKataChildren = $DBconnection->query($query_rsKataChildren);
$row_rsKataChildren = $stmt_rsKataChildren->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
     
//Catch anything wrong with query
try {
// Count contestants in Kumite in the active competition
$query_rsKumite = "SELECT COUNT(reg_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE class_discipline = 'Kumite' AND comp_current = 1";
$stmt_rsKumite = $DBconnection->query($query_rsKumite);
$row_rsKumite = $stmt_rsKumite->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Count adult contestants in Kumite in the active competition
$query_rsKumiteAdult= "SELECT COUNT(reg_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE class_discipline = 'Kumite' AND class_category <> 'Barn' AND comp_current = 1";
$stmt_rsKumiteAdult = $DBconnection->query($query_rsKumiteAdult);
$row_rsKumiteAdult = $stmt_rsKumiteAdult->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
      
//Catch anything wrong with query
try {
// Count children contestants in Kumite in the active competition
$query_rsKumiteChildren = "SELECT COUNT(reg_id) as total FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN "
        . "contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a "
        . "USING (account_id) WHERE class_discipline = 'Kumite' AND class_category = 'Barn' AND comp_current = 1";
$stmt_rsKumiteChildren = $DBconnection->query($query_rsKumiteChildren);
$row_rsKumiteChildren = $stmt_rsKumiteChildren->fetch(PDO::FETCH_ASSOC);
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
<p> H&auml;r &auml;r en summering av utvalda data. Nedan finns l&auml;nkar till andra rapporter.</p>
<table width="320">
  <tr>
    <td>Antal klubbar med anm&auml;lda deltagare:</td>
    <td><?php echo $row_rsAccounts['total'];?></td>
  </tr>
  <tr>
    <td>Antal t&auml;vlingsklasser med anm&auml;lda deltagare:</td>
    <td><?php echo $row_rsClasses['total'];?></td>
  </tr>
  <tr>
    <td>Antal deltagare anm&auml;lda i n&aring;gon klass (starter):</td>
    <td><?php echo $row_rsRegistrations['total'];?></td>
  </tr>
  <tr>
      <td>Antal registrerade medlemmar fr&aring;n klubbar <br>(inte n&ouml;dv&auml;ndigtvis anm&auml;lda &auml;n):</td>
    <td><?php echo $row_rsMembers['total'];?></td>
  </tr>
  <tr>
    <td>Antal anm&auml;lda deltagare:</td>
    <td><?php echo $row_rsContestants['total'];?></td>
  </tr>
  <tr>
    <td>Antal starter i kata, totalt:</td>
    <td><?php echo $row_rsKata['total'];?></td>
  </tr>  
  <tr>
    <td>Antal starter i kata, vuxna (>13 &aring;r)):</td>
    <td><?php echo $row_rsKataAdult['total'];?></td>
  </tr>  
  <tr>
    <td>Antal starter i kata, barn:</td>
    <td><?php echo $row_rsKataChildren['total'];?></td>
  </tr>  
  <tr>
    <td>Antal starter i kumite, totalt:</td>
    <td><?php echo $row_rsKumite['total'];?></td>
  </tr>  
  <tr>
    <td>Antal starter i kumite, vuxna (>13 &aring;r):</td>
    <td><?php echo $row_rsKumiteAdult['total'];?></td>
  </tr>  
  <tr>
    <td>Antal starter i kumite, barn:</td>
    <td><?php echo $row_rsKumiteChildren['total'];?></td>
  </tr>  
</table>
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
<?php 
//Kill statements
$stmt_rsAccounts->closeCursor();
$stmt_rsClasses->closeCursor();
$stmt_rsRegistrations->closeCursor();
$stmt_rsMembers->closeCursor();
$stmt_rsContestants->closeCursor();
$stmt_rsKata->closeCursor();
$stmt_rsKataChildren->closeCursor();
$stmt_rsKumite->closeCursor();
$stmt_rsKumiteChildren->closeCursor();
include("includes/footer.php");
?>
</body>
</html>