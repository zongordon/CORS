<?php
//Adapted sql query to PHP 7 (PDO) and added minor error handling. Changed from charset=ISO-8859-1. 
//Added header.php and news_sponsors_nav.php as includes.
//Removed function GetSQLValueString and includes/functions.php
//Changed way to show out competetion is raffled or not

if (!isset($_SESSION)) {
  session_start();
}

//Catch anything wrong with query
try {
require_once('Connections/DBconnection.php');    
// Select all classes for the current competition
$query1 = "SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age FROM classes AS c INNER JOIN competition AS com USING (comp_id) WHERE comp_current = 1 ORDER BY  class_discipline, class_gender, class_age, class_weight_length, class_gender_category";
$stmt_rsClasses = $DBconnection->query($query1);
$totalRows_rsClasses = $stmt_rsClasses->rowCount();
}   catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }
    
//Catch anything wrong with query
try {
// Select if the current competition is raffled
$query2 = "SELECT comp_id FROM competition WHERE comp_current = 1 AND comp_raffled = 1";
$stmt_rsRaffled = $DBconnection->query($query2);
$totalRows_rsRaffled = $stmt_rsRaffled->rowCount();
}   catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }    
    
$pagetitle="T&auml;vlingsklasser";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, karate, eskilstuna, visa tävlingsklasser, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";

// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");     
?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
    <div class="feature">
<?php if ($totalRows_rsClasses > 0) { // Show if recordset not empty ?>
    <h3>Befintliga t&auml;vlingsklasser</h3>
  <?php if ($totalRows_rsRaffled == 0) {
    echo '<p>Se startlistan &ouml;ver t&auml;vlande genom att klicka p&aring; l&auml;nken. <strong>Obs! T&auml;vlingsstegarna visas efter sista anm&auml;lningsdagen och d&aring; lottningen &auml;r gjord!</strong></p>';     
        }
        if ($totalRows_rsRaffled == 1) {
    echo '<p>Se startlistan &ouml;ver t&auml;vlande eller hela t&auml;vlingsstegen genom att klicka p&aring; respektive l&auml;nk. <strong>Lottningen &auml;r nu avklarad!</strong></p>';     
        } ?>
    <table width="100%" border="1">
      <tr>
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>Startlista</strong></td>
        <?php if ($totalRows_rsRaffled == 1) {
        echo "<td><strong>T&auml;vlingsstege</strong></td>";
              } ?>
      </tr>
<?php while($row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC)) {;?>
        <tr>
          <td><?php echo $row_rsClasses['class_discipline']; ?></td>
          <td><?php echo $row_rsClasses['class_gender_category']; ?></td>
          <td><?php echo $row_rsClasses['class_category']; ?></td>
          <td><?php echo $row_rsClasses['class_age']; ?></td>
          <td><?php echo $row_rsClasses['class_weight_length']; ?></td>
          <td><a href="ClassContestants_loggedout.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">Startlista</a></td>
          <?php if ($totalRows_rsRaffled == 1) {
                echo "<td><a href=javascript:MM_openBrWindow('ElimLadder.php?class_id=".$row_rsClasses['class_id']."','T&auml;vlingsstege','',1131,800,'true')>T&auml;vlingsstege</a></td>";
                } ?>          
        </tr>
<?php }; ?>
    </table>
<?php
      } // Show if recordset not empty 
        if ($totalRows_rsClasses == 0) { // Show if recordset empty ?>
    <p>Det finns inga t&auml;vlingsklasser att visa &auml;n!</p>
 <?php  } // Show if recordset empty 
 $stmt_rsClasses->closeCursor();
 $stmt_rsRaffled->closeCursor();
 $DBconnection = null;
    ?>
  </div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>