<?php
//Changed from "text" validation of $update_contestant_name
//Changed code to solve Warning: Undefined variable $contestant_name when validation triggered error message with PHP 8.0.0.rc1

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
        <div id="feature"> 
<?php
//Declare and initialise variables
$min_birthday='';$max_birthday='';$gender_init = '';$gender_final ='';$update_contestant_name='';$update_contestant_birth='';$update_contestant_birth_max='';$update_contestant_gender='';$contestant_team='';$update_contestant_team_member_1='';$update_contestant_team_member_2='';$update_contestant_team_member_3='';$update_contestant_team_member_4='';$update_contestant_team_member_5='';
//Update contestant selected on previous page if contestant_id is provided
if (filter_input(INPUT_GET,'contestant_id') != "") {
    $update_contestant_id = filter_input(INPUT_GET,'contestant_id');
    //Catch anything wrong with query
    try {
    // Select data for the selected contestants
    require('Connections/DBconnection.php');               
    $query_rsContestants = "SELECT account_id, contestant_id, contestant_name, contestant_birth, contestant_birth_max, "
            . "contestant_gender, contestant_team, contestant_team_member_1, contestant_team_member_2, contestant_team_member_3, "
            . "contestant_team_member_4, contestant_team_member_5 "
            . "FROM contestants "
            . "WHERE contestant_id = :contestant_id";
    $stmt_rsContestants = $DBconnection->prepare($query_rsContestants);
    $stmt_rsContestants->execute(array(':contestant_id'=>$update_contestant_id));
    $row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo 'An Error occured with query $query_rsContestants: '.$ex->getMessage();
    }
    $update_contestant_name = $row_rsContestants['contestant_name'];    
    $update_contestant_birth = $row_rsContestants['contestant_birth'];
    $update_contestant_birth_max = $row_rsContestants['contestant_birth_max'];
    $update_contestant_gender = $row_rsContestants['contestant_gender'];
    $update_contestant_team = $row_rsContestants['contestant_team'];
    $update_contestant_team_member_1 = $row_rsContestants['contestant_team_member_1'];
    $update_contestant_team_member_2 = $row_rsContestants['contestant_team_member_2'];
    $update_contestant_team_member_3 = $row_rsContestants['contestant_team_member_3'];
    $update_contestant_team_member_4 = $row_rsContestants['contestant_team_member_4'];
    $update_contestant_team_member_5 = $row_rsContestants['contestant_team_member_5'];
    $_SESSION['MM_Account'] = $row_rsContestants['account_id'];
    $_SESSION['contestant_team'] = $row_rsContestants['contestant_team'];
}

//Show one of the forms as a start
$output_form = 'yes';

