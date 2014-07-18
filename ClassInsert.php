<?php
//Added convertion to ISO-8859-1 for string input into DB

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="L&auml;gga till t&auml;vlingsklass";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, lägga till tävlingsklasser, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
    <div class="feature">
        <div class="error">     
<?php
// Insert new class if button is clicked and all fields are validated to be correct
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_class")) {
    $class_fee = $_POST['class_fee'];	
    $class_weight_length = encodeToISO($_POST['class_weight_length']);
    $class_age = encodeToISO($_POST['class_age']);
    $output_form = 'no';
        
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
 }
    else {  
    $output_form = 'yes';
    }
  	if ($output_form == 'yes') {
        
        //Select current competitions
        mysql_select_db($database_DBconnection, $DBconnection);
        $query_rsActiveComp = "SELECT comp_id, comp_name FROM competition WHERE comp_current = 1 ORDER BY comp_start_date ASC";
        $rsActiveComp = mysql_query($query_rsActiveComp, $DBconnection) or die(mysql_error());
        $row_rsActiveComp = mysql_fetch_assoc($rsActiveComp);

        $colname_rsActiveCompetitions = "-1";
        if (isset($_GET['1'])) {
        $colname_rsActiveCompetitions = $_GET['1'];
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
do {  
?>
                <option value="<?php echo $row_rsActiveComp['comp_id']?>"<?php if (!(strcmp($row_rsActiveComp['comp_id'], $row_rsActiveComp['comp_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsActiveComp['comp_name']?></option>
                <?php
} while ($row_rsActiveComp = mysql_fetch_assoc($rsActiveComp));
  $rows = mysql_num_rows($rsActiveComp);
  if($rows > 0) {
      mysql_data_seek($rsActiveComp, 0);
	  $row_rsActiveComp = mysql_fetch_assoc($rsActiveComp);
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
              <input name="class_weight_length" type="text" id="class_weight_length" value="-" size="15" />
            </label></td>
          </tr>
          <tr>
            <td>&Aring;lder eller namn p&aring; klass</td>
            <td><input name="class_age" type="text" id="class_age" size="15" /></td>
          </tr>
          <tr>
            <td>Avgift</td>
            <td><input name="class_fee" type="int" id="class_fee" size="15" /></td>
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
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
  	} 
  	else if ($output_form == 'no') {        
            if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_class")) {
            $insertSQL = sprintf("INSERT INTO classes (comp_id, class_category, class_discipline, class_gender, class_gender_category, class_weight_length, class_age, class_fee) VALUES (%s,%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['comp_id'], "int"),
                       GetSQLValueString($_POST['class_category'], "text"),
                       GetSQLValueString($_POST['class_discipline'], "text"),
                       GetSQLValueString($_POST['class_gender'], "text"),
		       GetSQLValueString($_POST['class_gender_category'], "text"),
                       GetSQLValueString($class_weight_length, "text"),
                       GetSQLValueString($class_age, "text"),
                       GetSQLValueString($_POST['class_fee'], "int"));

            mysql_select_db($database_DBconnection, $DBconnection);
            $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
  
            $insertGoTo = "ClassesList.php";
                if (isset($_SERVER['QUERY_STRING'])) {
                $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
                $insertGoTo .= $_SERVER['QUERY_STRING'];
                }
            header(sprintf("Location: %s", $insertGoTo));
            }
        }
mysql_free_result($rsActiveComp);
ob_end_flush();?>