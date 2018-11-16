<?php //Removed kill DB as it's included in footer.php

if (!isset($_SESSION)) {
  session_start();
}
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Select registrations for each club and all competitions
require('Connections/DBconnection.php');           
$query_rsContestants = "SELECT club_name, contact_email, contestant_name FROM contestants INNER JOIN account USING(account_id) WHERE active = 1 ORDER BY club_name, contact_email, contestant_name";
$stmt_rsContestants = $DBconnection->query($query_rsContestants);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: t&auml;vlande per klubb, totalt";
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
<h3>T&auml;vlande per klubb</h3>
<p>Rapporten visar t&auml;vlande per klubb (aktiva konton), vare sig anm&auml;lda till en t&auml;vling eller inte.</p>
<table width="40%" border="1">
    <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>E-post kontakt</strong></td>
          <td><strong>T&auml;vlande</strong></td>          
        </tr>
    <?php while($row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC)) { ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_rsContestants['club_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsContestants['contact_email']; ?></td>        
        <td nowrap="nowrap"><?php echo $row_rsContestants['contestant_name']; ?></td>
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