//Update individual contestant
if ($_SESSION['contestant_team'] === 0){
// Validate the contestant form if the button is clicked	
if (filter_input(INPUT_POST,"MM_update_contestant") === "update_contestant") {
  $update_contestant_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contestant_name'), MB_CASE_TITLE,"UTF-8"));    
  $update_contestant_birth = filter_input(INPUT_POST,'contestant_birth');
  $update_contestant_gender = filter_input(INPUT_POST,'contestant_gender');
  $update_contestant_birth_max = $update_contestant_birth;
  if(filter_input(INPUT_POST,'contestant_team_member_1') === ''){ $update_contestant_team_member_1 = 0;}else{$update_contestant_team_member_1 = filter_input(INPUT_POST,'contestant_team_member_1');}
  if(filter_input(INPUT_POST,'contestant_team_member_2') === ''){ $update_contestant_team_member_2 = 0;}else{$update_contestant_team_member_2 = filter_input(INPUT_POST,'contestant_team_member_2');}
  if(filter_input(INPUT_POST,'contestant_team_member_3') === ''){ $update_contestant_team_member_3 = 0;}else{$update_contestant_team_member_3 = filter_input(INPUT_POST,'contestant_team_member_3');}
  if(filter_input(INPUT_POST,'contestant_team_member_4') === ''){ $update_contestant_team_member_4 = 0;}else{$update_contestant_team_member_4 = filter_input(INPUT_POST,'contestant_team_member_4');}
  if(filter_input(INPUT_POST,'contestant_team_member_5') === ''){ $update_contestant_team_member_5 = 0;}else{$update_contestant_team_member_5 = filter_input(INPUT_POST,'contestant_team_member_5');}   
  $update_contestant_id = filter_input(INPUT_POST,'contestant_id');
  
    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('namn')->value($update_contestant_name)->pattern('words')->required()->min($length);
    $val->name('f&ouml;delsedatum')->value($update_contestant_birth)->datePattern('Y-m-d')->required();
    $val->name('k&ouml;n')->value($update_contestant_gender)->pattern('words')->required();
	        
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
        $output_form = 'yes';
    }
    //Search for teams related to the selected contestant to update the teams' gender and max - and min age
    try { //Catch anything wrong with query
    require('Connections/DBconnection.php');               
    $query_rsTeams = "SELECT contestant_id, contestant_name, contestant_birth, contestant_birth_max, contestant_gender, "
            . "contestant_team, contestant_team_member_1, contestant_team_member_2, contestant_team_member_3, "
            . "contestant_team_member_4, contestant_team_member_5 "
            . "FROM contestants "
            . "WHERE contestant_team_member_1 = :contestant_id_1 OR contestant_team_member_2 = :contestant_id_2 OR "
            . "contestant_team_member_3 = :contestant_id_3 OR contestant_team_member_4 = :contestant_id_4 OR "
            . "contestant_team_member_5 = :contestant_id_5";
    $stmt_rsTeams = $DBconnection->prepare($query_rsTeams);
    $stmt_rsTeams->execute(array(':contestant_id_1'=>$update_contestant_id,':contestant_id_2'=>$update_contestant_id,':contestant_id_3'=>$update_contestant_id,':contestant_id_4'=>$update_contestant_id, ':contestant_id_5'=>$update_contestant_id));
    }   catch(PDOException $ex) {
            echo 'An Error occured with query $query_rsTeams: '.$ex->getMessage();
    }
        while($row_rsTeams = $stmt_rsTeams->fetch(PDO::FETCH_ASSOC)) {
            $update_team_id = $row_rsTeams['contestant_id'];
            $update_team_name = $row_rsTeams['contestant_name'];
            $update_team_birth = $row_rsTeams['contestant_birth'];
            $update_team_birth_max = $row_rsTeams['contestant_birth_max'];
            $update_team_gender = $row_rsTeams['contestant_gender'];
            $update_team_team = $row_rsTeams['contestant_team'];
            $update_team_team_member_1 = $row_rsTeams['contestant_team_member_1'];
            $update_team_team_member_2 = $row_rsTeams['contestant_team_member_2'];
            $update_team_team_member_3 = $row_rsTeams['contestant_team_member_3'];
            $update_team_team_member_4 = $row_rsTeams['contestant_team_member_4'];
            $update_team_team_member_5 = $row_rsTeams['contestant_team_member_5'];

                //Compare selected team member's gender and set the team's gender            
                if($update_contestant_gender <> $update_team_gender) { //The members's gender is NOT the same as the team's
                    $team_gender = 'Mix';    
                }       
               //Compare selected team member's birthday and set the teams' max and min birthdays            
                if($update_contestant_birth < $update_team_birth) {//The members's birth is earlier than the team's
                    $update_team_birth = $update_contestant_birth;
                } 
                elseif ($update_contestant_birth_max > $update_team_birth_max) {//The members's birth is later than the team's
                    $update_team_birth_max = $update_contestant_birth_max; 
                }

                //UPDATE selected Team with gender and  max-/min birth                   
                try { //Catch anything wrong with query
                require('Connections/DBconnection.php');    
                $updateTeamSQL = "UPDATE contestants SET 
                account_id = :account_id, 
                contestant_name = :contestant_name, 
                contestant_birth = :contestant_birth, 
                contestant_birth_max = :contestant_birth_max, 
                contestant_gender = :contestant_gender, 
                contestant_team = :contestant_team, 
                contestant_team_member_1 = :contestant_team_member_1, 
                contestant_team_member_2 = :contestant_team_member_2, 
                contestant_team_member_3 = :contestant_team_member_3, 
                contestant_team_member_4 = :contestant_team_member_4, 
                contestant_team_member_5 = :contestant_team_member_5 
                WHERE contestant_id = :contestant_id"; 
                $stmt = $DBconnection->prepare($updateTeamSQL);                        
                $stmt->bindValue(':account_id', $_SESSION['MM_Account'], PDO::PARAM_INT);
                $stmt->bindValue(':contestant_name', $update_team_name, PDO::PARAM_STR);
                $stmt->bindValue(':contestant_birth', $update_team_birth, PDO::PARAM_STR);
                $stmt->bindValue(':contestant_birth_max', $update_team_birth_max, PDO::PARAM_STR);
                $stmt->bindValue(':contestant_gender', $update_team_gender, PDO::PARAM_STR);
                $stmt->bindValue(':contestant_team', $update_team_team, PDO::PARAM_INT);
                $stmt->bindValue(':contestant_team_member_1', $update_team_team_member_1, PDO::PARAM_INT);
                $stmt->bindValue(':contestant_team_member_2', $update_team_team_member_2, PDO::PARAM_INT);
                $stmt->bindValue(':contestant_team_member_3', $update_team_team_member_3, PDO::PARAM_INT);
                $stmt->bindValue(':contestant_team_member_4', $update_team_team_member_4, PDO::PARAM_INT);
                $stmt->bindValue(':contestant_team_member_5', $update_team_team_member_5, PDO::PARAM_INT);
                $stmt->bindValue(':contestant_id', $update_team_id, PDO::PARAM_INT);
                $stmt->execute();
                }   catch(PDOException $ex) {
                        echo 'An Error occured with query $updateTeamSQL: '.$ex->getMessage();
                    }
        $stmt_rsTeams->closeCursor();//Kill statements
        }
}
else {  
    $output_form = 'yes';
}
//Show individual contestant form
if ($output_form === 'yes') { ?>
<h3>Gör &auml;ndringar på klubbens t&auml;vlande och klicka p&aring; Spara.</h3>
<form id="update_contestant" name="update_contestant" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="450" border="0">
        <tr>
          <td>T&auml;vlandes namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="30" value="<?php echo $update_contestant_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>F&ouml;delsedatum (yyyy-mm-dd)</td>
          <td valign="top"><label>
            <input name="contestant_birth" type="text" id="contestant_birth" value="<?php echo $update_contestant_birth; ?>" size="8" maxlength="10"/>
          </label></td>
        </tr>
        <tr>
          <td>K&ouml;n</td>
          <td valign="top">
            <label>
              <input name="contestant_gender" type="radio" id="contestant_gender" value="Man" <?php if ($update_contestant_gender === "Man") {echo "checked='checked'";} ?>//>
              Man</label>
            <label>
              <input type="radio" name="contestant_gender" id="contestant_gender" value="Kvinna" <?php if ($update_contestant_gender === "Kvinna") {echo "checked='checked'";} ?>/>
              Kvinna</label>
          </td>
        </tr>
        <tr>
          <td>
        <input type="hidden" name="MM_update_contestant" value="update_contestant" />
        <input type="hidden" name="contestant_team" id="contestant_team" value="<?php echo $update_contestant_team; ?>" />
        <input type="hidden" name="contestant_team_member_1" id="contestant_team_member_1" value="<?php echo $update_contestant_team_member_1; ?>" />
        <input type="hidden" name="contestant_team_member_2" id="contestant_team_member_2" value="<?php echo $update_contestant_team_member_2; ?>" />
        <input type="hidden" name="contestant_team_member_3" id="contestant_team_member_3" value="<?php echo $update_contestant_team_member_3; ?>" />
        <input type="hidden" name="contestant_team_member_4" id="contestant_team_member_4" value="<?php echo $update_contestant_team_member_4; ?>" />
        <input type="hidden" name="contestant_team_member_5" id="contestant_team_member_5" value="<?php echo $update_contestant_team_member_5; ?>" />
        <input type="hidden" name="contestant_id" id="contestant_id" value="<?php echo $update_contestant_id; ?>" />
          </td>
          <td><label>
              <input type="submit" name="update_contestant" id="update_contestant" value="Spara" />
          </label></td>
        </tr>
      </table>
    </form>   
<?php 
}//Show individual contestant form
}//Update individual contestant

