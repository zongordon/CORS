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
// Select number of registrations for each class, for the active competition
require('Connections/DBconnection.php');           
$query_rsRegistrations = "SELECT cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age, COUNT(class_id) FROM classes AS cl INNER JOIN registration AS re USING (class_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1 GROUP BY class_id ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length";
$stmt_rsRegistrations = $DBconnection->query($query_rsRegistrations);
$row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC); 
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: antal anm&auml;lningar och tids&aring;tg&aringng per t&auml;vlingsklass";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Munktellarena.";
$pagekeywords="tuna karate cup, rapport antal anmälningar och tidsåtgång per klass, karate, eskilstuna, Munktellarena, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
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
		<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Antal anm&auml;lningar och tids&aring;tg&aringng per t&auml;vlingsklass</h3>
<p>Rapporten visar hur m&aring;nga anm&auml;lningar som gjorts till aktuell t&auml;vling per t&auml;vlingsklass och ber&auml;knad tids&aring;tg&aringng f&ouml;r klassen.</p>
<table width="80%" border="1">
    <tr>
      <td><strong>T&auml;vlingsklass</strong></td>
          <td><strong>Antal t&auml;vlande</strong></td>
          <td><strong>Totalt ber&auml;knad tid (min)</strong></td>
      </tr>
      <?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_weight_length'].' | '.$row_rsRegistrations['class_age'].' &aring;r'?></td>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['COUNT(class_id)']; ?></td>
          <td nowrap="nowrap">
<?php 
if ($row_rsRegistrations['class_discipline'] == "Kata") {
$time_class = 3;
}
if (($row_rsRegistrations['class_discipline'] == "Kumite") && ($row_rsRegistrations['class_category'] == "Barn")) {
$time_class = 4;
}
if (($row_rsRegistrations['class_discipline'] == "Kumite") && ($row_rsRegistrations['class_category'] <> "Barn")) {
$time_class = 5;
}
echo (($row_rsRegistrations['COUNT(class_id)']-1) * $time_class); ?></td>
        </tr>
      <?php }  ?>        
    </table>
      <p>&nbsp;</p>
  </div>
  <div class="story">
    <p>&nbsp;</p>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statements and DB connection
$stmt_rsRegistrations->closeCursor();
$DBconnection = null;
?>