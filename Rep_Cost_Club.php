<?php
//Moved meta description and keywords to header.php
//Granted access to all levels of registered users
if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
if ($_SESSION['MM_Level'] === 0) {
$MM_authorizedUsers = "0";    
}
if ($_SESSION['MM_Level'] === 1) {
$MM_authorizedUsers = "1";    
}
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
//Select number of registrations and cost for each club in active competition
require('Connections/DBconnection.php');           
$query_rsCost = "SELECT club_name, coach_names, COUNT(reg_id), SUM(class_fee) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE comp_current = 1 GROUP BY account_id ORDER BY club_name";
$stmt_rsCost = $DBconnection->query($query_rsCost);
$row_rsCost = $stmt_rsCost->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: anm&auml;lningar och kostnad per klubb";
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
<h3>Kostnad och antal anm&auml;lningar per klubb</h3>
<p>Rapporten visar hur m&aring;nga anm&auml;lningar (kata och/eller kumite) som gjorts till aktuell t&auml;vling och den sammanlagda kostnaden per klubb.</p>
<table width="100%" border="1">
    <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>Antal&nbsp;anm&auml;lningar</strong></td>
          <td><strong>Coacher</strong></td>
          <td><strong>Kostnad</strong></td>
        </tr>
    <?php while($row_rsCost = $stmt_rsCost->fetch(PDO::FETCH_ASSOC)) { 
 ?>
      <tr>
        <td nowrap="nowrap"><?php echo $row_rsCost['club_name']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsCost['COUNT(reg_id)']; ?></td>
        <td><?php echo $row_rsCost['coach_names']; ?></td>
        <td nowrap="nowrap"><?php echo $row_rsCost['SUM(class_fee)'].' kr'; ?></td>
      </tr>
    <?php } ?>
</table>
      <p>&nbsp;</p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statements and DB connection
$stmt_rsCost->closeCursor();
$DBconnection = null;
?>
