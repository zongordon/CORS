<?php
//Moved meta description and keywords to header.php
ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Ta bort t&auml;vling";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

//Fetch the selected competition from previous page
$colname_rsCompetition = filter_input(INPUT_GET,'comp_id');    

//DELETE the competition 
require('Connections/DBconnection.php');         
$query = "DELETE FROM competition WHERE comp_id = :comp_id";
$stmt_rsCompDelete = $DBconnection->prepare($query);
$stmt_rsCompDelete->bindValue(':comp_id', $colname_rsCompetition, PDO::PARAM_INT);   
$stmt_rsCompDelete->execute();

  $deleteGoTo = "CompetitionList.php";
  if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
  }
  header(sprintf("Location: %s", $deleteGoTo));
  //Kill statements and DB connection
  $stmt_rsCompDelete->closeCursor();
  $DBconnection = null;
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
  <div class="feature">
<h3>Det finns ingen t&auml;vling att ta bort!</h3>
<p><a href="CompetitionList.php">Tillbaka till T&auml;vlingar</a> </p>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>