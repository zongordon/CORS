<?php
ob_start();
//Access level top administrator
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Uppdatera deltagare - admin";
// Includes Several code functions
include_once('includes/functions.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");
//Update contestant selected on previous page if contestant_id is provided
if (filter_input(INPUT_GET,'contestant_id') != "") {
    $contestant_id = filter_input(INPUT_GET,'contestant_id');
        //Catch anything wrong with query
    try {
    // Select data for the selected contestants
    require('Connections/DBconnection.php');               
    $query_rsContestants = "SELECT account_id, contestant_name, contestant_birth, contestant_gender FROM contestants WHERE contestant_id = :contestant_id";
    $stmt_rsContestants = $DBconnection->prepare($query_rsContestants);
    $stmt_rsContestants->execute(array(':contestant_id'=>$contestant_id));
    $row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    $contestant_name = $row_rsContestants['contestant_name'];    
    $contestant_birth = $row_rsContestants['contestant_birth'];
    $contestant_gender = $row_rsContestants['contestant_gender'];
    $account_id = $row_rsContestants['account_id'];

}?>    
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
        <div class="feature">
            <h3>Gör &auml;ndringar på klubbens t&auml;vlande och klicka p&aring; Spara.</h3>
<div class="error">
<?php
//Validate the form input when button is clicked
    $insert_contestant_name = "";
    $insert_contestant_birth = "";
    $insert_contestant_gender = "";
// Validate the contestant form if the button is clicked	
if (filter_input(INPUT_POST,"MM_update_contestant") === "update_contestant") {
    $insert_contestant_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contestant_name'), MB_CASE_TITLE,"UTF-8"));    
    $insert_contestant_birth = filter_input(INPUT_POST,'contestant_birth');
    $insert_contestant_gender = filter_input(INPUT_POST,'contestant_gender');
    $output_form = 'no';
	
    if (empty($insert_contestant_name)) {
      // $insert_contestant_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i namn!</h1>';
      $output_form = 'yes';
    }
    if (empty($insert_contestant_birth)) {
      // $insert_contestant_birth is blank
      echo '<h3>Du gl&ouml;mde att fylla i f&ouml;delsedatum!</h1>';
      $output_form = 'yes';
	}
    if (!empty($insert_contestant_birth) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $insert_contestant_birth)) {
    // $insert_contestant_birth is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; f&ouml;delsedatum (YYYY-MM-DD)!</h1>';
    $output_form = 'yes';
    }	
    if (empty($insert_contestant_gender)) {	
      // $insert_contestant_gender is blank
      echo '<h3>Du gl&ouml;mde att v&auml;lja k&ouml;n.</h1>';
      $output_form = 'yes';
    }
}
else {  
    $output_form = 'yes';
}
if ($output_form === 'yes') { ?>

</div>
<form id="update_contestant" name="update_contestant" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="450" border="0">
        <tr>
          <td>T&auml;vlandes namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="30" value="<?php echo $contestant_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>F&ouml;delsedatum (t.ex. 1996-01-31)</td>
          <td valign="top"><label>
            <input name="contestant_birth" type="date" id="contestant_birth" value="<?php echo $contestant_birth; ?>" size="8" maxlength="10"/>
          </label></td>
        </tr>
        <tr>
          <td>K&ouml;n</td>
          <td valign="top">
            <label>
              <input name="contestant_gender" type="radio" id="contestant_gender" value="Man" <?php if ($contestant_gender == "Man") echo "checked='checked'"; ?>//>
              Man</label>
            <label>
              <input type="radio" name="contestant_gender" id="contestant_gender" value="Kvinna" <?php if ($contestant_gender == "Kvinna") echo "checked='checked'"; ?>/>
              Kvinna</label>
          </td>
        </tr>
        <tr>
          <td>
        <input type="hidden" name="MM_update_contestant" value="update_contestant" />
        <input type="hidden" name="account_id" id="account_id" value="<?php echo $account_id; ?>" />
        <input type="hidden" name="account_id" id="contestant_id" value="<?php echo $contestant_id; ?>" />
          </td>
          <td><label>
              <input type="submit" name="update_contestant" id="update_contestant" value="Spara" />
          </label></td>
        </tr>
      </table>
    </form>   
        </div>
</div>
<?php 
}
else if ($output_form === 'no') {
    if (filter_input(INPUT_POST,"MM_update_contestant") === "update_contestant") {    
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');    
    //UPDATE selected Contestant
    $updateSQL = "UPDATE contestants SET 
    account_id = :account_id, 
    contestant_name = :contestant_name, 
    contestant_birth = :contestant_birth, 
    contestant_gender = :contestant_gender 
    WHERE contestant_id = :contestant_id"; 
    $stmt = $DBconnection->prepare($updateSQL);                        
    $stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_name', $insert_contestant_name, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_birth', $insert_contestant_birth, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_gender', $insert_contestant_gender, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_id', $contestant_id, PDO::PARAM_INT);
    $stmt->execute();
    }   catch(PDOException $ex) {
            echo "An Error occured with queryX: ".$ex->getMessage();
        }
    $updateGoTo = "RegInsert_reg.php#registration_insert";
    header(sprintf("Location: %s", $updateGoTo));
  }
//Kill statement
$stmt->closeCursor();  
} 
include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>