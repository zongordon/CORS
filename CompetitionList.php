<?php 
//Added function to change current competition and only one current (active) at a time

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="T&auml;vlingar";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, lista tävlingar, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php'); 

    //If button is clicked for updating then update the current competition
    if (isset($_POST["MM_update"]) && isset($_POST['new_current_comp_id']) && ($_POST["MM_update"] == "CompForm")) {
       $new_current_comp_id = $_POST['new_current_comp_id'];
       // Set all competitions first to non-current (0)
       $resetSQL = sprintf("UPDATE competition SET comp_current = 0");
       mysql_select_db($database_DBconnection, $DBconnection);
       $Result1 = mysql_query($resetSQL, $DBconnection) or die(mysql_error());
       // Set selected competition as current (1)     
       $updateSQL = sprintf("UPDATE competition SET comp_current = 1 WHERE comp_id=$new_current_comp_id");
       mysql_select_db($database_DBconnection, $DBconnection);
       $Result2 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
    }
 //Select all columns from competitions table
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompetitions = "SELECT * FROM competition ORDER BY comp_id ASC";
$rsCompetitions = mysql_query($query_rsCompetitions, $DBconnection) or die(mysql_error());
$row_rsCompetitions = mysql_fetch_assoc($rsCompetitions);
$totalRows_rsCompetitions = mysql_num_rows($rsCompetitions);            
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php"); ?>
  <!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
  <!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">  
<h3>Befintliga t&auml;vlingar</h3>
<?php if ($totalRows_rsCompetitions > 0) { // Show if recordset not empty ?>
  <p>Kopiera klasser fr&aring;n t&auml;vling, &auml;ndra eller ta bort t&auml;vlingar genom att klicka p&aring; respektive l&auml;nk.</p>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsCompetitions == 0) { // Show if recordset empty ?>
  <p>Det finns inga t&auml;vlingar att visa!</p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_rsCompetitions > 0) { // Show if recordset not empty ?>
  <form id="CompForm" name="CompForm" method="POST" action=""> 
    <table width="100%" border="1">
    <tr>
      <td><strong>T&auml;vling</strong></td>
      <td><strong>Startdatum</strong></td>
      <td><strong>Slutdatum</strong></td>
      <td><strong>Sista anm&auml;lningsdatum</strong></td>
      <td><strong>Max antal anm&auml;lningar</strong></td>
      <td><strong>Antal anm&auml;lningar</strong></td>      
      <td><strong>Antal t&auml;vlingsklasser</strong></td>
      <td><strong>Aktiv</strong></td>
      <td><strong>Kopiera klasser</strong></td>
      <td><strong>&Auml;ndra</strong></td>
      <td nowrap="nowrap"><strong>Ta bort</strong></td>
    </tr>
    <?php do { 
  $colname_rsClasses = $row_rsCompetitions['comp_id'];
        
//Number of classes in each competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = sprintf("SELECT class_id FROM classes WHERE comp_id = %s", GetSQLValueString($colname_rsClasses, "int"));
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$totalRows_rsClasses = mysql_num_rows($rsClasses);

  $colname_rsRegistrations = $row_rsCompetitions['comp_id'];

//Number of registrations in each competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = sprintf("SELECT re.reg_id, re.class_id FROM registration AS re INNER JOIN classes AS cl USING (class_id) WHERE comp_id = %s", GetSQLValueString($colname_rsRegistrations, "int"));
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);        
        ?>
      <tr>
        <td><?php echo $row_rsCompetitions['comp_name']; ?></td>
        <td><?php echo $row_rsCompetitions['comp_start_date']; ?></td>
        <td><?php echo $row_rsCompetitions['comp_end_date']; ?></td>
        <td><?php echo $row_rsCompetitions['comp_end_reg_date']; ?></td>
        <td><?php echo $row_rsCompetitions['comp_max_regs']; ?></td>
        <td><?php echo $totalRows_rsRegistrations;?></td>
        <td><?php echo $totalRows_rsClasses;?></td> 
        <td><label>
        <input <?php if (!(strcmp($row_rsCompetitions['comp_current'],1))) { 
                        echo "checked=\"checked\"";
                     } ?> 
        type="radio" name="new_current_comp_id" value ="<?php echo $row_rsCompetitions['comp_id'];?>" id = "new_current_comp_id_<?php echo $row_rsCompetitions['comp_id'];?>"/>               
        </label></td>
        <td><a href="ClassesCopy.php?comp_id=<?php echo $row_rsCompetitions['comp_id']; ?>">Kopiera</a></td>        
        <td><a href="CompetitionUpdate.php?comp_id=<?php echo $row_rsCompetitions['comp_id']; ?>">&Auml;ndra</a></td>
        <td><a href="#" onclick="return deleteCompetition('<?php echo $row_rsCompetitions['comp_id']; ?>')">Ta bort</a></td>
      </tr> 
      <?php } while ($row_rsCompetitions = mysql_fetch_assoc($rsCompetitions)); ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>          
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>Uppdatera aktiv t&auml;vling:</td>          
          <td><input name="CompUpdate" type="submit" id="CompUpdate" value="Spara" /></td>
        </tr>
    </table>
      <input type="hidden" name="MM_update" value="CompForm" />      
  </form>      
  <?php 
      } // Show if recordset not empty ?>
  </div>
  <div class="story"></div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsCompetitions);
mysql_free_result($rsClasses);
mysql_free_result($rsRegistrations);
?> 