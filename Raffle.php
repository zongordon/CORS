<?php //Rewrote the code to have individual draws as well as class specific club draws including possible to raffle separate classes or all classes at once.
ob_start();

//Declare and initialise variables
$editFormAction = '';

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";
    
$pagetitle="Lotta t&auml;vlingsklasser - admin";
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
<?php 
$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Update start order when entered start order and button is clicked
if (filter_input(INPUT_POST,"MM_update") === "UpdateRaffle") {
    //Catch anything wrong with query
    try {
    //Get data from form to use when updating
    $club_startorder = filter_input(INPUT_POST,'club_startorder');
    $club_reg_id = filter_input(INPUT_POST,'club_reg_id');
    require('Connections/DBconnection.php');
    $updateStartorder = "UPDATE clubregistration SET club_startorder = :club_startorder WHERE club_reg_id = :club_reg_id";
    $stmt = $DBconnection->prepare($updateStartorder);                                  
    $stmt->bindValue(':club_startorder', $club_startorder, PDO::PARAM_INT);       
    $stmt->bindValue(':club_reg_id', $club_reg_id, PDO::PARAM_INT);    
    $stmt->execute(); 
    }   
    catch(PDOException $ex) {
        echo "An Error occured with query $updateStartorder: ".$ex->getMessage();
    }  
//Kill statement
$stmt->closeCursor();
}

//Update to raffle done when when selected "Lottning klar" and button is clicked
if (filter_input(INPUT_POST,"MM_RaffleDone") === "RaffleDone") {
    //Catch anything wrong with query
    try {
    //Get data from form to use when updating
    $comp_raffled = filter_input(INPUT_POST,'comp_raffled');
    $comp_id = filter_input(INPUT_POST,'comp_id');
    require('Connections/DBconnection.php');
    $updateRaffle = "UPDATE competition SET comp_raffled = :comp_raffled WHERE comp_id = :comp_id";
    $stmt = $DBconnection->prepare($updateRaffle);                                  
    $stmt->bindValue(':comp_raffled', $comp_raffled, PDO::PARAM_INT);       
    $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);    
    $stmt->execute(); 
    }   
    catch(PDOException $ex) {
        echo "An Error occured with query $updateRaffle: ".$ex->getMessage();
    }      
//Kill statement
$stmt->closeCursor();    
}
//Catch anything wrong with query
try {
//Select the current raffle data from the active competition
require('Connections/DBconnection.php');           
$queryclub_reg = "SELECT club_reg_id FROM clubregistration INNER JOIN registration USING (club_reg_id) INNER JOIN account USING (account_id) "
        . "INNER JOIN competition USING (comp_id) WHERE comp_current = 1";
$stmt_rsClubRegs = $DBconnection->query($queryclub_reg);
$totalRows_rsClubRegs = $stmt_rsClubRegs->rowCount();   
}   
    catch(PDOException $ex) {
        echo "An Error occured with $queryclub_reg: ".$ex->getMessage();
    }
    
