<?php
//Moved meta description and keywords to header.php
//Added function to handle more input: comp_arranger, comp_email and comp_url

ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}
$pagetitle="L&auml;gga till t&auml;vling";
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
      <div class="error">
<?php
//Initiate global variables
global $comp_name, $comp_start_date, $comp_end_date, $comp_end_reg_date, $comp_current, $comp_arranger, $comp_email, $comp_url, $comp_max_regs;
    $comp_name = "";
    $comp_start_date = "";
    $comp_end_date = "";
    $comp_end_reg_date = "";
    $comp_arranger = "";
    $comp_email = "";
    $comp_url = "";
    $comp_max_regs = "";
    $comp_current = "";
//Validate the form if button is clicked
 if (filter_input(INPUT_POST,'MM_insert') == 'new_comp') {
    $comp_name = encodeToUtf8(filter_input(INPUT_POST,'comp_name'));
    $comp_start_date = filter_input(INPUT_POST,'comp_start_date');
    $comp_end_date = filter_input(INPUT_POST,'comp_end_date');
    $comp_end_reg_date = filter_input(INPUT_POST,'comp_end_reg_date');
    $comp_arranger = encodeToUtf8(filter_input(INPUT_POST,'comp_arranger'));
    $comp_email = filter_input(INPUT_POST,'comp_email');
    $comp_url = filter_input(INPUT_POST,'comp_url');
    $comp_max_regs = filter_input(INPUT_POST,'comp_max_regs');
    $comp_current = filter_input(INPUT_POST,'comp_current');
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
    if (empty($comp_arranger)) {
      // $comp_arranger is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens arrang&ouml;r!</h3>';
      $output_form = 'yes';
    }
    if (empty($comp_email)) {
      // $comp_email is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingsarrang&ouml;rens mejladresss!</h3>';
      $output_form = 'yes';
    }
    //If comp_email is not blank validate the input 
    else {
      // Validate contact_email
      if(!valid_email($comp_email)){
        // comp_email is invalid because LocalName is bad  
        echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3>';
        $output_form = 'yes';
      }
    }
    if (empty($comp_url)) {
      // $comp_url is blank
      echo '<h3>Du gl&ouml;mde att fylla i t&auml;vlingens webbadress!</h3>';
      $output_form = 'yes';
    }  
    //If comp_url is not blank validate the input 
    else {
      // Remove all illegal characters from a url
      $comp_url = filter_var($comp_url, FILTER_SANITIZE_URL);        
      // Validate comp_url
      if(!filter_var($comp_url, FILTER_VALIDATE_URL)){
        // comp_url is invalid   
        echo '<h3>Den ifyllda webbadressen &auml;r inte giltig.</h3>';
        $output_form = 'yes';
      } 
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
              <input type="text" name="comp_name" id="comp_name" value="<?php echo $comp_name ?>" size="32"/>
            </label></td>
          </tr>
          <tr>
            <td>Startdatum</td>
            <td><label>
              <input type="text" name="comp_start_date" id="comp_start_date" value="<?php echo $comp_start_date ?>" size="32"/>
            </label></td>
          </tr>
          <tr>
            <td>Slutdatum</td>
            <td><label>
              <input type="text" name="comp_end_date" id="comp_end_date" value="<?php echo $comp_end_date ?>" size="32"/>
            </label></td>
          </tr>
          <tr>
            <td>Sista anm&auml;lningsdag</td>
            <td><label>
              <input type="text" name="comp_end_reg_date" id="comp_end_reg_date" value="<?php echo $comp_end_reg_date ?>" size="32"/>
            </label></td>
          </tr>
          <tr>
            <td>T&auml;vlingens arrang&ouml;r:</td>
            <td><label>
              <input type="text" name="comp_arranger" id="comp_arranger" value="<?php echo $comp_arranger ?>" size="32"/>
          </label></td>
          </tr>
          <tr>
            <td>T&auml;vlingsarrang&ouml;rens mejladress:</td>
            <td><label>
              <input type="text" name="comp_email" id="comp_email" value="<?php echo $comp_email ?>" size="32"/>
            </label></td>
          </tr>
          <tr>
            <td>T&auml;vlingens webbadress:</td>
            <td><label>
              <input type="text" name="comp_url" id="comp_url" value="<?php echo $comp_url ?>" size="32"/>
            </label></td>
          </tr>          
          <tr>
            <td>Max antal anm&auml;lningar</td>
            <td><label>
            <input name="comp_max_regs" type="text" id="comp_max_regs" value="<?php echo $comp_max_regs ?>" size="32"/>              
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
//If button is clicked for insert then insert to columns from data in the form
    if (filter_input(INPUT_POST,'MM_insert') == 'new_comp') {
    // Set all competitions first to non-current (0) if the new competition shall be current
    $comp_current = filter_input(INPUT_POST,'comp_current');
            // Set all competitions first to non-current (0) if this competition will be the active  one ($comp_current == "on")
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
    // Insert all competition data  
    require('Connections/DBconnection.php');         
    $insertSQL = "INSERT INTO competition  (comp_name, comp_start_date, comp_end_date, comp_end_reg_date, comp_arranger, comp_email, "
            . "comp_url, comp_max_regs, comp_current) VALUES (:comp_name, :comp_start_date, :comp_end_date, :comp_arranger, :comp_email, "
            . ":comp_url, :comp_end_reg_date, :comp_max_regs, :comp_current)";
    $stmt = $DBconnection->prepare($insertSQL);
    $stmt->bindValue(':comp_name', $comp_name, PDO::PARAM_STR);
    $stmt->bindValue(':comp_start_date', $comp_start_date, PDO::PARAM_STR);
    $stmt->bindValue(':comp_end_date', $comp_end_date, PDO::PARAM_STR);
    $stmt->bindValue(':comp_end_reg_date', $comp_end_reg_date, PDO::PARAM_STR);
    $stmt->bindValue(':comp_arranger', $comp_arranger, PDO::PARAM_STR);
    $stmt->bindValue(':comp_email', $comp_email, PDO::PARAM_STR);
    $stmt->bindValue(':comp_url', $comp_url, PDO::PARAM_STR);
    $stmt->bindValue(':comp_max_regs', $comp_max_regs, PDO::PARAM_INT);
    $stmt->bindValue(':comp_current', $comp_current, PDO::PARAM_INT);
    $stmt->execute();

    $insertGoTo = "CompetitionList.php";
        if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
        }
        header(sprintf("Location: %s", $insertGoTo));
    }
    //Kill statements and DB connection
    $stmt->closeCursor();
    $DBconnection = null;
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
