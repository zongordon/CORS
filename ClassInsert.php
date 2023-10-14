<?php
//Added class_repechage field in form when inserting the class

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

$pagetitle="L&auml;gga till t&auml;vlingsklass";
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
    <div class="feature">
        <div class="error">     
<?php
//Declare and initialise variables
$class_team ='';$class_category = '';$class_discipline = '';$class_discipline_variant = '';$class_gender = '';$class_gender_category = '';$class_weight_length = '';
$class_age = '';$class_fee = '';$class_match_time = '';$class_repechage = '';
// Insert new class if button is clicked and all fields are validated to be correct
 if (filter_input(INPUT_POST,'MM_insert') && filter_input(INPUT_POST,'MM_insert') == 'new_class') {
    $comp_id = filter_input(INPUT_POST,'comp_id');         
    $class_team = filter_input(INPUT_POST,'class_team');
    $class_category = filter_input(INPUT_POST,'class_category');             
    $class_discipline = filter_input(INPUT_POST,'class_discipline');         
    $class_discipline_variant = filter_input(INPUT_POST,'class_discipline_variant');         
    $class_gender = filter_input(INPUT_POST,'class_gender');         
    $class_gender_category = filter_input(INPUT_POST,'class_gender_category');
    $class_repechage = filter_input(INPUT_POST,'class_repechage');
    if (filter_input(INPUT_POST, trim('class_weight_length')) === '') { 
        $class_weight_length = '-';            
    } 
    else {
        $class_weight_length = encodeToUtf8(filter_input(INPUT_POST,trim('class_weight_length'))); 
    }
    $class_age = encodeToUtf8(filter_input(INPUT_POST,trim('class_age')));    
    $class_fee = filter_input(INPUT_POST, trim('class_fee'));
    $class_match_time = str_replace(',','.',filter_input(INPUT_POST, trim('class_match_time')));

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
            require('Connections/DBconnection.php');                           
            //Select all competitions
            $query1 = "SELECT comp_id, comp_name FROM competition ORDER BY comp_start_date ASC";
            $stmt_rsCompetitions = $DBconnection->query($query1);
            $totalRows_rsCompetitions = $stmt_rsCompetitions->rowCount(); 
            }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }                     
?>
        </div>
<h3>Skapa en ny t&auml;vlingsklass f&ouml;r att kunna anm&auml;la t&auml;vlande till</h3>
      <p>Fyll i formul&auml;ret och klicka p&aring; knappen &quot;Ny t&auml;vlingsklass&quot;.</p>    
      <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="new_class" id="new_class">
        <table width="400" border="0">
          <tr>
            <td>T&auml;vling</td>
            <td><label>
              <select name="comp_id" id="comp_id">
