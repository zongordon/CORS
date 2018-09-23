<?php
//Added $colname_rsClass = filter_input(INPUT_POST,'class_id') to update the class as that only worked locally and not on the testsite

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Fetch the selected Class
$colname_rsClass = filter_input(INPUT_GET, 'class_id');
    
$pagetitle="Uppdatera t&auml;vlingsklass";
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
    <div class ="feature">
        <div class="error">
<?php 
// Update class data if button is clicked and all fields are validated to be correct
 if (filter_input(INPUT_POST,'MM_insert') == 'update_class') {
    $colname_rsClass = filter_input(INPUT_POST,'class_id');
    $comp_id = filter_input(INPUT_POST,'comp_id');         
    $class_category = filter_input(INPUT_POST,'class_category');             
    $class_discipline = filter_input(INPUT_POST,'class_discipline');         
    $class_gender = filter_input(INPUT_POST,'class_gender');         
    $class_gender_category = filter_input(INPUT_POST,'class_gender_category');
    if (filter_input(INPUT_POST, trim('class_weight_length')) == '') { 
        $class_weight_length = '-';            
    } 
    else {
        $class_weight_length = encodeToUtf8(filter_input(INPUT_POST,trim('class_weight_length'))); 
    }
    $class_age = encodeToUtf8(filter_input(INPUT_POST,trim('class_age')));    
    $class_fee = filter_input(INPUT_POST, trim('class_fee'));
    $output_form = 'no';
        
    if (empty($class_fee)) {
      // $class_fee is blank
      echo '<h1>Du gl&ouml;mde att fylla i avgift f&ouml;r klassen!</h1>';
      $output_form = 'yes';
    }
    else {
        if (!ctype_digit($class_fee)) {	
        // $class_fee input is not numeric
        echo '<h1>Bara siffror &auml;r till&aring;tet i f&auml;ltet f&ouml;r avgift!</h1>';
        $output_form = 'yes';
        }     
    }
 }
 else {  
    $output_form = 'yes';
 }
  	if ($output_form == 'yes') {
   //Catch anything wrong with query
    try {
        //Select Class data for selected class
        require('Connections/DBconnection.php');           
        $query1 = "SELECT c.class_id, c.comp_id, c.class_category, c.class_discipline, c.class_gender, c.class_gender_category, c.class_weight_length, c.class_age, c.class_fee, co.comp_name FROM classes AS c JOIN competition AS co ON co.comp_id = c.comp_id WHERE class_id = :class_id";
        $stmt_rsClass = $DBconnection->prepare($query1);
        $stmt_rsClass->execute(array(':class_id' => $colname_rsClass));
        $row_rsClass = $stmt_rsClass->fetch(PDO::FETCH_ASSOC);
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }                         
?>
        </div>
<h3>&Auml;ndra en t&auml;vlingsklass f&ouml;r att kunna anm&auml;la t&auml;vlande till</h3>
    <p>G&ouml;r &auml;ndringar i formul&auml;ret och klicka p&aring; knappen &quot;Uppdatera&quot;.</p>    
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="update_class" id="update_class">
        <table width="400" border="0">
          <tr>
            <td>T&auml;vling</td>
            <td><label>
<select name="comp_id" id="comp_id">
<option value="<?php echo $row_rsClass['comp_id']?>"<?php if (!(strcmp($row_rsClass['comp_id'], $row_rsClass['comp_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['comp_name']?></option>
</select>
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lderskategori</td>
            <td><label>
              <select name="class_category" id="class_category">
                <option value="Senior" <?php if (!(strcmp("Senior", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Senior</option>
                <option value="U21" <?php if (!(strcmp("U21", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>U21</option>
                <option value="Junior" <?php if (!(strcmp("Junior", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Junior</option>
                <option value="Kadett" <?php if (!(strcmp("Kadett", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Kadett</option>
                <option value="Barn" <?php if (!(strcmp("Barn", $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>>Barn</option>
<option value="<?php echo $row_rsClass['class_category']?>"<?php if (!(strcmp($row_rsClass['class_category'], $row_rsClass['class_category']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['class_category']?></option>
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Disciplin</td>
            <td valign="top"><p>
              <label>
<input <?php if (!(strcmp($row_rsClass['class_discipline'],"Kata"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kata" id="class_discipline_0" />
Kata</label>
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_discipline'],"Kumite"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kumite" id="class_discipline_1" />
                Kumite</label>
              <br />
            </p></td>
          </tr>
          <tr>
            <td>T&auml;vlingsklass f&ouml;r (k&ouml;n)</td>
            <td valign="top"><p>
              <label>
                  <input <?php if (!(strcmp($row_rsClass['class_gender'],"Man"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Man" id="class_gender_0" />
            Man</label>
              <label>
  <input <?php if (!(strcmp($row_rsClass['class_gender'],"Kvinna"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Kvinna" id="class_gender_1" />
                Kvinna</label>
              <label>
  <input <?php if (!(strcmp($row_rsClass['class_gender'],"Mix"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Mix" id="class_gender_2" />
                Mix</label>
              <br />
            </p></td>
          </tr>
          <tr>
            <td>K&ouml;nskategori</td>
<td valign="top"><label>
  <select name="class_gender_category" id="class_gender_category">
    <option value="Herrar" <?php if (!(strcmp("Herrar", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Herrar</option>
    <option value="Damer" <?php if (!(strcmp("Damer", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Damer</option>
    <option value="Pojkar" <?php if (!(strcmp("Pojkar", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Pojkar</option>
    <option value="Flickor" <?php if (!(strcmp("Flickor", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Flickor</option>
    <option value="Mix" <?php if (!(strcmp("Mix", $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>>Mix</option>    
<option value="<?php echo $row_rsClass['class_gender_category']?>"<?php if (!(strcmp($row_rsClass['class_gender_category'], $row_rsClass['class_gender_category']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['class_gender_category']?></option>
  </select>
</label></td>
          </tr>
          <tr>
            <td>Vikt- eller l&auml;ngdkategori</td>
            <td><label>
              <input name="class_weight_length" type="text" id="class_weight_length" value="<?php echo $row_rsClass['class_weight_length']; ?>" size="15" />
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lder eller namn p&aring; klass</td>
            <td><input name="class_age" type="text" id="class_age" value="<?php echo ltrim($row_rsClass['class_age']); ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Avgift</td>
            <td><input name="class_fee" type="int" id="class_fee" value="<?php echo ltrim($row_rsClass['class_fee']); ?>" size="15" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="new_class" id="new_class" value="Uppdatera" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="update_class" />
        <input name="class_id" type="hidden" id="class_id" value="<?php echo $row_rsClass['class_id']; ?>" />
        <input type="hidden" name="MM_update" value="update_class" />
        <input type="hidden" name="MM_update" value="update_class" />
    </form>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statements and DB connection
$stmt_rsClass->closeCursor();
        }
 	else if ($output_form == 'no') {        
            if (filter_input(INPUT_POST,'MM_update') == 'update_class') {
                //Catch anything wrong with query
                try {
                require('Connections/DBconnection.php');
                //UPDATE selected Class
                $updateSQL = "UPDATE classes SET comp_id = :comp_id, 
                class_category = :class_category, 
                class_discipline = :class_discipline, 
                class_gender = :class_gender, 
                class_gender_category = :class_gender_category, 
                class_weight_length = :class_weight_length, 
                class_age = :class_age, 
                class_fee = :class_fee
                WHERE class_id = :class_id"; 
                $stmt = $DBconnection->prepare($updateSQL);                                 
                $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
                $stmt->bindValue(':class_category', $class_category, PDO::PARAM_STR);
                $stmt->bindValue(':class_discipline', $class_discipline, PDO::PARAM_STR);
                $stmt->bindValue(':class_gender', $class_gender, PDO::PARAM_STR);
                $stmt->bindValue(':class_gender_category', $class_gender_category, PDO::PARAM_STR);
                $stmt->bindValue(':class_weight_length', $class_weight_length, PDO::PARAM_STR);            
                $stmt->bindValue(':class_age', $class_age, PDO::PARAM_STR);            
                $stmt->bindValue(':class_fee', $class_fee, PDO::PARAM_INT);                        
                $stmt->bindValue(':class_id', $colname_rsClass, PDO::PARAM_INT);
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
ob_end_flush();
?>