//Update team contestant
if ($_SESSION['contestant_team'] === 1){ ?>
<?php 
//Catch anything wrong with query
try {
// Select all registered contestants but no teams for the club   
require('Connections/DBconnection.php');                   
$query_rsTeamMembers = "SELECT co.contestant_id, co.account_id, co.contestant_name, co.contestant_birth, "
        . "co.contestant_gender, co.contestant_team, co.contestant_team_member_1, co.contestant_team_member_2, "
        . "co.contestant_team_member_3, co.contestant_team_member_4, co.contestant_team_member_5 "
        . "FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND co.account_id = :account_id "
        . "WHERE co.contestant_team <> 1 ORDER BY co.contestant_name";
$stmt_rsTeamMembers = $DBconnection->prepare($query_rsTeamMembers);
$stmt_rsTeamMembers ->execute(array(':account_id'=>$_SESSION['MM_Account']));
$row_rsTeamMembers = $stmt_rsTeamMembers->fetchAll(PDO::FETCH_ASSOC);   
$totalRows_rsTeamMembers = $stmt_rsTeamMembers->rowCount();    
}   catch(PDOException $ex) {
        echo 'An Error occured with query $query_rsTeamMembers: '.$ex->getMessage();
    }
// Validate the contestant form if the button is clicked	
if (filter_input(INPUT_POST,"MM_update_team") === "update_team") {
    $update_contestant_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contestant_name'), MB_CASE_TITLE,"UTF-8"));    
    $update_contestant_team = filter_input(INPUT_POST,'contestant_team');
    $update_contestant_team_member_1 = filter_input(INPUT_POST,'contestant_team_member_1');
    $update_contestant_team_member_2 = filter_input(INPUT_POST,'contestant_team_member_2');
    if(filter_input(INPUT_POST,'contestant_team_member_3') === ''){ $update_contestant_team_member_3 = 0;}else{$update_contestant_team_member_3 = filter_input(INPUT_POST,'contestant_team_member_3');}
    if(filter_input(INPUT_POST,'contestant_team_member_4') === ''){ $update_contestant_team_member_4 = 0;}else{$update_contestant_team_member_4 = filter_input(INPUT_POST,'contestant_team_member_4');}
    if(filter_input(INPUT_POST,'contestant_team_member_5') === ''){ $update_contestant_team_member_5 = 0;}else{$update_contestant_team_member_5 = filter_input(INPUT_POST,'contestant_team_member_5');}
    $update_contestant_id = filter_input(INPUT_POST,'contestant_id');    
    
    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('lagets namn')->value($update_contestant_name)->pattern('alphanum')->required()->min($length);
    $val->name('tillr&auml;ckligt m&aring;nga lagmedlemmar')->value($update_contestant_team_member_1)->pattern('int')->required();
    $val->name('tillr&auml;ckligt m&aring;nga lagmedlemmar')->value($update_contestant_team_member_2)->pattern('int')->required();
	        
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
        $output_form = 'yes';
    }
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
        //Compare selected team members and club contestants
        if($row_rsTeamMember['contestant_id']==$update_contestant_team_member_1||$row_rsTeamMember['contestant_id']==$update_contestant_team_member_2||$row_rsTeamMember['contestant_id']==$update_contestant_team_member_3||$row_rsTeamMember['contestant_id']==$update_contestant_team_member_4||$row_rsTeamMember['contestant_id']==$update_contestant_team_member_5){
            //Compare selected team members' gender and set the team's gender            
            if($gender_init === ''){//The first contestant selected as team member
                $gender_final = $row_rsTeamMember['contestant_gender'];
                $gender_init = $gender_final;
            }elseif ($gender_init == $row_rsTeamMember['contestant_gender']) {//The members's gender is the same as previous
                $gender_final = $row_rsTeamMember['contestant_gender'];
                $gender_init = $gender_final;
                } else {//The members's gender is NOT the same as previous
                $gender_final = 'Mix';    
                $gender_init = $gender_final;
                }       
           //Compare selected team members' birthdays and set the team's max and min birthdays            
            if($min_birthday === ''||$max_birthday === ''){//The first contestant selected as team member
                $min_birthday = $row_rsTeamMember['contestant_birth'];
                $max_birthday = $min_birthday;
            }
            elseif ($min_birthday == $row_rsTeamMember['contestant_birth']) {//The members's birth is the same as previous
            } 
            elseif ($min_birthday > $row_rsTeamMember['contestant_birth']) {//The members's birth is the earlier than previous
                $min_birthday = $row_rsTeamMember['contestant_birth']; 
            }
            elseif ($max_birthday == $row_rsTeamMember['contestant_birth']) {//The members's birth is the same as previous
            } 
            elseif ($max_birthday < $row_rsTeamMember['contestant_birth']) {//The members's birth is the later than previous
                $max_birthday = $row_rsTeamMember['contestant_birth']; 
            }                
        } else {//The conestant is not selected as team member    
          }          
     } 
    $update_contestant_birth = $min_birthday;
    $update_contestant_birth_max = $max_birthday;
    $update_contestant_gender = $gender_final;
}//Update team

