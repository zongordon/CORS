<?php
//Added function to only have one current competition (active) at a time
//Removed mb_convert_case from Competition Name
ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="L&auml;gga till t&auml;vling";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, lägga till tävling, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
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
//Initiate global variables
global $comp_name, $comp_start_date, $comp_end_date, $comp_end_reg_date, $comp_current, $comp_max_regs;

//Validate the form if button is clicked
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_comp")) {
    $comp_name = encodeToISO($_POST['comp_name']);
    $comp_start_date = $_POST['comp_start_date'];
    $comp_end_date = $_POST['comp_end_date'];
    $comp_end_reg_date = $_POST['comp_end_reg_date'];
    $comp_max_regs = $_POST['comp_max_regs'];
    $comp_current = $_POST['comp_current'];
    $output_form = 'no';

    if (empty($comp_name)) {
      // $comp_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens namn!</h3>';
      $output_form = 'yes';
    }
    if (empty($comp_start_date)) {
      // $comp_start_date is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens startdatum!</h3>';
      $output_form = 'yes';
    }
    if (!empty($comp_start_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $comp_start_date)) {
    // $comp_start_date is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; t&auml;vlingens startdatum!</h3>';
    $output_form = 'yes';
    }	    
    if (empty($comp_end_date)) {
      // $comp_end_date is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens slutdatum!</h3>';
      $output_form = 'yes';
    }
    if (!empty($comp_end_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $comp_end_date)) {
    // $comp_end_date is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; t&auml;vlingens slutdatum!</h3>';
    $output_form = 'yes';
    }	        
    if (empty($comp_end_reg_date)) {
      // $comp_end_reg_date is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens sista anm&auml;lningsdag!</h3>';
      $output_form = 'yes';
    }
    if (!empty($comp_end_reg_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $comp_end_reg_date)) {
    // $comp_end_reg_date is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; sista anm&auml;lningsdag!</h3>';
    $output_form = 'yes';
    }	            
    if (empty($comp_max_regs)) {
      // $comp_max_regs is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens maximala antal anm&auml;lningar!</h3>';
      $output_form = 'yes';
    }    
    if (!empty($comp_max_regs ) && !is_numeric($comp_max_regs)) {
    // $comp_max_regs is not a number
    echo '<h3>Du anv&auml;nde annat format &auml;n siffror f&ouml;r max antal anm&auml;lningar!</h3>';
    $output_form = 'yes';
    }	                
} 

  else {
    $output_form = 'yes';
  	}

  	if ($output_form == 'yes') {
?>  
       </div>         
<h3>Skapa en ny t&auml;vling</h3>
      <p>Fyll i formul&auml;ret och klicka p&aring; knappen &quot;Ny t&auml;vling&quot;.</p>
      <form id="new_comp" name="new_comp" method="post" action="<?php echo $editFormAction; ?>">
        <table width="400" border="0">
          <tr>
            <td>T&auml;vlingens namn</td>
            <td><label>
              <input type="text" name="comp_name" id="comp_name" value="<?php echo $comp_name ?>"/>
            </label></td>
          </tr>
          <tr>
            <td>Startdatum</td>
            <td><label>
              <input type="text" name="comp_start_date" id="comp_start_date" value="<?php echo $comp_start_date ?>"/>
            </label></td>
          </tr>
          <tr>
            <td>Slutdatum</td>
            <td><label>
              <input type="text" name="comp_end_date" id="comp_end_date" value="<?php echo $comp_end_date ?>"/>
            </label></td>
          </tr>
          <tr>
            <td>Sista anm&auml;lningsdag</td>
            <td><label>
              <input type="text" name="comp_end_reg_date" id="comp_end_reg_date" value="<?php echo $comp_end_reg_date ?>"/>
            </label></td>
          </tr>
          <tr>
            <td>Max antal anm&auml;lningar</td>
            <td><label>
            <input name="comp_max_regs" type="text" id="comp_max_regs" value="<?php echo $comp_max_regs ?>"/>              
            </label></td>
          </tr>
          <tr>
            <td>Aktiv t&auml;vling</td>
            <td><label>
            <input name="comp_current" type="checkbox" id="comp_current" value="1" <?php if ($comp_current == 1){ echo 'checked';} elseif ($comp_current == 0) { echo 'unchecked';}?> />
            </label></td>
          </tr>          
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="new_competition" id="new_competition" value="Ny t&auml;vling" />
            </label></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="new_comp" />
    </form>
    <?php
  	} 
	//Save the competition information
  	else if ($output_form == 'no') {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_comp")) {
    // Set all competitions first to non-current (0) if the new competition shall be current
    if ($_POST["comp_current"] == 1) {
       $resetSQL = sprintf("UPDATE competition SET comp_current = 0");
       mysql_select_db($database_DBconnection, $DBconnection);
       $Result1 = mysql_query($resetSQL, $DBconnection) or die(mysql_error());
    }
  // Insert all competition data  
  $insertSQL = sprintf("INSERT INTO competition (comp_name, comp_start_date, comp_end_date, comp_end_reg_date, comp_max_regs, comp_current) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($comp_name, "text"),
                       GetSQLValueString($_POST['comp_start_date'], "date"),
                       GetSQLValueString($_POST['comp_end_date'], "date"),
                       GetSQLValueString($_POST['comp_end_reg_date'], "date"),
                       GetSQLValueString($_POST['comp_max_regs'], "int"),
                       GetSQLValueString($_POST['comp_current'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());

  $insertGoTo = "CompetitionList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
?>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>
