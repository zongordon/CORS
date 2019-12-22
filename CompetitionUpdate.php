<?php
//Added validation class with multiple validation features and removed most of existing validation code
//Removed client side date format for date input fields

ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Fetch the selected competition from previous page
$colname_rsCompetition = filter_input(INPUT_GET,'comp_id');

$pagetitle="&Auml;ndra t&auml;vling";
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
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class ="feature">    
        <div class="error">
<?php
//Declare and initialise variables
$comp_name = '';$comp_start_time = '';$comp_start_date = '';$comp_end_reg_date = '';$comp_arranger = '';$comp_email = '';$comp_url = '';$comp_max_regs = '';$comp_current = '';$comp_limit_roundrobin = '';
 //Validate the form if button is clicked
 if (filter_input(INPUT_POST,'MM_update') === 'update_competition') {
    $comp_id = filter_input(INPUT_POST,'comp_id');     
    $comp_name = encodeToUtf8(filter_input(INPUT_POST,'comp_name'));
    $comp_start_time = filter_input(INPUT_POST,'comp_start_time');        
    $comp_start_date = filter_input(INPUT_POST,'comp_start_date');
    $comp_end_reg_date = filter_input(INPUT_POST,'comp_end_reg_date');
    $comp_arranger = encodeToUtf8(filter_input(INPUT_POST,'comp_arranger'));
    $comp_email = filter_input(INPUT_POST,'comp_email');
    $comp_url = filter_input(INPUT_POST,'comp_url');
    $comp_max_regs = filter_input(INPUT_POST,'comp_max_regs');
    $comp_limit_roundrobin = filter_input(INPUT_POST,'comp_limit_roundrobin');
    
    $val = new Validation();
    $length = 5;//min length of strings
    $min = 3;//minimum value of integers
    $max = 5;//maximum value of integers
    $val->name('t&auml;vlingens namn')->value($comp_name)->pattern('text')->required()->min($length);
    $val->name('starttid')->value($comp_start_time)->timePattern()->required();
    $val->name('startdatum')->value($comp_start_date)->datePattern('Y-m-d')->required();    
    $val->name('sista anm&auml;lmingsdatum')->value($comp_end_reg_date)->datePattern('Y-m-d')->required();    
    $val->name('arrang&ouml;r')->value($comp_arranger)->pattern('text')->required()->min($length);
    $val->name('e-post')->value($comp_email)->emailPattern()->required();
    $val->name('t&auml;vlingssajten')->value($comp_url)->urlPattern()->required();
    $val->name('max antal anm&auml;lningar')->value($comp_max_regs)->pattern('int')->required();
    $val->name('gr&auml;ns f&ouml;r round robin')->value($comp_limit_roundrobin)->valuePattern($min,$max)->required();
    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }
 }
else {
    $output_form = 'yes';
}