//Show team form
if ($output_form === 'yes') { ?>
<h3>&Auml;ndra lagets namn och vilka som ing&aring;r.</h3>    
<form id="update_team" name="update_team" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="550" border="0">
        <tr>
          <td>Lagets namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="30" value="<?php echo $update_contestant_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>Medlem 1</td>  
          <td nowrap="nowrap">
          <label><select name="contestant_team_member_1" id="contestant_team_member_1">             
<?php
        if ($update_contestant_team_member_1 === 0 || $update_contestant_team_member_1 === ''){
            echo '<option value="">V&auml;lj lagmedlem 1!';
        }
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option value="<?php echo $row_rsTeamMember['contestant_id']?>"
<?php if (!(strcmp($update_contestant_team_member_1, $row_rsTeamMember['contestant_id']))) { echo "selected=\"selected\"";} ?>>
<?php echo $row_rsTeamMember['contestant_name'].' | '.$row_rsTeamMember['contestant_birth'].' | '.$row_rsTeamMember['contestant_gender'];?>
      </option>
<?php
    } ?>
          </select></label></td> 
        </tr>
        <tr>
          <td>Medlem 2</td>  
          <td nowrap="nowrap">
          <label><select name="contestant_team_member_2" id="contestant_team_member_2">
<?php
        if ($update_contestant_team_member_2 === 0 || $update_contestant_team_member_2 === ''){
            echo '<option value="">V&auml;lj lagmedlem 2!';
        }
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option value="<?php echo $row_rsTeamMember['contestant_id']?>"
<?php if (!(strcmp($update_contestant_team_member_2, $row_rsTeamMember['contestant_id']))) { echo "selected=\"selected\"";} ?>>
<?php echo $row_rsTeamMember['contestant_name'].' | '.$row_rsTeamMember['contestant_birth'].' | '.$row_rsTeamMember['contestant_gender'];?>
      </option>
