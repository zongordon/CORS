<?php
//Changed validation from "text" for $comp_arranger

ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}
$pagetitle="L&auml;gga till t&auml;vling";
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
   <div class="feature">     
      <div class="error">
<?php
//Declare and initialise variables
$comp_name = '';$comp_start_time = '';$comp_start_date = '';$comp_end_reg_date = '';$comp_arranger = '';$comp_email = '';$comp_url = '';$comp_max_regs = '';$comp_current = '';$comp_limit_roundrobin = '';
//Validate the form if button is clicked
 if (filter_input(INPUT_POST,'MM_insert') == 'new_comp') {
    $comp_name = encodeToUtf8(filter_input(INPUT_POST,'comp_name'));
    $comp_start_time = filter_input(INPUT_POST,'comp_start_time');    
    $comp_start_date = filter_input(INPUT_POST,'comp_start_date');
    $comp_end_reg_date = filter_input(INPUT_POST,'comp_end_reg_date');
    $comp_arranger = encodeToUtf8(filter_input(INPUT_POST,'comp_arranger'));
    $comp_email = filter_input(INPUT_POST,'comp_email');
    $comp_url = filter_input(INPUT_POST,'comp_url');
    $comp_max_regs = filter_input(INPUT_POST,'comp_max_regs');
    $comp_current = filter_input(INPUT_POST,'comp_current');
    $comp_limit_roundrobin = filter_input(INPUT_POST,'comp_limit_roundrobin');

    $val = new Validation();
    $length = 5;//min length of strings
    $min = 3;//minimum value of integers
    $max = 5;//maximum value of integers
    $val->name('t&auml;vlingens namn')->value($comp_name)->pattern('text')->required()->min($length);
    $val->name('starttid')->value($comp_start_time)->timePattern()->required();
    $val->name('startdatum')->value($comp_start_date)->datePattern('Y-m-d')->required();    
    $val->name('sista anm&auml;lmingsdatum')->value($comp_end_reg_date)->datePattern('Y-m-d')->required();    
    $val->name('arrang&ouml;r')->value($comp_arranger)->pattern('alphanum')->required()->min($length);
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
            <td>Starttid <br/>(hh:mm eller h:mm)</td>
            <td><label>
                    <input type="text" name="comp_start_time" id="comp_start_time" value="<?php echo substr($comp_start_time,0,5) ?>" size="2"/>
            </label></td>
          </tr>          
          <tr>
            <td>Startdatum <br/>(yyyy-mm-dd)</td>
            <td><label>
              <input type="text" name="comp_start_date" id="comp_start_date" value="<?php echo $comp_start_date ?>" size="8" maxlength="10"/>
            </label></td>
          </tr>
          <tr>
            <td>Sista anm&auml;lningsdag <br/>(yyyy-mm-dd)</td>
            <td><label>
              <input type="text" name="comp_end_reg_date" id="comp_end_reg_date" value="<?php echo $comp_end_reg_date ?>" size="8" maxlength="10"/>
            </label></td>
          </tr>
          <tr>
            <td>T&auml;vlingens arrang&ouml;r</td>
            <td><label>
              <input type="text" name="comp_arranger" id="comp_arranger" value="<?php echo $comp_arranger ?>" size="32"/>
          </label></td>
          </tr>
          <tr>
            <td>T&auml;vlingsarrang&ouml;rens mejladress</td>
            <td><label>
              <input type="text" name="comp_email" id="comp_email" value="<?php echo $comp_email ?>" size="32"/>
            </label></td>
          </tr>
          <tr>
            <td>T&auml;vlingens webbadress<br/>(http://sajt.com)</td>
            <td><label>
              <input type="text" name="comp_url" id="comp_url" value="<?php echo $comp_url ?>" size="32"/>
            </label></td>
          </tr>          
          <tr>
            <td>Max antal anm&auml;lningar</td>
            <td><label>
              <input name="comp_max_regs" type="number" id="comp_max_regs" value="<?php echo $comp_max_regs ?>" size="32"/>              
            </label></td>
          </tr>
        <tr>
            <td>Gr&auml;ns f&ouml;r round robin<br/>(alla m&ouml;ter alla; 3-5)</td>
            <td><label>
              <input name="comp_limit_roundrobin" type="number" id="comp_limit_roundrobin" value="<?php echo $comp_limit_roundrobin ?>"/>              
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
    $insertSQL = "INSERT INTO competition  (comp_name, comp_start_time, comp_start_date, comp_end_reg_date, comp_arranger, comp_email, "
            . "comp_url, comp_max_regs, comp_limit_roundrobin, comp_current) VALUES (:comp_name, :comp_start_time, :comp_start_date, "
            . ":comp_end_reg_date, :comp_arranger, :comp_email, :comp_url, :comp_max_regs, :comp_limit_roundrobin, :comp_current)";
    $stmt = $DBconnection->prepare($insertSQL);
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

    $insertGoTo = "CompetitionList.php";
        if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
        }
        header(sprintf("Location: %s", $insertGoTo));
    }
    //Kill statement
    $stmt->closeCursor();
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
