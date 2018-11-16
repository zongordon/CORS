<?php
//Removed function and links to elimination ladders as that are replaced by Draws.php
//Removed kill DB as it's included in footer.php

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
    
$pagetitle="T&auml;vlingsklasser";
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
    <p>Se startlistan &ouml;ver t&auml;vlande genom att klicka p&aring; l&auml;nken.<br> <strong>Obs! T&auml;vlingsstegarna visas p&aring; separat sida efter sista anm&auml;lningsdagen och d&aring; lottningen &auml;r gjord!</strong></p>     
    <table width="100%" border="1">
      <tr>
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>Startlista</strong></td>
      </tr>
<?php while($row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC)) {;?>
        <tr>
          <td><?php echo $row_rsClasses['class_discipline']; ?></td>
          <td><?php echo $row_rsClasses['class_gender_category']; ?></td>
          <td><?php echo $row_rsClasses['class_category']; ?></td>
          <td><?php echo $row_rsClasses['class_age']; ?></td>
          <td><?php echo $row_rsClasses['class_weight_length']; ?></td>
          <td><a href="ClassContestants_loggedout.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">Startlista</a></td>
        </tr>
<?php }; ?>
    </table>
<?php
      } // Show if recordset not empty 
        if ($totalRows_rsClasses == 0) { // Show if recordset empty ?>
    <p>Det finns inga t&auml;vlingsklasser att visa &auml;n!</p>
 <?php  } // Show if recordset empty 
 $stmt_rsClasses->closeCursor();
    ?>
  </div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>