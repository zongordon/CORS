<?php
//Corrected bug preventing classes from being displayed correctly
ob_start();

global $editFormAction;

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="T&auml;vlingsklasser - admin";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, lista tävlingsklasser för administratörer, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

//Set initial sorting (ORDER BY) and change if new sort order is selected in dropdown list
$sorting = "comp_current DESC, comp_name, class_discipline, class_gender, class_age, class_weight_length, class_gender_category";
if (isset($_GET['sorting'])) {
  $sorting = $_GET['sorting'];
}
//Select all classes for respective competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = "SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, c.class_fee, com.comp_name FROM classes AS c INNER JOIN competition AS com USING (comp_id) WHERE comp_current = 1 ORDER BY $sorting";
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$row_rsClasses = mysql_fetch_assoc($rsClasses);
$totalRows_rsClasses = mysql_num_rows($rsClasses);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<?php if ($totalRows_rsClasses == 0) { // Show if recordset empty ?>
<p>Det finns inga t&auml;vlingsklasser att visa &auml;n!</p>
<?php } // Show if recordset empty

if ($totalRows_rsClasses > 0) { // Show if recordset not empty ?> 
    <h3>Befintliga t&auml;vlingsklasser</h3>
    <p>Se startlistan av t&auml;vlande eller hela t&auml;vlingsstegen, &auml;ndra eller ta bort t&auml;vlingsklasser genom att klicka p&aring; respektive l&auml;nk. &Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
<form action="<?php echo $editFormAction; ?>" method="GET" enctype="application/x-www-form-urlencoded" name="SelectSorting" id="SelectSorting">
  <table width="200" border="0">
    <tr>
      <td valign="middle">Sortering</td>
      <td><label>
        <select name="sorting" id="sorting">
      <option value="comp_current DESC, comp_name, class_discipline, class_gender, class_age, class_weight_length, class_gender_category">Aktuell t&auml;vling f&ouml;rst</option>
      <option value="comp_name, class_discipline, class_gender, class_age, class_weight_length, class_gender_category">T&auml;vlingsnamn</option>
</select>
      </label></td>
      <td><input type="submit" name="submit" id="submit" value="Sortera" /></td>
    </tr>
  </table>
</form>
    <table width="100%" border="1">
      <tr>
        <td><strong>T&auml;vling</strong></td>                  
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>Avgift</strong></td>        
        <td><strong>Startlista</strong></td>
        <td><strong>T&auml;vlingsstege</strong></td>        
        <td><strong>&Auml;ndra</strong></td>
        <td><strong>Ta bort</strong></td>
      </tr>
      <?php do { ?>
  <tr>
    <td><?php echo $row_rsClasses['comp_name']; ?></td>      
          <td><?php echo $row_rsClasses['class_discipline']; ?></td>
          <td><?php echo $row_rsClasses['class_gender_category']; ?></td>
          <td><?php echo $row_rsClasses['class_category']; ?></td>
          <td><?php echo $row_rsClasses['class_age']; ?></td>
          <td><?php echo $row_rsClasses['class_weight_length']; ?></td>
    <td><?php echo $row_rsClasses['class_fee'].' kr'; ?></td>
    <td><a href="ClassContestants.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">Startlista</a></td>
    <td><a href="javascript:MM_openBrWindow('ElimLadder.php?class_id=<?php echo $row_rsClasses['class_id']; ?>','T&auml;vlingsstege','',1131,800,'true')">T&auml;vlingsstege</a></td>          
    <td><a href="ClassUpdate.php?class_id=<?php echo $row_rsClasses['class_id']; ?>">&Auml;ndra</a></td>
    <td><a href="#" onclick="return deleteClass('<?php echo $row_rsClasses['class_id']; ?>')">Ta bort</a></td>
  </tr>
  <?php } while ($row_rsClasses = mysql_fetch_assoc($rsClasses)); ?>
    </table>
<?php 
mysql_free_result($rsClasses);    
} // Show if recordset not empty 
?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>