<?php
while($row_rsCompetitions = $stmt_rsCompetitions->fetch(PDO::FETCH_ASSOC)) {  
?>
<option value="<?php echo $row_rsCompetitions['comp_id']?>"<?php if (!(strcmp($row_rsCompetitions['comp_id'], $comp_id))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCompetitions['comp_name']?></option>
<?php
}
?>
</select>
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lderskategori</td>
            <td><label>
              <select name="class_category" id="class_category">
                <option value="Senior" <?php if (!(strcmp("Senior", $class_category))) {echo "selected=\"selected\"";} ?>>Senior</option>
                <option value="U21" <?php if (!(strcmp("U21", $class_category))) {echo "selected=\"selected\"";} ?>>U21</option>
                <option value="Junior" <?php if (!(strcmp("Junior", $class_category))) {echo "selected=\"selected\"";} ?>>Junior</option>
                <option value="Kadett" <?php if (!(strcmp("Kadett", $class_category))) {echo "selected=\"selected\"";} ?>>Kadett</option>
                <option value="Barn" <?php if (!(strcmp("Barn", $class_category))) {echo "selected=\"selected\"";} ?>>Barn</option>
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Typ av klass</td>
            <td valign="top">
              <label>
                <input <?php if (!(strcmp($class_team,0))) {echo "checked=\"checked\"";} ?> type="radio" name="class_team" value=0 id="class_team_0" />                
              Individuell</label>
              <label>
              <input <?php if (!(strcmp($class_team,1))) {echo "checked=\"checked\"";} ?> type="radio" name="class_team" value=1 id="class_team_1" />                              
                  Lag</label>
                <br />
            </td>
          <tr>
            <td>Disciplin</td>
            <td valign="top">
              <label>
                <input <?php if (!(strcmp($class_discipline,"Kata"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kata" id="class_discipline_0" />                
              Kata</label>
              <label>
              <input <?php if (!(strcmp($class_discipline,"Kumite"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline" value="Kumite" id="class_discipline_1" />                              
                  Kumite</label>
                <br />
            </td>
          </tr>
          <tr>
            <td>Katasystem</td>
            <td valign="top">
              <label>
               <input <?php if (!(strcmp($class_discipline_variant,0))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline_variant" value=0 id="class_discipline_variant_0" />                   
              Flaggor</label>
              <label>
               <input <?php if (!(strcmp($class_discipline_variant,1))) {echo "checked=\"checked\"";} ?> type="radio" name="class_discipline_variant" value=1 id="class_discipline_variant_1" />                                     
              Po&auml;ng</label>
              <br />
            </td>
          </tr>                    
          <tr>
            <td>T&auml;vlingsklass f&ouml;r (k&ouml;n) </td>
            <td valign="top">
              <label>
               <input <?php if (!(strcmp($class_gender,"Man"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Man" id="class_gender_0" />                                                       
                Man</label>
              <label>
               <input <?php if (!(strcmp($class_gender,"Kvinna"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Kvinna" id="class_gender_1" />                                                                         
              Kvinna</label>
              <label>
               <input <?php if (!(strcmp($class_gender,"Mix"))) {echo "checked=\"checked\"";} ?> type="radio" name="class_gender" value="Mix" id="class_gender_2" />                                                                                           
              Mix</label>              
            </td>
          </tr>
          <tr>
            <td>K&ouml;nskategori</td>
            <td><label>
              <select name="class_gender_category" id="class_gender_category">
                <option value="Herrar" <?php if (!(strcmp("Herrar", $class_gender_category))) {echo "selected=\"selected\"";} ?>>Herrar</option>
                <option value="Damer" <?php if (!(strcmp("Damer", $class_gender_category))) {echo "selected=\"selected\"";} ?>>Damer</option>
                <option value="Pojkar" <?php if (!(strcmp("Pojkar", $class_gender_category))) {echo "selected=\"selected\"";} ?>>Pojkar</option>
                <option value="Flickor" <?php if (!(strcmp("Flickor", $class_gender_category))) {echo "selected=\"selected\"";} ?>>Flickor</option>
                <option value="Mix" <?php if (!(strcmp("Mix", $class_gender_category))) {echo "selected=\"selected\"";} ?>>Mix</option>    
              </select>
            </label></td>
          </tr>
          <tr>
            <td>&Aring;terkval</td>
            <td><label>
              <select name="class_gender_category" id="class_gender_category">
                <option value=0 <?php if (!(strcmp(0, $class_repechage))) {echo "selected=\"selected\"";} ?>>Nej</option>
                <option value=1 <?php if (!(strcmp(1, $class_repechage))) {echo "selected=\"selected\"";} ?>>Ja</option>
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Vikt- eller l&auml;ngdkategori</td>
            <td><label>
              <input name="class_weight_length" type="text" id="class_weight_length" value="<?php echo $class_weight_length ?>" size="15" />
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lder eller namn p&aring; klass</td>
            <td><input name="class_age" type="text" id="class_age" value="<?php echo $class_age ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Avgift</td>
            <td><input name="class_fee" type="number" id="class_fee" value="<?php echo $class_fee ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Ber&auml;knad matchtid (heltal eller decimaltal)</td>
            <td><input name="class_match_time" type="text" id="class_match_time" value="<?php echo $class_match_time ?>" size="15" /></td>
          </tr>          
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="new_class" class= "button" id="new_class" value="Ny t&auml;vlingsklass" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="new_class" />
    </form>
<?php
  	} 
  	else if ($output_form == 'no') {
            //Catch anything wrong with query
            try {
            //INSERT new class in the database    
            require('Connections/DBconnection.php');             
            $query1 = "INSERT INTO classes (comp_id, class_team, class_category, class_discipline, class_discipline_variant, class_gender, class_gender_category, class_repechage,"
                    . "class_weight_length, class_age, class_fee, class_match_time) "
                    . "VALUES (:comp_id, :class_team, :class_category, :class_discipline, :class_discipline_variant, :class_gender, :class_gender_category, :class_repechage,"
                    . ":class_weight_length, :class_age, :class_fee, :class_match_time)";
            $stmt = $DBconnection->prepare($query1);
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
            $stmt->bindValue(':class_match_time', $class_match_time, PDO::PARAM_INT);
            $stmt->execute();
            }   
            //Catch eny error
            catch(PDOException $ex) {  
                echo "An Error occured: ".$ex->getMessage();
            }
                $insertGoTo = "ClassesList.php";
                if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
                $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
                $insertGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
                }
                header(sprintf("Location: %s", $insertGoTo));

            //Kill statement
            $stmt->closeCursor();
        } ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>
