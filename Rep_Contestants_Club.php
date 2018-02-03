<?php 
//Adapted code to PHP 7 (PDO) and added minor error handling. 
//Added header.php, restrict_access.php and news_sponsors_nav.php as includes.
//Added check of access level

ob_start();

if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Select number of registrations for each club, for the active competition
require('Connections/DBconnection.php');           
$query_rsContestants = "SELECT club_name, COUNT(reg_id) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE comp_current = 1 GROUP BY account_id ORDER BY club_name";
$stmt_rsContestants = $DBconnection->query($query_rsContestants);
$row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC); 
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: antal t&auml;vlande som anm&auml;lts till aktuell t&auml;vling, per klubb";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Munktellarena.";
$pagekeywords="tuna karate cup, rapport om antal tävlande per klubb, karate, eskilstuna, Munktellarena, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
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
<h3>Antal t&auml;vlande (som &auml;r anm&auml;lda till n&aring;gon klass) per klubb</h3>
<p>Rapporten visar antal t&auml;vlande (som anm&auml;lts till n&aring;gon t&auml;vlingsklass) vid aktuell t&auml;vling per klubb.</p>
<table width="40%" border="1">
    <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>Antal&nbsp;t&auml;vlande</strong></td>
        </tr>
    <?php while($row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC)) { ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_rsContestants['club_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsContestants['COUNT(reg_id)']; ?></td>
        </tr>
    <?php } ?>
</table>
      <p>&nbsp;</p>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
<p>&nbsp;</p>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statements and DB connection
$stmt_rsContestants->closeCursor();
$DBconnection = null;
?>