if ($totalRows_rsClubRegs == 0) { // Show if recordset empty ?>
        <p>Det finns inga anm&auml;lningar att lotta, &auml;n!</p>
<?php } // Show if recordset empty
else { // Show if recordset not empty 
// Raffle the all classes if the button is clicked	
if (filter_input(INPUT_POST,"MM_raffle_all") === "raffle_all") { 
    // Generate a random start order for each distinct club_reg_id in current competition
    require('Connections/DBconnection.php');           
    $query_rsDistinctClubs = "SELECT DISTINCT r.club_reg_id FROM registration AS r INNER JOIN classes AS c USING (class_id) "
            . "INNER JOIN competition AS co USING (comp_id) WHERE comp_current = 1";
    $stmt_rsDistinctClubs = $DBconnection->prepare($query_rsDistinctClubs);
    $stmt_rsDistinctClubs->execute();

while ($row_rsDistinctClubs = $stmt_rsDistinctClubs->fetch(PDO::FETCH_ASSOC)) {
    $randomNumbers[$row_rsDistinctClubs['club_reg_id']] = random_int(0,99999); // Generate a random start order
}
    // Update each club with random start order
    $updateClubSQL = "UPDATE registration SET club_start_order = :club_start_order "
            . "WHERE club_reg_id = :club_reg_id";
    $updateClubStmt = $DBconnection->prepare($updateClubSQL);

    foreach ($randomNumbers as $clubRegId => $randomNumber) {
        $updateClubStmt->bindValue(':club_start_order', $randomNumber, PDO::PARAM_INT);
        $updateClubStmt->bindValue(':club_reg_id', $clubRegId, PDO::PARAM_INT);
        $updateClubStmt->execute();
    }
// Generate a random start order for each registration in all classes in current competition
 require('Connections/DBconnection.php');           
    $query_rsRegistrations = "SELECT reg_id FROM registration AS r INNER JOIN classes AS c USING (class_id) "
            . "INNER JOIN competition AS co USING (comp_id) WHERE comp_current = 1";
    $stmt_rsRegistrations = $DBconnection->prepare($query_rsRegistrations);
    $stmt_rsRegistrations->execute();

while ($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) {
    $randomNumbers[$row_rsRegistrations['reg_id']] = random_int(0,99999); // Generate a random start order
}
     // Update each registration with random start order
    $updateRegSQL = "UPDATE registration SET start_order = :start_order WHERE reg_id = :reg_id" ;
    $updateRegStmt = $DBconnection->prepare($updateRegSQL);

    foreach ($randomNumbers as $regId => $randomNumber) {
        $updateRegStmt->bindValue(':start_order', $randomNumber, PDO::PARAM_INT);
        $updateRegStmt->bindValue(':reg_id', $regId, PDO::PARAM_INT);
        $updateRegStmt->execute();
    }
// Kill statements    
$stmt_rsDistinctClubs->closeCursor();     
$stmt_rsRegistrations->closeCursor();         
}    
    
// Raffle the selected class if the button is clicked	
if (filter_input(INPUT_POST,"MM_raffle") === "raffle") {
    $colname_SelectedClass = filter_input(INPUT_POST,'class_id');
 
    // Generate a random start order for each distinct club_reg_id in selected class
    require('Connections/DBconnection.php');           
    $query_rsDistinctClubs = "SELECT DISTINCT club_reg_id FROM registration WHERE class_id = :class_id";
    $stmt_rsDistinctClubs = $DBconnection->prepare($query_rsDistinctClubs);
    $stmt_rsDistinctClubs->execute(array(':class_id'=>$colname_SelectedClass));

while ($row_rsDistinctClubs = $stmt_rsDistinctClubs->fetch(PDO::FETCH_ASSOC)) {
    $randomNumbers[$row_rsDistinctClubs['club_reg_id']] = random_int(0,99999); // Generate a random start order
}
    // Update each club with random start order
    $updateClubSQL = "UPDATE registration SET club_start_order = :club_start_order "
            . "WHERE club_reg_id = :club_reg_id AND class_id = :class_id";
    $updateClubStmt = $DBconnection->prepare($updateClubSQL);

    foreach ($randomNumbers as $clubRegId => $randomNumber) {
        $updateClubStmt->bindValue(':club_start_order', $randomNumber, PDO::PARAM_INT);
        $updateClubStmt->bindValue(':club_reg_id', $clubRegId, PDO::PARAM_INT);
        $updateClubStmt->bindValue(':class_id', $colname_SelectedClass, PDO::PARAM_INT);
        $updateClubStmt->execute();
    }
// Generate a random start order for each registration in selected class
 require('Connections/DBconnection.php');           
    $query_rsRegistrations = "SELECT reg_id FROM registration WHERE class_id = :class_id";
    $stmt_rsRegistrations = $DBconnection->prepare($query_rsRegistrations);
    $stmt_rsRegistrations->execute(array(':class_id'=>$colname_SelectedClass));

while ($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) {
    $randomNumbers[$row_rsRegistrations['reg_id']] = random_int(0,99999); // Generate a random start order
}
     // Update each registration with random start order
    $updateRegSQL = "UPDATE registration SET start_order = :start_order WHERE reg_id = :reg_id AND class_id = :class_id" ;
    $updateRegStmt = $DBconnection->prepare($updateRegSQL);

    foreach ($randomNumbers as $regId => $randomNumber) {
        $updateRegStmt->bindValue(':start_order', $randomNumber, PDO::PARAM_INT);
        $updateRegStmt->bindValue(':reg_id', $regId, PDO::PARAM_INT);
        $updateRegStmt->bindValue(':class_id', $colname_SelectedClass, PDO::PARAM_INT);        
        $updateRegStmt->execute();
    }
// Kill statements    
$stmt_rsDistinctClubs->closeCursor();     
$stmt_rsRegistrations->closeCursor();         
}    
//Catch anything wrong with query    
try {
//Select all classes for respective competition
require('Connections/DBconnection.php');           
$ClassQuery = "SELECT c.class_id, c.comp_id, c.class_team, c.class_category, c.class_discipline, c.class_discipline_variant, "
        . "c.class_gender_category, c.class_gender, c.class_weight_length, c.class_age, r.start_order AS class_start_order "
        . "FROM classes c JOIN (SELECT class_id, MIN(start_order) AS start_order FROM registration GROUP BY class_id) "
        . "r ON c.class_id = r.class_id JOIN competition comp ON c.comp_id = comp.comp_id "
        . "WHERE comp.comp_current = 1 ORDER BY class_discipline, class_team, class_gender, class_age, class_weight_length, class_gender_category";
$stmt_rsClasses = $DBconnection->query($ClassQuery);
$totalRows_rsClasses = $stmt_rsClasses->rowCount();
}   
    catch(PDOException $ex) {
        echo "An Error occured with $ClassQuery: ".$ex->getMessage();
    } ?> 
    <h3>T&auml;vlingsklasser att lotta</h3>
    <p>Klicka p&aring; respektive knapp f&ouml;r att antingen lotta alla klasser samtidigt eller f&ouml;r att lotta enskilda klasser.</p>
      <table class="medium_tbl" border="1">
        <tr>
          <th colspan="2">T&auml;vlingsklasser</th>
         </tr>
        <tr>
        <td colspan="2">
     <form id="raffle_all" name="MM_raffle_all" method="POST" action="<?php echo $editFormAction; ?>">              
     <table class="medium_tbl" border="0"> 
      <tr>
          <td>
          <input type="submit" name="raffle_all" class= "button" id="raffle_all" value="Lotta alla klasser" />
          <input type="hidden" name="MM_raffle_all" value="raffle_all" id="raffle_all"/>
          </td>
       </tr>                           
     </table>
     </form>
        </td>
        </tr>
        <tr>
          <td colspan="2">
<form id="raffle" name="MM_raffle" method="POST" action="<?php echo $editFormAction; ?>">              
      <table class="medium_tbl" border="0">                                      
         <tr>
          <td><label><select name="class_id" id="class_id">                               
<?php
    while($row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC)) {
?>
      <option value="<?php echo $row_rsClasses['class_id']?>">
    <?php if($row_rsClasses['class_team'] === 1){echo'Lag - ';}echo $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_category'].' | '; 
      if ($row_rsClasses['class_age'] === "") { 
          echo "";          
      } 
      if ($row_rsClasses['class_age'] <> "") { 
          echo $row_rsClasses['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsClasses['class_weight_length'] === "-") {
          echo "";                    
      }
      if ($row_rsClasses['class_weight_length'] <> "-") {
         echo $row_rsClasses['class_weight_length'].' | '; 
      } 
     if ($row_rsClasses['class_start_order'] <> 0 || NULL) {
         echo 'Lottad!'; 
      }   
?>
      </option>
<?php
    } ?></select></label>
          </td>
          <td>
          <input type="submit" name="raffle" class= "button" id="raffle" value="Lotta klassen" />
          <input type="hidden" name="MM_raffle" value="raffle" id="raffle"/>
          </td>
        </tr>
        </table>
</form></td>
          </tr>
     </table>
<?php 
//Kill statement
$stmt_rsClasses->closeCursor();
          
//Catch anything wrong with query
try{
//Select raffle status from the active competition
require('Connections/DBconnection.php');           
$queryRaffle = "SELECT comp_id, comp_raffled FROM competition WHERE comp_current = 1";
$stmt_rsRaffle = $DBconnection->query($queryRaffle);
$row_rsRaffle = $stmt_rsRaffle->fetch(PDO::FETCH_ASSOC);
}   
    catch(PDOException $ex) {
        echo "An Error occured with $queryRaffle: ".$ex->getMessage();
    }
?>
<p>&Auml;ndra status n&auml;r lottningen &auml;r klar och klicka p&aring; Spara!</p>
    <table class = "narrow_tbl" border="1">
      <thead>
      <tr>
          <th colspan="2">Status p&aring; lottning</th>
        </tr>
      </thead
  ><form id="RaffleDone" name="RaffleDone" method="POST" action="<?php echo $editFormAction; ?>">
      <tr>
        <td><label>
            <select name="comp_raffled" type="int" id="comp_raffled" ">
            <option value="0" <?php if (!(strcmp("0", $row_rsRaffle['comp_raffled']))) {echo "selected=\"selected\"";} ?>>Lottning inte klar</option>
            <option value="1" <?php if (!(strcmp("1", $row_rsRaffle['comp_raffled']))) {echo "selected=\"selected\"";} ?>>Lottning klar</option>
          </label></td>
        <td><input type="submit" name="Spara" class = "button" id="Spara" value="Spara" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsRaffle['comp_id']; ?>" /></td>
      </tr>
    <input type="hidden" name="MM_RaffleDone" value="RaffleDone"/>
  </form>
    </table>
<?php
//Kill statement
$stmt_rsRaffle->closeCursor(); ?>
  </div>
</div>
<?php
include("includes/footer.php");
} // Show if recordset not empty 
?>
</body>
</html>