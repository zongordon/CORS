<?php
//Added class_discipline_variant to be able to select flag or point system for kata
//Removed $DBconnection = null; as it's included in footer.php
ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

$pagetitle="L&auml;gga till t&auml;vlingsklass";
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
        <div class="error">     
<?php
// Insert new class if button is clicked and all fields are validated to be correct
 if (filter_input(INPUT_POST,'MM_insert') && filter_input(INPUT_POST,'MM_insert') == 'new_class') {
    $comp_id = filter_input(INPUT_POST,'comp_id');         
    $class_category = filter_input(INPUT_POST,'class_category');             
    $class_discipline = filter_input(INPUT_POST,'class_discipline');         
    $class_discipline_variant = filter_input(INPUT_POST,'class_discipline_variant');         
    $class_gender = filter_input(INPUT_POST,'class_gender');         
    $class_gender_category = filter_input(INPUT_POST,'class_gender_category');
    if (filter_input(INPUT_POST, trim('class_weight_length')) === '') { 
        $class_weight_length = '-';            
    } 
    else {
        $class_weight_length = encodeToUtf8(filter_input(INPUT_POST,trim('class_weight_length'))); 
    }
    $class_age = encodeToUtf8(filter_input(INPUT_POST,trim('class_age')));    
    $class_fee = filter_input(INPUT_POST, trim('class_fee'));
    $class_match_time = filter_input(INPUT_POST, trim('class_match_time'));
    $output_form = 'no';
        
        if (empty($class_age)) {
      // $class_age is blank
      echo '<h3>Du gl&ouml;mde att fylla i &aring;lder f&ouml;r klassen!</h3>';
      $output_form = 'yes';
    }
    else {
        if (!ctype_digit($class_age) && !preg_match('/(\d{2})-(\d{2})/', $class_age)) {	
        // $class_age input is not numeric and doesn't match "nn-nn"
        echo '<h3>Det ska antingen vara bara siffror eller "nn-nn" f&ouml;r &aring;lder f&ouml;r klassen!</h3>';
        $output_form = 'yes';
        }     
    }
    //If age < 10 remove and then add a "0" for better sorting
    if ($class_age < 10) {
        $class_age = ltrim($class_age, 0);
        $class_age = '0'.$class_age;
    }       
    if (empty($class_fee)) {
      // $class_fee is blank
      echo '<h3>Du gl&ouml;mde att fylla i avgift f&ouml;r klassen!</h1>';
      $output_form = 'yes';
    }
    else {
        if (!ctype_digit($class_fee)) {	
        // $class_fee input is not numeric
        echo '<h3>Bara siffror &auml;r till&aring;tet i f&auml;ltet f&ouml;r avgift!</h1>';
        $output_form = 'yes';
        }     
    }
    if (empty($class_match_time)) {
      // $class_match_time is blank
      echo '<h3>Du gl&ouml;mde att fylla i ber&auml;knad matchtid f&ouml;r klassen!</h3>';
      $output_form = 'yes';
    }
    else {
        if (!is_numeric($class_match_time)) {	
        // $class_match_time is not numeric
        echo '<h3>Bara tal eller decimaltal (t.ex. 3.8) &auml;r till&aring;tet i f&auml;ltet f&ouml;r ber&auml;knad matchtid!</h3>';
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
<option value="<?php echo $row_rsCompetitions['comp_id']?>"<?php if (!(strcmp($row_rsCompetitions['comp_id'], $row_rsCompetitions['comp_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCompetitions['comp_name']?></option>
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
                <option value="Senior">Senior</option>
                <option value="U21">U21</option>
                <option value="Junior">Junior</option>
                <option value="Kadett">Kadett</option>
                <option value="Barn">Barn</option>
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Disciplin</td>
            <td valign="top"><p>
              <label>
                <input type="radio" name="class_discipline" value="Kata" id="class_discipline_0" checked="checked"/>
              Kata</label>
              <label>
              <input type="radio" name="class_discipline" value="Kumite" id="class_discipline_1" />
                  Kumite</label>
                <br />
            </p></td>
          </tr>
          <tr>
            <td>Katasystem</td>
            <td valign="top"><p>
              <label>
                <input type="radio" name="class_discipline_variant" value=0 id="class_discipline_variant_0" checked="checked" />
              Flaggor</label>
              <label>
                <input type="radio" name="class_discipline_variant" value=1 id="class_discipline_variant_1"/>
              Po&auml;ng</label>
              <br />
            </p></td>
          </tr>                    
          <tr>
            <td>T&auml;vlingsklass f&ouml;r (k&ouml;n) </td>
            <td valign="top"><p>
              <label>
                <input type="radio" name="class_gender" value="Man" id="class_gender_0" checked="checked"/>
                Man</label>
              <label>
                <input type="radio" name="class_gender" value="Kvinna" id="class_gender_1" />
              Kvinna</label>
              <label>
                <input type="radio" name="class_gender" value="Mix" id="class_gender_2" />
              Mix</label>              
            </p></td>
          </tr>
          <tr>
            <td>K&ouml;nskategori</td>
            <td><label>
              <select name="class_gender_category" id="class_gender_category">
                <option value="Herrar">Herrar</option>
                <option value="Damer">Damer</option>
                <option value="Pojkar">Pojkar</option>
                <option value="Flickor">Flickor</option>
                <option value="Mix">Mix</option>                
              </select>
            </label></td>
          </tr>
          <tr>
            <td>Vikt- eller l&auml;ngdkategori</td>
            <td><label>
              <input name="class_weight_length" type="text" id="class_weight_length" value="<?php $class_weight_length ?>" size="15" />
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lder eller namn p&aring; klass</td>
            <td><input name="class_age" type="text" id="class_age" value="<?php $class_age ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Avgift</td>
            <td><input name="class_fee" type="number" id="class_fee" value="<?php $class_fee ?>" size="15" /></td>
          </tr>
          <tr>
            <td>Ber&auml;knad matchtid</td>
            <td><input name="class_match_time" type="text" id="class_match_time" value="<?php $class_match_time ?>" size="15" /></td>
          </tr>          
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="new_class" id="new_class" value="Ny t&auml;vlingsklass" />
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
            $query1 = "INSERT INTO classes (comp_id, class_category, class_discipline, class_discipline_variant, class_gender, class_gender_category, class_weight_length, class_age, class_fee, class_match_time) VALUES (:comp_id, :class_category, :class_discipline, :class_discipline_variant, :class_gender, :class_gender_category, :class_weight_length, :class_age, :class_fee, :class_match_time)";
            $stmt = $DBconnection->prepare($query1);
            $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
            $stmt->bindValue(':class_category', $class_category, PDO::PARAM_STR);
            $stmt->bindValue(':class_discipline', $class_discipline, PDO::PARAM_STR);
            $stmt->bindValue(':class_discipline_variant', $class_discipline_variant, PDO::PARAM_INT);
            $stmt->bindValue(':class_gender', $class_gender, PDO::PARAM_STR);
            $stmt->bindValue(':class_gender_category', $class_gender_category, PDO::PARAM_STR);
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