<?php
    } ?>
          </select></label></td> 
        </tr>
        <tr>
          <td>Medlem 3</td>  
          <td nowrap="nowrap">
          <label><select name="contestant_team_member_3" id="contestant_team_member_3">
<?php
        if ($update_contestant_team_member_3 === 0 || $update_contestant_team_member_3 === ''){
            echo '<option value="">V&auml;lj lagmedlem 3!';
        }
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option value="<?php echo $row_rsTeamMember['contestant_id'] ?>"
<?php if (!(strcmp($update_contestant_team_member_3, $row_rsTeamMember['contestant_id']))) { echo "selected=\"selected\"";} ?>>
<?php echo $row_rsTeamMember['contestant_name'].' | '.$row_rsTeamMember['contestant_birth'].' | '.$row_rsTeamMember['contestant_gender'];?>
      </option>
<?php
    } ?>
          </select></label></td> 
        </tr>        
        <tr>
          <td>Medlem 4</td>  
          <td nowrap="nowrap">
          <label><select name="contestant_team_member_4" id="contestant_team_member_4">
<?php
        if ($update_contestant_team_member_4=== 0 || $update_contestant_team_member_4 === ''){
            echo '<option value="">V&auml;lj lagmedlem 4!';
        }
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option value="<?php echo $row_rsTeamMember['contestant_id'] ?>"
<?php if (!(strcmp($update_contestant_team_member_4, $row_rsTeamMember['contestant_id']))) { echo "selected=\"selected\"";} ?>>
<?php echo $row_rsTeamMember['contestant_name'].' | '.$row_rsTeamMember['contestant_birth'].' | '.$row_rsTeamMember['contestant_gender'];?>
      </option>
