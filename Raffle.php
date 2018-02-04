<?php 
//Moved meta description and keywords to header.php
//Added code to handle situation with no registrations
ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

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
        echo "An Error occured with queryX: ".$ex->getMessage();
    }  
//Kill statements and DB connection
$stmt->closeCursor();
$DBconnection = null;        
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
        echo "An Error occured with queryX: ".$ex->getMessage();
    }      
//Kill statements and DB connection
$stmt->closeCursor();
$DBconnection = null;    
}
//Select the current raffle data from the active competition
require('Connections/DBconnection.php');           
$query1 = "SELECT comp_id, club_reg_id, club_name, club_startorder FROM clubregistration INNER JOIN account USING (account_id) INNER JOIN competition USING (comp_id) WHERE comp_current = 1 ORDER BY club_startorder, club_name";
$stmt_rsClubRegs = $DBconnection->query($query1);
$totalRows_rsClubRegs = $stmt_rsClubRegs->rowCount();   

$pagetitle="Hantera lottning";
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
<?php 
if ($totalRows_rsClubRegs === 0){
     echo '<h3>Finns inga anm&auml;ningar att lotta &auml;n!</h3>';
}
else {
?>    
<h3>Hantera lottningen av klubbarnas inb&ouml;rdes startordning</h3>
<p>V&auml;lj startordning f&ouml;r  klubben och klicka p&aring; Spara!</p>
    <table width="300" border="0">
      <tr>
        <td><strong>Klubb</strong></td>
        <td><strong>Lottning</strong></td>
        <td>&nbsp;</td>
        </tr>
<?php while($row_rsClubRegs = $stmt_rsClubRegs->fetch(PDO::FETCH_ASSOC)) { ?>
  <form id="UpdateRaffle" name="UpdateRaffle" method="POST" action="<?php echo $editFormAction; ?>">
      <tr>
        <td><label>
          <input type="text" name="club_name" id="club_name" value="<?php echo $row_rsClubRegs['club_name']; ?>" size="55"/>
          </label></td>
        <td><label>
          <input name="club_startorder" type="text" id="club_startorder" value="<?php echo $row_rsClubRegs['club_startorder']; ?>" size="2" maxlength="2" />
          </label></td>
        <td><input type="submit" name="Spara" id="Spara" value="Spara" />
          <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubRegs['club_reg_id']; ?>" /></td>
        </tr>
    <input type="hidden" name="MM_update" value="UpdateRaffle"/>
  </form>
<?php } ?>
      </table>  
<?php
//Select raffle status from the active competition
require('Connections/DBconnection.php');           
$query2 = "SELECT comp_id, comp_raffled FROM competition WHERE comp_current = 1";
$stmt_rsRaffle = $DBconnection->query($query2);
$row_rsRaffle = $stmt_rsRaffle->fetch(PDO::FETCH_ASSOC);
?>
<p>&Auml;ndra status n&auml;r lottningen &auml;r klar och klicka p&aring; Spara!</p>
    <table border="0">
      <tr>
          <td><strong>Status p&aring; lottning</strong></td>
          <td>&nbsp;</td>
      </tr>
  <form id="RaffleDone" name="RaffleDone" method="POST" action="<?php echo $editFormAction; ?>">
      <tr>
        <td><label>
            <select name="comp_raffled" type="int" id="comp_raffled" ">
            <option value="0" <?php if (!(strcmp("0", $row_rsRaffle['comp_raffled']))) {echo "selected=\"selected\"";} ?>>Lottning inte klar</option>
            <option value="1" <?php if (!(strcmp("1", $row_rsRaffle['comp_raffled']))) {echo "selected=\"selected\"";} ?>>Lottning klar</option>
          </label></td>
        <td><input type="submit" name="Spara" id="Spara" value="Spara" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsRaffle['comp_id']; ?>" /></td>
      </tr>
    <input type="hidden" name="MM_RaffleDone" value="RaffleDone"/>
  </form>
    </table>
<?php
$stmt_rsRaffle->closeCursor();
}
?>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
    <p>&nbsp;</p>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statements and DB connection
$stmt_rsClubRegs->closeCursor();
$DBconnection = null; 
ob_end_flush();?>