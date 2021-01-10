<?php 
//Added class for styling button in css file

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Catch comp_id sent from previous page to select competition classes to copy if $colname_rsCompetition <> NULL
$colname_rsCompetition = filter_input(INPUT_GET, 'comp_id');
if ($colname_rsCompetition <> NULL) {
//Catch anything wrong with query
    try {
        //Select all classes for selected competition and the competition's name
        require('Connections/DBconnection.php');           
        $query1 = "SELECT com.comp_name, cl.class_id, cl.class_team, cl.class_category, cl.class_discipline, cl.class_discipline_variant, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age, cl.class_fee, cl.class_match_time FROM classes AS cl INNER JOIN competition AS com USING (comp_id) WHERE comp_id = :comp_id ORDER BY class_discipline, class_gender, class_age, class_weight_length, class_gender_category";
        $stmt_rsClasses = $DBconnection->prepare($query1);
        $stmt_rsClasses->execute(array(':comp_id' => $colname_rsCompetition));
        $row_rsClasses = $stmt_rsClasses->fetchAll(PDO::FETCH_ASSOC);        
        $totalRows_rsClasses = $stmt_rsClasses->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured with query1: ".$ex->getMessage();
    }   

//Catch anything wrong with query
    try {
        //Select all competitions except the one from where the classes are copied           
        $query2 = "SELECT comp_name, comp_id FROM competition WHERE comp_id <> :comp_id";
        $stmt_rsOtherCompetitions = $DBconnection->prepare($query2);
        $stmt_rsOtherCompetitions->execute(array(':comp_id' => $colname_rsCompetition));
    }   
    catch(PDOException $ex) {
        echo "An Error occured with query2: ".$ex->getMessage();
    }
    
    //Catch anything wrong with query
    try {
        //Select the competition name from where the classes are copied           
        $query3 = "SELECT comp_name FROM competition WHERE comp_id = :comp_id";
        $stmt_rsCompetition = $DBconnection->prepare($query3);
        $stmt_rsCompetition->execute(array(':comp_id' => $colname_rsCompetition));
        $row_rsCompetition = $stmt_rsCompetition->fetch(PDO::FETCH_ASSOC);
    }   
    catch(PDOException $ex) {
        echo "An Error occured with query3: ".$ex->getMessage();
    }
}
$pagetitle="Kopiera T&auml;vlingklasser";
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
  <?php if ($totalRows_rsClasses === 0) { // Show if recordset empty ?>
    <p>Det finns inga t&auml;vlingsklasser att visa!</p>
  <?php } // Show if recordset empty ?>
<?php 
if ($totalRows_rsClasses > 0 OR (filter_input(INPUT_POST,'MM_CopyClasses') === 'copy_classes')) { // Show if recordset not empty OR "Kopiera" button is clicked and classes chosen for copy?>
    <h3>Befintliga t&auml;vlingsklasser i <?php echo $row_rsCompetition['comp_name'] ?></h3>
    <p>Kopiera t&auml;vlingsklasser med ifyllda checkboxar genom att klicka p&aring; "Kopiera"!</p>  
      <div class="error">    
<?php    
//If "Kopiera" button is clicked then validate and execute the below
if (filter_input(INPUT_POST,'MM_CopyClasses') === 'copy_classes') {
$output_form = 'no';

        if (filter_input(INPUT_POST,'copy_class') === "") {
        // all copy_class fields are blank
        echo '<h3>Du gl&ouml;mde att v&auml;lja n&aring;gon klass att kopiera!</h3><br/>';            
        $output_form = 'yes';    
        }
}
else {  
   $output_form = 'yes';
}    
if ($output_form === 'yes') {    
?>
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="copy_classes" id="copy_classes">
    <table width="100%" border="1">
      <tr>
        <td><strong>Type</strong></td>
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>Matchtid</strong></td>
        <td><strong>Kopiera</strong></td>
      </tr>
<?php //reset ($row_rsClasses);
      foreach($row_rsClasses As $row_rsClass) { ?>
  <tr>
          <td><?php if($row_rsClass['class_team'] === 1) { echo 'Lag';} else{ echo 'Individuell';} ?></td>
          <td><?php echo $row_rsClass['class_discipline']; ?></td>
          <td><?php echo $row_rsClass['class_gender_category']; ?></td>
          <td><?php echo $row_rsClass['class_category']; ?></td>
          <td><?php echo $row_rsClass['class_age']; ?></td>
          <td><?php echo $row_rsClass['class_weight_length']; ?></td>
          <td><?php echo $row_rsClass['class_match_time']; ?></td>
          <td><label>
        <input name="copy_class[]" type="checkbox" id="copy_class[]" value="<?php echo $row_rsClass['class_id'];?>" checked />
              </label>
          </td>
  </tr>
<?php } ?>
    <tr>
      <td valign="top">V&auml;lj t&auml;vling att kopiera till:</td>
      <td><label>
        <select name="to_comp_id" id="to_comp_id">
<?php
while($row_rsOtherCompetitions = $stmt_rsOtherCompetitions->fetch(PDO::FETCH_ASSOC)) {  
?>
<option value="<?php echo $row_rsOtherCompetitions['comp_id']?>"<?php if (!(strcmp($row_rsOtherCompetitions['comp_id'], $colname_rsCompetition))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsOtherCompetitions['comp_name']?></option>
<?php
} ?>
        </select>
      </label></td>
      <td>
      <input type="hidden" name="MM_CopyClasses" value="copy_classes" />
      <input type="submit" name="copy_classes" class= "button" id="copy_classes" value="Kopiera" />
      </td>
    </tr>
    </table>
    </form>
      </div>              
<?php 
}       
//If the form shall not be displayed execute below    
    else if ($output_form === 'no') {
          //If the "Kopiera" button is clicked and classes chosen for copy, then copy those classes to the selected competition  
          if (filter_input(INPUT_POST,'MM_CopyClasses') === 'copy_classes') {
            foreach($_POST['copy_class'] as $class_id) {              
            $comp_id = filter_input(INPUT_POST,'to_comp_id');                
             //Catch anything wrong with query
            try {
            //INSERT new class in the database    
            require('Connections/DBconnection.php');
            $insertSQL = "INSERT INTO classes (comp_id, class_team, class_category, class_discipline, class_discipline_variant ,class_gender_category, class_gender, class_weight_length, class_age, class_fee, class_match_time)
            SELECT :comp_id AS comp_id, class_team, class_category, class_discipline, class_discipline_variant, class_gender_category, class_gender, class_weight_length, class_age, class_fee, class_match_time  
            FROM classes WHERE class_id = :class_id";
            $stmt = $DBconnection->prepare($insertSQL);
            $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
            $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }                  
              $updateGoTo = "ClassesList.php";
                    if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
                    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
                    $updateGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
                    }        
              header(sprintf("Location: %s", $updateGoTo));
            //Kill statement
            $stmt->closeCursor();                          
            } 
          }
    }
} // Show if recordset of classes not empty 

?>
  </div>
  <div class="story">
    <p>&nbsp;</p>
  </div>
</div>
<?php
//Kill statements
$stmt_rsClasses->closeCursor();
$stmt_rsCompetition->closeCursor();
$stmt_rsOtherCompetitions->closeCursor();
include("includes/footer.php");?>
</body>
</html>
<?php
ob_end_flush();
?> 