<?php
    } ?>
          </select></label></td> 
        </tr>
        <tr>
          <td>Medlem 5</td>  
          <td nowrap="nowrap">
          <label><select name="contestant_team_member_5" id="contestant_team_member_5">
<?php
        if ($update_contestant_team_member_5 === 0 || $update_contestant_team_member_5 === ''){
            echo '<option value="">V&auml;lj lagmedlem 5!';
        }
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option value="<?php echo $row_rsTeamMember['contestant_id'] ?>"
<?php if (!(strcmp($update_contestant_team_member_5, $row_rsTeamMember['contestant_id']))) { echo "selected=\"selected\"";} ?>>
<?php echo $row_rsTeamMember['contestant_name'].' | '.$row_rsTeamMember['contestant_birth'].' | '.$row_rsTeamMember['contestant_gender'];?>
      </option>
<?php
    } ?>
          </select></label></td> 
        </tr>        
        <tr>
          <td>
        <input type="hidden" name="contestant_birth" value="<?php echo $update_contestant_birth ?>"/>              
        <input type="hidden" name="contestant_birth_max" value="<?php echo $update_contestant_birth_max ?>"/>              
        <input type="hidden" name="contestant_team" value="<?php echo $update_contestant_team ?>" />
        <input type="hidden" name="contestant_id" id="contestant_id" value="<?php echo $update_contestant_id; ?>" />        
        <input type="hidden" name="MM_update_team" value="update_team" />
          </td>
          <td><label>
              <input type="submit" name="update_team" id="update_team" value="Uppdatera lag" />
          </label></td>
        </tr>
      </table>
</form>   
<?php    
}//Show team form

$stmt_rsTeamMembers->closeCursor();//Kill statement
}//Update team contestant 

//Excute sql update and don't show form
if ($output_form === 'no') {
    if (filter_input(INPUT_POST,"MM_update_contestant") === "update_contestant" || filter_input(INPUT_POST,"MM_update_team") === "update_team") {    
    try { //Catch anything wrong with query
    require('Connections/DBconnection.php');    
    //UPDATE selected Contestant or Team    
    $updateSQL = "UPDATE contestants SET 
    account_id = :account_id, 
    contestant_name = :contestant_name, 
    contestant_birth = :contestant_birth, 
    contestant_birth_max = :contestant_birth_max, 
    contestant_gender = :contestant_gender, 
    contestant_team = :contestant_team, 
    contestant_team_member_1 = :contestant_team_member_1, 
    contestant_team_member_2 = :contestant_team_member_2, 
    contestant_team_member_3 = :contestant_team_member_3, 
    contestant_team_member_4 = :contestant_team_member_4, 
    contestant_team_member_5 = :contestant_team_member_5 
    WHERE contestant_id = :contestant_id"; 
    $stmt = $DBconnection->prepare($updateSQL);                        
    $stmt->bindValue(':account_id', $_SESSION['MM_Account'], PDO::PARAM_INT);
    $stmt->bindValue(':contestant_name', $update_contestant_name, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_birth', $update_contestant_birth, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_birth_max', $update_contestant_birth_max, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_gender', $update_contestant_gender, PDO::PARAM_STR);
    $stmt->bindValue(':contestant_team', $update_contestant_team, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_team_member_1', $update_contestant_team_member_1, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_team_member_2', $update_contestant_team_member_2, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_team_member_3', $update_contestant_team_member_3, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_team_member_4', $update_contestant_team_member_4, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_team_member_5', $update_contestant_team_member_5, PDO::PARAM_INT);
    $stmt->bindValue(':contestant_id', $update_contestant_id, PDO::PARAM_INT);
    $stmt->execute();
    }   catch(PDOException $ex) {
            echo 'An Error occured with query $updateSQL: '.$ex->getMessage();
        }
    if ($MM_authorizedUsers === "1") { 
        $updateGoTo = "RegsHandleAll.php#registration_insert";
    }else{
        $updateGoTo = "RegInsert_reg.php#registration_insert";
    }        
    header(sprintf("Location: %s", $updateGoTo));
    }
 
//$stmt->closeCursor();//Kill statements
}//$output_form = no ?>
        </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();