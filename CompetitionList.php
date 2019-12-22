<?php 
//Replaced comp_end_date with comp_start_time in table
//Truncated $row_rsCompetitions['comp_start_time'] to 5 characters

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

    //If button is clicked for updating then update the current competition
    if (filter_input(INPUT_POST, 'MM_update') && filter_input(INPUT_POST,'new_current_comp_id') && filter_input(INPUT_POST, 'MM_update') == 'CompForm') {
       $new_current_comp_id = filter_input(INPUT_POST, 'new_current_comp_id');
                //Catch anything wrong with query
                try {
                // Set all competitions first to non-current (0)    
                require('Connections/DBconnection.php');
                $comp_current = 0;
                $resetSQL = "UPDATE competition SET comp_current = :comp_current"; 
                $stmt_rsReset = $DBconnection->prepare($resetSQL);                                 
                $stmt_rsReset->bindValue(':comp_current', $comp_current, PDO::PARAM_INT);
                $stmt_rsReset->execute();
                }   
                catch(PDOException $ex) {
                    echo "An Error occured with query (resetSQL): ".$ex->getMessage();
                }   

                //Catch anything wrong with query
                try {
                // Set selected competition as current (1)    
                $updateSQL = "UPDATE competition SET comp_current = 1 WHERE comp_id = :comp_id"; 
                $stmt_rsCurrent = $DBconnection->prepare($updateSQL);                                 
                $stmt_rsCurrent->bindValue(':comp_id', $new_current_comp_id, PDO::PARAM_INT);
                $stmt_rsCurrent->execute();
                }   
                catch(PDOException $ex) {
                    echo "An Error occured with query (updateSQL): ".$ex->getMessage();
                }   
    //Kill statements and DB connection
    $stmt_rsReset->closeCursor();            
    $stmt_rsCurrent->closeCursor();            
    }
//Catch anything wrong with query
try {    
 //Select all columns from competitions table
require('Connections/DBconnection.php');           
$query1 = "SELECT * FROM competition ORDER BY comp_id ASC";
$stmt_rsCompetitions = $DBconnection->query($query1);
$totalRows_rsCompetitions = $stmt_rsCompetitions->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with query1: ".$ex->getMessage();
}   
$pagetitle="T&auml;vlingar";
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
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">  
<h3>Befintliga t&auml;vlingar</h3>
<?php if ($totalRows_rsCompetitions > 0) { // Show if recordset not empty ?>
  <p>Kopiera klasser fr&aring;n t&auml;vling, &auml;ndra eller ta bort t&auml;vlingar genom att klicka p&aring; respektive l&auml;nk.</p>
<?php } // Show if recordset not empty  
      if ($totalRows_rsCompetitions == 0) { // Show if recordset empty ?>
  <p>Det finns inga t&auml;vlingar att visa!</p>
<?php } // Show if recordset empty 
if ($totalRows_rsCompetitions > 0) { // Show if recordset not empty ?>
  <form id="CompForm" name="CompForm" method="POST" action=""> 
    <table width="100%" border="1">
    <tr>
      <td><strong>T&auml;vling</strong></td>
      <td><strong>Start-datum</strong></td>
      <td><strong>Start-tid</strong></td>
      <td><strong>Sista anm&auml;lnings-datum</strong></td>
      <td><strong>Max antal anm&auml;lningar</strong></td>
      <td><strong>Antal anm&auml;lningar</strong></td>      
      <td><strong>Antal t&auml;vlings-klasser</strong></td>
      <td><strong>Aktiv</strong></td>
      <td><strong>Kopiera klasser</strong></td>
      <td><strong>&Auml;ndra</strong></td>
      <td nowrap="nowrap"><strong>Ta bort</strong></td>
    </tr>
<?php while($row_rsCompetitions = $stmt_rsCompetitions->fetch(PDO::FETCH_ASSOC)) { 
 $colname_rsCompId = $row_rsCompetitions['comp_id'];

//Catch anything wrong with query
try {    
//Number of classes in each competition
require('Connections/DBconnection.php');           
$query2 = "SELECT class_id FROM classes WHERE comp_id = :comp_id";
$stmt_rsClasses = $DBconnection->prepare($query2);
$stmt_rsClasses->execute(array(':comp_id' => $colname_rsCompId));
$totalRows_rsClasses = $stmt_rsClasses->rowCount();
}   
catch(PDOException $ex) {
    echo "Another Error occured with query2: ".$ex->getMessage();
}   

//Catch anything wrong with query
try {    
//Number of registrations in each competition
$query3 = "SELECT re.reg_id, re.class_id FROM registration AS re INNER JOIN classes AS cl USING (class_id) WHERE comp_id = :comp_id";
$stmt_rsRegistrations = $DBconnection->prepare($query3);
$stmt_rsRegistrations->execute(array(':comp_id' => $colname_rsCompId));
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with query3: ".$ex->getMessage();
}     
        ?>
      <tr>
        <td><?php echo $row_rsCompetitions['comp_name']; ?></td>
        <td><?php echo $row_rsCompetitions['comp_start_date']; ?></td>
        <td><?php echo substr($row_rsCompetitions['comp_start_time'],0,5); ?></td>
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
<?php } ?>
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
<?php 
//Kill statements
$stmt_rsCompetitions->closeCursor();
$stmt_rsClasses->closeCursor();
include("includes/footer.php");?>
</body>
</html> 