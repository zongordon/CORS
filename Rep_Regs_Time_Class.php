<?php 
//Replace width="80%" with class="wide_tbl" and removed  nowrap="nowrap"

if (!isset($_SESSION)) {
  session_start();
}
//Access level registered user
$MM_authorizedUsers = $_SESSION['MM_Level']; 
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Select number of registrations for each class, for the active competition
require('Connections/DBconnection.php');           
$query_rsRegistrations = "SELECT com.comp_limit_roundrobin, cl.class_id, cl.class_team, cl.class_category, cl.class_discipline, "
        . "cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age, cl.class_match_time, cl.class_repechage, "
        . "COUNT(class_id) FROM classes AS cl INNER JOIN registration AS re USING (class_id) INNER JOIN competition as com "
        . "USING (comp_id) WHERE comp_current = 1 GROUP BY class_id ORDER BY cl.class_team, cl.class_discipline, cl.class_gender, "
        . "cl.class_age, cl.class_weight_length";
$stmt_rsRegistrations = $DBconnection->query($query_rsRegistrations); 
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

$pagetitle="Rapport: antal anm&auml;lningar och tids&aring;tg&aringng per t&auml;vlingsklass";
//Require Class for calculating number of matches and total match time
require_once 'Classes/ClassCalculations.php';
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
<table Replace class="wide_tbl" border="1">
    <tr>
      <td><strong>T&auml;vlingsklass</strong></td>
          <td><strong>Antal t&auml;vlande</strong></td>
          <td><strong>Totalt antal matcher</strong></td>
          <td><strong>Totalt ber&auml;knad tid (min)</strong></td>
      </tr>
      <?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) { 
            $max_matches = new ClassCalculations;
            $max_matches->limit_roundrobin = $row_rsRegistrations['comp_limit_roundrobin'];
            $max_matches->registrations = $row_rsRegistrations['COUNT(class_id)'];
            $max_matches->repechage = $row_rsRegistrations['class_repechage'];

            $total_match_time = new ClassCalculations;
            $total_match_time->class_match_time = $row_rsRegistrations['class_match_time'];?>
        <tr>
          <td><?php if($row_rsRegistrations['class_team'] === 1){echo'Lag - ';} echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_weight_length'].' | '.$row_rsRegistrations['class_age'].' &aring;r'?></td>
          <td><?php echo $row_rsRegistrations['COUNT(class_id)']; ?></td>
          <td><?php echo $max_matches->class_max_matches(); ?></td>
          <td><?php echo $max_matches->class_max_matches()*$total_match_time->class_total_time(); ?></td>
        </tr>
      <?php }  ?>        
    </table>
    <p><a href="javascript:history.go(-1);">Klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
  </div>
  <div class="story">
    <p>&nbsp;</p>
</div>
</div>
<?php 
//Kill statement
$stmt_rsRegistrations->closeCursor();
include("includes/footer.php");
?>
</body>
</html>

