<?php
///Moved meta description and keywords to header.php

if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}
$sorting = "class_discipline, class_gender, class_age, class_weight_length, club_startorder, reg_id";
//Fetch the selected sorting
$colname_rsCompetition = filter_input(INPUT_GET,'comp_id');

if (filter_input(INPUT_GET,'sorting')) {
  $sorting = filter_input(INPUT_GET,'sorting');
}
//Catch anything wrong with query
try {
// Select all registered contestants for the current competition    
require_once('Connections/DBconnection.php');    
//Select contestants for current competition
$query = "SELECT com.comp_current, com.comp_id, a.club_name, re.reg_id, re.contestant_result, co.contestant_name, re.contestant_height, re.contestant_startnumber, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN clubregistration AS clu USING (club_reg_id) INNER JOIN account AS a USING (account_id) INNER JOIN competition AS com USING (comp_id) INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE com.comp_current = 1 ORDER BY $sorting";
$stmt_rsRegistrations = $DBconnection->query($query);
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();
}   catch(PDOException $ex) {
    echo "An Error occured!"; //user friendly message
    //some_logging_function($ex->getMessage());
    }
    
$pagetitle="Samtliga t&auml;vlande";
// Includes Several code functions
include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<?php if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
        <p>Det finns inga startlistor att visa &auml;n!</p>
<?php } // Show if recordset empty
if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>  
<h3>Samtliga anm&auml;lda till start</h3>
<p>Nedan finns samtliga nuvarande anm&auml;lningar till t&auml;vlingen. &Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
<!-- Sort the table by name of the club, competition class or contestant name!-->
<form action="<?php echo $editFormAction; ?>" method="GET" enctype="application/x-www-form-urlencoded" name="SelectSorting" id="SelectSorting">
  <table width="200" border="0">
    <tr>
      <td valign="middle">Sortering</td>
      <td><label>
        <select name="sorting" id="sorting">
      <option value="club_name, class_discipline, class_gender, class_age, class_weight_length, contestant_name"<?php if (!(strcmp($sorting, "club_name, class_discipline, class_gender, class_age, class_weight_length, contestant_name"))) {echo "selected=\"selected\"";} ?>>Klubb</option>
      <option value="class_discipline, class_gender, class_age, class_weight_length, club_startorder, reg_id"<?php if (!(strcmp($sorting, "class_discipline, class_gender, class_age, class_weight_length, club_startorder, reg_id"))) {echo "selected=\"selected\"";} ?>>T&auml;vlingsklass</option>
      <option value="contestant_name, club_name"<?php if (!(strcmp($sorting, "contestant_name, club_name"))) {echo "selected=\"selected\"";} ?>>T&auml;vlande</option>
        </select>
      </label></td>
      <td><input type="submit" name="submit" id="submit" value="Sortera" /></td>
    </tr>
  </table>
</form>
<table width="100%" border="1">
  <tr>
    <td><strong>Startnr.</strong></td>      
    <td><strong>Klubb</strong></td>
    <td><strong>T&auml;vlande</strong></td>
    <td><strong>L&auml;ngd (eventuellt)</strong></td>
    <td><strong>T&auml;vlingsklass</strong></td>
    </tr>
  <?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) {;?>
    <tr>
      <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>        
      <td><?php echo $row_rsRegistrations['club_name']; ?></td>
      <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
      <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
      <td><?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
      if ($row_rsRegistrations['class_age'] == "") { 
          echo "";          
      } 
      if ($row_rsRegistrations['class_age'] <> "") { 
          echo $row_rsRegistrations['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsRegistrations['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsRegistrations['class_weight_length'] <> "-") {
         echo $row_rsRegistrations['class_weight_length']; 
      }
      ?></td>
      </tr>
    <?php } ?>
</table>
<?php 
} // Show if recordset not empty 
$stmt_rsRegistrations->closeCursor();
$DBconnection = null;
?>
  </div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>