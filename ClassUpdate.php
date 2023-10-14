<?php
//Added class_repechage field in form to update the class

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
// require Class for validation of forms
require_once 'Classes/Validate.php';
// Includes HTML Head
include_once('includes/header.php');
//Includes Several code functions
include_once('includes/functions.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");
//Includes Restrict access code function
include_once('includes/restrict_access.php');?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">    
    <div class ="feature">
        <div class="error">
<?php 
//Declare and initialise variables
$class_team ='';$class_category = '';$class_discipline = '';$class_discipline_variant = '';$class_gender = '';$class_gender_category = '';$class_weight_length = '';$class_age = '';$class_fee = '';$class_match_time = '';
// Update class data if button is clicked and all fields are validated to be correct
 if (filter_input(INPUT_POST,'MM_update') === 'update_class') {
    $colname_rsClass = filter_input(INPUT_POST,'class_id');
    $comp_id = filter_input(INPUT_POST,'comp_id');        
    $class_team = filter_input(INPUT_POST,'class_team');    
    $class_category = filter_input(INPUT_POST,'class_category');             
    $class_discipline = filter_input(INPUT_POST,'class_discipline');         
    $class_discipline_variant = filter_input(INPUT_POST,'class_discipline_variant');         
    $class_gender = filter_input(INPUT_POST,'class_gender');         
    $class_gender_category = filter_input(INPUT_POST,'class_gender_category');
    $class_repechage = filter_input(INPUT_POST,'class_repechage');
    if (filter_input(INPUT_POST, trim('class_weight_length')) == '') { 
        $class_weight_length = '-';            
    } 
    else {
        $class_weight_length = encodeToUtf8(filter_input(INPUT_POST,trim('class_weight_length'))); 
    }
    $class_age = encodeToUtf8(filter_input(INPUT_POST,trim('class_age')));    
    $class_fee = filter_input(INPUT_POST, trim('class_fee'));
    $class_match_time = str_replace(',','.',filter_input(INPUT_POST, trim('class_match_time')));
echo 'Kön: '.$class_gender.'<br>';
echo 'vikt-/l&auml;ngdkategori: '.$class_weight_length.'<br>';
    $val = new Validation();
    $val->name('typ av klass')->value($class_team)->pattern('int')->required();
    $val->name('disciplin')->value($class_discipline)->pattern('alpha')->required();
    if($class_discipline === 'Kata'){
    $val->name('katasystem')->value($class_discipline_variant)->pattern('int')->required();
    }else{
    $class_discipline_variant = 2;    
    }
    $val->name('k&ouml;n')->value($class_gender)->pattern('text')->required();    
    $val->name('vikt-/l&auml;ngdkategori')->value($class_weight_length)->pattern('text');
    $val->name('avgift f&ouml;r klassen')->value($class_fee)->pattern('int')->required();
    $val->name('ber&auml;knad matchtid f&ouml;r klassen')->value($class_match_time)->pattern('float')->required();
    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }
        if (!ctype_digit($class_age) && !preg_match('/(\d{2})-(\d{2})/', $class_age)) {	
        // $class_age input is not numeric and doesn't match "nn-nn"
        echo '<h3>Det ska antingen vara bara siffror eller "nn-nn" f&ouml;r &aring;lder f&ouml;r klassen!</h3>';
        $output_form = 'yes';
        }      
    //If age < 10 remove and then add a "0" for better sorting
    if ($class_age < 10) {
        $class_age = ltrim($class_age, 0);
        $class_age = '0'.$class_age;
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
        $query1 = "SELECT c.class_id, c.comp_id, c.class_team, c.class_category, c.class_discipline, c.class_discipline_variant, c.class_gender, "
                . "c.class_gender_category, c.class_weight_length, c.class_age, c.class_fee, c.class_match_time, c.class_repechage, co.comp_name "
                . "FROM classes AS c JOIN competition AS co ON co.comp_id = c.comp_id WHERE class_id = :class_id";
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
<option value="<?php echo $row_rsClass['comp_id']?>"<?php if (!(strcmp($row_rsClass['comp_id'], $row_rsClass['comp_id']))) {
    echo "selected=\"selected\"";} ?>><?php echo $row_rsClass['comp_name']?></option>
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
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Typ av klass</td>
            <td valign="top">
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_team'],0))) {echo "checked=\"checked\"";} ?> type="radio" name="class_team" value=0 id="class_team_0" />                
              Individuell</label>
              <label>
              <input <?php if (!(strcmp($row_rsClass['class_team'],1))) {echo "checked=\"checked\"";} ?> type="radio" name="class_team" value=1 id="class_team_1" />                              
                  Lag</label>
                <br />
            </td>
          <tr>          
          <tr>
            <td>Disciplin</td>
            <td valign="top">
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_discipline'],"Kata"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kata" id="class_discipline_0" />
              Kata</label>
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_discipline'],"Kumite"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kumite" id="class_discipline_1" />
                Kumite</label>
              <br />
            </td>
          </tr>
          <tr>
            <td>Katasystem</td>
            <td valign="top">
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_discipline_variant'],0))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline_variant" value=0 id="class_discipline_variant_0" />
              Flaggor</label>
              <label>
                <input <?php if (!(strcmp($row_rsClass['class_discipline_variant'],1))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline_variant" value=1 id="class_discipline_variant_1" />
              Po&auml;ng</label>
              <br />
            </td>
          </tr>          
          <tr>
            <td>T&auml;vlingsklass f&ouml;r (k&ouml;n)</td>
            <td valign="top">
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
            </td>
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
  </select>
</label></td>
          </tr>
          <tr>
              <td>&Aring;terkval</td>
<td valign="top"><label>
  <select name="class_repechage" id="class_repechage">
    <option value=1 <?php if (!(strcmp(1, $row_rsClass['class_repechage']))) {echo "selected=\"selected\"";} ?>>Ja</option>
    <option value=0 <?php if (!(strcmp(0, $row_rsClass['class_repechage']))) {echo "selected=\"selected\"";} ?>>Nej</option>
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
            <td><input name="class_age" type="text" id="class_age" value="<?php echo $row_rsClass['class_age']; ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Avgift</td>
            <td><input name="class_fee" type="number" id="class_fee" value="<?php echo $row_rsClass['class_fee']; ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Ber&auml;knad matchtid</td>
            <td><input name="class_match_time" type="text" id="class_match_time" value="<?php echo $row_rsClass['class_match_time']; ?>" size="15" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="new_class" class= "button" id="new_class" value="Uppdatera" />
            </label></td>
          </tr>
        </table>
         <input name="class_id" type="hidden" id="class_id" value="<?php echo $row_rsClass['class_id']; ?>" />
        <input type="hidden" name="MM_update" value="update_class" />
    </form>
<?php
        }
 	else if ($output_form === 'no') {   
                 //Catch anything wrong with query
                try {
                require('Connections/DBconnection.php');
                //UPDATE selected Class
                $updateSQL = "UPDATE classes SET comp_id = :comp_id, 
                class_team = :class_team, 
                class_category = :class_category, 
                class_discipline = :class_discipline, 
                class_discipline_variant = :class_discipline_variant, 
                class_gender = :class_gender, 
                class_gender_category = :class_gender_category, 
                class_repechage = :class_repechage,
                class_weight_length = :class_weight_length, 
                class_age = :class_age, 
                class_fee = :class_fee,
                class_match_time = :class_match_time
                WHERE class_id = :class_id"; 
                $stmt = $DBconnection->prepare($updateSQL);                                 
                $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
                $stmt->bindValue(':class_team', $class_team, PDO::PARAM_INT);
                $stmt->bindValue(':class_category', $class_category, PDO::PARAM_STR);
                $stmt->bindValue(':class_discipline', $class_discipline, PDO::PARAM_STR);
                $stmt->bindValue(':class_discipline_variant', $class_discipline_variant, PDO::PARAM_INT);
                $stmt->bindValue(':class_gender', $class_gender, PDO::PARAM_STR);
                $stmt->bindValue(':class_gender_category', $class_gender_category, PDO::PARAM_STR);
                $stmt->bindValue(':class_repechage', $class_repechage, PDO::PARAM_INT);
                $stmt->bindValue(':class_weight_length', $class_weight_length, PDO::PARAM_STR);            
                $stmt->bindValue(':class_age', $class_age, PDO::PARAM_STR);            
                $stmt->bindValue(':class_fee', $class_fee, PDO::PARAM_INT);                        
                $stmt->bindValue(':class_match_time', $class_match_time, PDO::PARAM_STR);                        
                $stmt->bindValue(':class_id', $colname_rsClass, PDO::PARAM_INT);
                $stmt->execute();
                }   
                //Catch eny error
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
//Kill statement
$stmt_rsClass->closeCursor();?>
    </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>




