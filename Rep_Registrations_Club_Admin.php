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
$query_rsContestants = "SELECT comp_name, club_name, contestant_name,class_discipline FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN contestants USING (contestant_id) INNER JOIN account USING(account_id) ORDER BY comp_start_date DESC, club_name, contestant_name Asc";
$stmt_rsContestants = $DBconnection->query($query_rsContestants);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: anm&auml;lda per klubb";
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
<h3>Anm&auml;lda till n&aring;gon klass per klubb</h3>
<p>Rapporten visar vilka anm&auml;lda per klubb (som anm&auml;lts till n&aring;gon t&auml;vlingsdisciplin) vid n&aring;gon t&auml;vling.</p>
<table width="40%" border="1">
    <tr>
          <td><strong>T&auml;vling</strong></td>        
          <td><strong>Klubb</strong></td>
          <td><strong>T&auml;vlande</strong></td>
          <td><strong>T&auml;vlingsdisciplin</strong></td>
        </tr>
    <?php while($row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC)) { ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_rsContestants['comp_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsContestants['club_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsContestants['contestant_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsContestants['class_discipline']; ?></td>
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