if ($output_form == 'yes') {
    //Catch anything wrong with query
    try {    
    //Select all columns from the selected competition
    require('Connections/DBconnection.php');           
    $query = "SELECT * FROM competition WHERE comp_id = :comp_id";
    $stmt_rsCompetition = $DBconnection->prepare($query);
    $stmt_rsCompetition->execute(array(':comp_id' => $colname_rsCompetition));
    $row_rsCompetition = $stmt_rsCompetition->fetch(PDO::FETCH_ASSOC);
    } 
    catch(PDOException $ex) {
        echo "An Error occured with query1: ".$ex->getMessage();
    }
?>          
        </div>
<h3>&Auml;ndra &ouml;nskade v&auml;rden och klicka p&aring; &quot;Spara&quot; f&ouml;r att spara och g&aring; tillbaka till listan &ouml;ver t&auml;vlingar.</h3>
  </div>
  <div class="story">
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="update_competition" id="update_competition">
        <table width="400" border="0">
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">T&auml;vlingens namn:</td>
          <td>&nbsp;</td>
          <td><input name="comp_name" type="text" value="<?php echo $row_rsCompetition['comp_name']; ?>" size="32" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Starttid<br/>(hh:mm eller h:mm)</td>
          <td>&nbsp;</td>
          <td><input name="comp_start_time" type="text" value="<?php echo substr($row_rsCompetition['comp_start_time'],0,5); ?>" size="2" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Startdatum<br/>(yyyy-mm-dd)</td>
          <td>&nbsp;</td>
          <td><input name="comp_start_date" type="text" value="<?php echo $row_rsCompetition['comp_start_date']; ?>" size="8" maxlength="10"/></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Sista anm&auml;lningsdag<br/>(yyyy-mm-dd)</td>
          <td>&nbsp;</td>
          <td><input name="comp_end_reg_date" type="text" value="<?php echo $row_rsCompetition['comp_end_reg_date']; ?>" size="8" maxlength="10"/></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">T&auml;vlingens arrang&ouml;r</td>
          <td>&nbsp;</td>
          <td><input name="comp_arranger" type="text" value="<?php echo $row_rsCompetition['comp_arranger']; ?>" size="32" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">T&auml;vlingsarrang&ouml;rens mejladress</td>
          <td>&nbsp;</td>
          <td><input name="comp_email" type="text" value="<?php echo $row_rsCompetition['comp_email']; ?>" size="32" /></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">T&auml;vlingens webbadress<br/>(http://sajt.com)</td>
          <td>&nbsp;</td>
          <td><input name="comp_url" type="text" value="<?php echo $row_rsCompetition['comp_url']; ?>" size="32" /></td>
        </tr>
        <tr>
            <td align="right" valign="baseline" nowrap="nowrap">Max antal anm&auml;lningar</td>
            <td>&nbsp;</td> 
            <td><label>
               <input name="comp_max_regs" type="number" id="comp_max_regs" value="<?php echo $row_rsCompetition['comp_max_regs']; ?>"/>              
            </label></td>
        </tr>        
        <tr>
            <td align="right" valign="baseline" nowrap="nowrap">Gr&auml;ns f&ouml;r round robin<br/>(alla m&ouml;ter alla; 3-5)</td>
            <td>&nbsp;</td> 
            <td><label>
               <input name="comp_limit_roundrobin" type="number" id="comp_limit_roundrobin" value="<?php echo $row_rsCompetition['comp_limit_roundrobin']; ?>"/>              
            </label></td>
        </tr>        
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">Aktiv:</td>
          <td>&nbsp;</td>
          <td><label>
          <input type="checkbox" name="comp_current" id="comp_current" 
          <?php if (!(strcmp($row_rsCompetition['comp_current'],1))) {
                   //Disable checkbox if competition is current (active)
                   echo "checked=\"checked\" disabled='disabled'/ />(&auml;ndrar du i listan &ouml;ver t&auml;vlingar)";
                   echo "<input name='comp_current' type='hidden' value=1 />";
                }   
                else {
                   echo "/>"; 
                } ?>  
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input name="CompUpdate" type="submit" id="CompUpdate" value="Spara"/></td>
        </tr>
      </table>
      <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsCompetition['comp_id']; ?>"/>
      <input type="hidden" name="MM_update" value="update_competition"/>
    </form>
    <p>&nbsp;</p>
<?php    
} 
//Save the competition information
else if ($output_form == 'no') {

        //If button is clicked for updating then update to columns from data in the form
        if (filter_input(INPUT_POST,'MM_update') == 'update_competition') {
            $comp_current = filter_input(INPUT_POST,'comp_current');
            // Set all competitions first to non-current (0) if competition is changed to active ($comp_current == "on")
            if ($comp_current === "on") {
                //Catch anything wrong with query
                try {
                // Set all competitions first to non-current (0)   
                require('Connections/DBconnection.php');
                $comp_reset = 0;
                $resetSQL = "UPDATE competition SET comp_current = :comp_current"; 
                $stmt_rsReset = $DBconnection->prepare($resetSQL);                                 
                $stmt_rsReset->bindValue(':comp_current', $comp_reset, PDO::PARAM_INT);
                $stmt_rsReset->execute();
                }   
                catch(PDOException $ex) {
                    echo "An Error occured with query (resetSQL): ".$ex->getMessage();
                }
                $comp_current = 1;
            }        
        //Catch anything wrong with query
        try {    
        require('Connections/DBconnection.php');
        // Update all competition data          
            $updateSQL = "UPDATE competition SET comp_name = :comp_name,  
            comp_start_time = :comp_start_time, 
            comp_start_date = :comp_start_date, 
            comp_end_reg_date = :comp_end_reg_date, 
            comp_arranger = :comp_arranger, 
            comp_email = :comp_email, 
            comp_url = :comp_url, 
            comp_max_regs = :comp_max_regs, 
            comp_limit_roundrobin = :comp_limit_roundrobin, 
            comp_current = :comp_current
            WHERE comp_id = :comp_id"; 
            $stmt = $DBconnection->prepare($updateSQL);                                 
            $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
            $stmt->bindValue(':comp_name', $comp_name, PDO::PARAM_STR);
            $stmt->bindValue(':comp_start_time', $comp_start_time, PDO::PARAM_STR);
            $stmt->bindValue(':comp_start_date', $comp_start_date, PDO::PARAM_STR);
            $stmt->bindValue(':comp_end_reg_date', $comp_end_reg_date, PDO::PARAM_STR);
            $stmt->bindValue(':comp_arranger', $comp_arranger, PDO::PARAM_STR);
            $stmt->bindValue(':comp_email', $comp_email, PDO::PARAM_STR);
            $stmt->bindValue(':comp_url', $comp_url, PDO::PARAM_STR);
            $stmt->bindValue(':comp_max_regs', $comp_max_regs, PDO::PARAM_INT);            
            $stmt->bindValue(':comp_limit_roundrobin', $comp_limit_roundrobin, PDO::PARAM_INT);            
            $stmt->bindValue(':comp_current', $comp_current, PDO::PARAM_INT);                             
            $stmt->execute();        
        }
        catch(PDOException $ex) {
            echo "An Error occured: ".$ex->getMessage();
        }  
            //After update redirect to page listing the competitions
            $updateGoTo = "CompetitionList.php?" . $row_rsCompetition['comp_id'] . "=" . $row_rsCompetition['comp_id'] . "";
            if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
            $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
            $updateGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
            } 
        header(sprintf("Location: %s", $updateGoTo));
        //Kill statement 
        $stmt->closeCursor();
        }
}
?>
  </div>
</div>
<?php 
//Kill statement 
$stmt_rsCompetition->closeCursor();
include("includes/footer.php");?>
</body>
</html>
<?php
ob_end_flush();
?>