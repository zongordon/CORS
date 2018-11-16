<?php 
//Removed kill DB as it's included in footer.php

if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
$MM_authorizedUsers = $_SESSION['MM_Level']; 
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Select number of registrations for each club, for the active competition
require('Connections/DBconnection.php');           
$query_rsContestants = "SELECT club_name, COUNT(reg_id) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE comp_current = 1 GROUP BY account_id ORDER BY club_name";
$stmt_rsContestants = $DBconnection->query($query_rsContestants);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: antal t&auml;vlande som anm&auml;lts till aktuell t&auml;vling, per klubb";
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
    <p><a href="javascript:history.go(-1);">Klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
  </div>
</div>
<?php 
//Kill statement
$stmt_rsContestants->closeCursor();
include("includes/footer.php");
?>
</body>
</html>