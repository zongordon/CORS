<?php 
//Added link to previous age if no contestants exist

//Declare and initialise variables
$colname_rsClass = '';$class_gender = ''; $contestant_height = ''; $contestant_result = ''; $contestant_gender = ''; $sql_db = '';

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Fetch the selected Class
if (filter_input(INPUT_GET, 'class_id')) {
    $colname_rsClass = filter_input(INPUT_GET, 'class_id');
}

// Delete the selected registration when clicking the Delete button
if (filter_input(INPUT_POST,'MM_delete') && filter_input(INPUT_POST,'MM_delete') == 'delete_reg') {
    $reg_id = filter_input(INPUT_POST, 'reg_id');
    require('Connections/DBconnection.php');           
    $query = "DELETE FROM registration WHERE reg_id = :reg_id";
    $stmt_rsUserexists = $DBconnection->prepare($query);
    $stmt_rsUserexists->bindValue(':reg_id', $reg_id, PDO::PARAM_INT);   
    $stmt_rsUserexists->execute();
    $stmt_rsUserexists->closeCursor();
}

// If the update form is sent (button clicked), validation will start
if (filter_input(INPUT_POST,'MM_update') && filter_input(INPUT_POST,'MM_update') == 'update_reg') {
    $colname_rsClass = filter_input(INPUT_POST, 'class');
   //Catch anything wrong with query
    try {
        //Select Class gender for selected class
        require('Connections/DBconnection.php');           
        $query1 = "SELECT class_gender FROM classes WHERE class_id = :class_id";
        $stmt_rsClassGender = $DBconnection->prepare($query1);
        $stmt_rsClassGender->execute(array(':class_id' => $colname_rsClass));
        $row_rsClassGender = $stmt_rsClassGender->fetch(PDO::FETCH_ASSOC);
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }               
   $class_gender = $row_rsClassGender['class_gender'];
   $contestant_result = filter_input(INPUT_POST, 'contestant_result');
   $contestant_height = filter_input(INPUT_POST, 'contestant_height');
   $contestant_gender = filter_input(INPUT_POST, 'contestant_gender');
   $reg_id = filter_input(INPUT_POST, 'reg_id');
      
    $val = new Validation();
    if(empty($contestant_height)){
        $contestant_height = 0;
        $val->name('l&auml;ngd')->value($contestant_height)->pattern('int');
    }else{    
    $min = 100;//minimum value of integers
    $max = 225;//maximum value of integers
    $val->name('l&auml;ngd')->value($contestant_height)->valuePattern($min,$max);
    }    
    $val->name('resultat')->value($contestant_result)->pattern('int');
    $val->name('k&ouml;n')->value($contestant_gender)->equal($class_gender);

    if($val->isSuccess()){
    	$sql_db = 'yes';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
      	$sql_db = 'no';
    } 
//Kill statement
$stmt_rsClassGender->closeCursor();
}			
	//Save the updated information 
  	if ($sql_db === 'yes') {
            
        //Catch anything wrong with query
        try {
            require('Connections/DBconnection.php');               
            // Update the registration in accordance with the input from the update form, regarding class and result for the contestant when clicking the button and if the form is validated ok
            $query2 = "UPDATE registration SET 
            contestant_result = :contestant_result, 
            contestant_height = :contestant_height, 
            class_id = :class_id  
            WHERE reg_id = :reg_id";
            $stmt_rsUpdate = $DBconnection->prepare($query2);                                  
            $stmt_rsUpdate->bindValue(':contestant_result', $contestant_result, PDO::PARAM_INT);       
            $stmt_rsUpdate->bindValue(':contestant_height', $contestant_height, PDO::PARAM_INT);    
            $stmt_rsUpdate->bindValue(':class_id', $colname_rsClass, PDO::PARAM_INT);
            $stmt_rsUpdate->bindValue(':reg_id', $reg_id, PDO::PARAM_INT);
            $stmt_rsUpdate->execute(); 
        }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }   
        $stmt_rsUpdate->closeCursor();
	}	

    //Catch anything wrong with query
    try {
        require('Connections/DBconnection.php');           
        // Select the contestants and their information for the selected class
        $query3 = "SELECT a.club_name, re.reg_id, re.contestant_result, re.contestant_height, re.contestant_startnumber, "
                . "co.contestant_name, co.contestant_team, co.contestant_gender, co.contestant_birth, co.contestant_birth_max,"
                . "co.contestant_team_member_1, co.contestant_team_member_2,co.contestant_team_member_3, co.contestant_team_member_4,"
                . "co.contestant_team_member_5,cl.class_id FROM registration AS re INNER JOIN classes AS cl USING (class_id) "
                . "INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) "
                . "INNER JOIN clubregistration AS clu USING (club_reg_id) "
                . "WHERE cl.class_id = :class_id ORDER BY club_startorder, reg_id";
        $stmt_rsRegistrations = $DBconnection->prepare($query3);
        $stmt_rsRegistrations->execute(array(':class_id' => $colname_rsClass));
        $totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }              

    //Catch anything wrong with query
    try {
        // Select data for the selected class
        $query4 = "SELECT class_id, class_team, class_category, class_discipline, class_gender_category, class_age, "
                . "class_weight_length FROM classes WHERE class_id = :class_id";
        $stmt_rsClass = $DBconnection->prepare($query4);
        $stmt_rsClass->execute(array(':class_id' => $colname_rsClass));
        $row_rsClass = $stmt_rsClass->fetch(PDO::FETCH_ASSOC);
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }                   
if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
<h3>Det finns ingen t&auml;vlande i klassen!</h3>
<p><a href="ClassesList.php">Klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
<?php if ($MM_authorizedUsers === "1") { ?>  
<p><a href="ClassesList.php">Tillbaka till T&auml;vlingsklasser</a></p>
<?php 
      }//Show link
} // Show if recordset empty 
if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>
<?php if ($MM_authorizedUsers === "1") { ?>
<p>&Auml;ndra t&auml;vlingsklass, l&auml;ngd eller placering och klicka p&aring; Spara eller ta bort anm&auml;lan.</p>
<?php }?>
<h3>
<?php  
if($row_rsClass['class_team'] === 1){echo'Lag - ';} echo $row_rsClass['class_discipline'].' | '.$row_rsClass['class_gender_category'].' | '.$row_rsClass['class_category'];
if ($row_rsClass['class_age'] == "") { 
        echo ""; 
} 
if ($row_rsClass['class_age'] <> "") { 
    echo ' | '.$row_rsClass['class_age'].' &aring;r'.'  ';     
}
if ($row_rsClass['class_weight_length'] == "") { 
    echo "";     
} 
if ($row_rsClass['class_weight_length'] <> "") { 
echo ' | '.$row_rsClass['class_weight_length'];
}
?>
</h3>
<?php if ($MM_authorizedUsers === "1") { ?>
<table class="medium_tbl" border="1">
<tr><td>
    <strong>T&auml;vlingsklass - Klubb - T&auml;vlande - L&auml;ngd (eventuellt) - Placering - Spara - Ta bort anm&auml;lan</strong>
</td></tr>
<tr><td>
<table width ="100%" >
<?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) { 
        if ($row_rsRegistrations['contestant_team'] === 1){
            $team_member1 = $row_rsRegistrations['contestant_team_member_1'];
            $team_member2 = $row_rsRegistrations['contestant_team_member_2'];
            $team_member3 = $row_rsRegistrations['contestant_team_member_3'];
            $team_member4 = $row_rsRegistrations['contestant_team_member_4'];
            $team_member5 = $row_rsRegistrations['contestant_team_member_5'];
            //Catch anything wrong with query
            try {
            //Select contestant name for team members
            require('Connections/DBconnection.php');           
            $query6 = "SELECT contestant_name FROM contestants WHERE contestant_id = $team_member1 || contestant_id = $team_member2 ||"
            . "contestant_id = $team_member3 || contestant_id = $team_member4 || contestant_id = $team_member5 ORDER BY contestant_name";            
            $stmt_rsTeamMembers = $DBconnection->query($query6);
            }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }
        }
?>
<tr>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="application/x-www-form-urlencoded" name="update_reg" id="update_reg">
<td>
<select name="class" id="class">
<?php
    //Calculate the contestant's age at te date of the competition
    $calculate_age = new AgeCalc;
    $calculate_age->comp_start_date = $comp_start_date;
    $calculate_age->contestant_birth = $row_rsRegistrations['contestant_birth'];
    $calculate_age->contestant_birth_max = $row_rsRegistrations['contestant_birth_max'];
    $calculate_age->contestant_team = $row_rsRegistrations['contestant_team'];
    $calculate_age->contestant_gender = $row_rsRegistrations['contestant_gender'];

     //Catch anything wrong with query
    try {
    //Select classes applicable for the contestant'S age and gender
    require('Connections/DBconnection.php');               
    $query_rsClassData = 
            "SELECT cl.class_id, cl.class_team, cl.class_category, cl.class_discipline, cl.class_gender, "
            . "cl.class_gender_category, cl.class_weight_length, cl.class_age "
            . "FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id "
            . "WHERE "
            . "comp_current = 1 && cl.class_team = :contestant_team && cl.class_gender = :contestant_gender && "
            . "SUBSTRING(cl.class_age, 1, 2) = :contestant_age_min && SUBSTRING(cl.class_age, 1, 2) = :contestant_age_max "
            . "|| comp_current = 1 && cl.class_team = :contestantteam && cl.class_gender = :contestantgender && "
            . "SUBSTRING(cl.class_age, 4, 2) >= :contestantage_min && SUBSTRING(cl.class_age, 4, 2) <= :contestantage_max "
            . "ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, cl.class_gender_category"; 
    $stmt_rsClassData = $DBconnection->prepare($query_rsClassData);
    $stmt_rsClassData->execute(array(':contestant_gender'=>$calculate_age->contestant_gender, ':contestant_team'=>$calculate_age->contestant_team,
        ':contestant_age_min'=>$calculate_age->calculate_age('contestant_age_min'),':contestant_age_max'=>$calculate_age->calculate_age('contestant_age_max'), ':contestantgender'=>$calculate_age->contestant_gender,
        ':contestantteam'=>$calculate_age->contestant_team, ':contestantage_min'=>$calculate_age->calculate_age('contestant_age_min'),':contestantage_max'=>$calculate_age->calculate_age('contestant_age_max'),));
    $row_rsClassData = $stmt_rsClassData->fetchAll(PDO::FETCH_ASSOC);      
    } catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
      }
foreach($row_rsClassData as $row_rsClasses) {
?>
<option value="<?php echo $row_rsClasses['class_id']?>"<?php if (!(strcmp($row_rsClasses['class_id'], $colname_rsClass))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_weight_length'].' | '.$row_rsClasses['class_age'].' &aring;r'?></option> 
<?php
} 
?>
</select></label>
</td>
<td>
<?php echo $row_rsRegistrations['club_name']; ?>
</td>
<td>
<?php if ($row_rsRegistrations['contestant_team'] === 1){ 
        echo $row_rsRegistrations['contestant_name'].':';
        while ($row_rsTeamMembers = $stmt_rsTeamMembers->fetch(PDO::FETCH_ASSOC)){ 
        echo ' - '.$row_rsTeamMembers['contestant_name'];                    
        }
      } else {
        echo $row_rsRegistrations['contestant_name'];  
      }?>    
</td>
<td><label>
<input name="contestant_height" type="text" id="contestant_height" value="<?php if ($row_rsRegistrations['contestant_height'] < 1){ echo ''; } else { echo $row_rsRegistrations['contestant_height'];} ?>" size="1" maxlength="3" />
</label>cm
</td>
<td><label>
<select name="contestant_result" id="contestant_result">
<option value="0"<?php if ($row_rsRegistrations['contestant_result'] === NULL || $row_rsRegistrations['contestant_result'] === 0) {echo "selected=\"selected\"";} ?>>Oplacerad</option>    
<option value="1"<?php if ($row_rsRegistrations['contestant_result'] === 1) {echo "selected=\"selected\"";} ?>>1:a</option>
<option value="2"<?php if ($row_rsRegistrations['contestant_result'] === 2) {echo "selected=\"selected\"";} ?>>2:a</option>
<option value="3"<?php if ($row_rsRegistrations['contestant_result'] === 3) {echo "selected=\"selected\"";} ?>>3:e</option>
</select>
</label></td>
<td>
</td>
<td>
    <label><input type="submit" name="update_reg" class= "button" id="update_reg" value="Spara" /></label>
</td>
<input name="reg_id" type="hidden" id="reg_id" value="<?php echo $row_rsRegistrations['reg_id']; ?>" />
<input type="hidden" name="MM_update" value="update_reg" />
<input type="hidden" name="contestant_gender" id="contestant_gender" value="<?php echo $row_rsRegistrations['contestant_gender']; ?>" />
</form></td>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="application/x-www-form-urlencoded" name="delete_reg" id="delete_reg">
    <td>
        <label><input type="submit" name="delete_reg" class= "button" id="delete_reg" value="Ta bort" /></label>
    </td>
    <input name="reg_id" type="hidden" id="reg_id" value="<?php echo $row_rsRegistrations['reg_id']; ?>" />
    <input type="hidden" name="MM_delete" value="delete_reg" />
</form>
</tr>
<?php } ?>
</table>
</td></tr>
</table>
<?php 
//Kill statement
//$stmt_rsClasses->closeCursor();
    } else { ?>
  <table class="medium_tbl" border="1">
    <tr>
      <td><strong>Startnr.</strong></td>        
      <td><strong>Klubb</strong></td>
      <td><strong>T&auml;vlande</strong></td>
      <td><strong>L&auml;ngd (eventuellt)</strong></td>
      </tr>
<?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) { 
        if ($row_rsRegistrations['contestant_team'] === 1){
            $team_member1 = $row_rsRegistrations['contestant_team_member_1'];
            $team_member2 = $row_rsRegistrations['contestant_team_member_2'];
            $team_member3 = $row_rsRegistrations['contestant_team_member_3'];
            $team_member4 = $row_rsRegistrations['contestant_team_member_4'];
            $team_member5 = $row_rsRegistrations['contestant_team_member_5'];
            //Catch anything wrong with query
            try {
            //Select Class gender for selected class
            require('Connections/DBconnection.php');           
            $query6 = "SELECT contestant_name FROM contestants WHERE contestant_id = $team_member1 || contestant_id = $team_member2 ||"
            . "contestant_id = $team_member3 || contestant_id = $team_member4 || contestant_id = $team_member5 ORDER BY contestant_name";            
            $stmt_rsTeamMembers = $DBconnection->query($query6);
            }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }
        }
?>
      <tr>
        <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>          
        <td><?php echo $row_rsRegistrations['club_name']; ?></td>
<?php if ($row_rsRegistrations['contestant_team'] === 1){ ?>        
        <td><?php echo $row_rsRegistrations['contestant_name'].':'?>
<?php   while ($row_rsTeamMembers = $stmt_rsTeamMembers->fetch(PDO::FETCH_ASSOC)){ 
            echo ' - '.$row_rsTeamMembers['contestant_name'];
        }  
      } else { ?>
        <td><?php echo $row_rsRegistrations['contestant_name'];
      } ?>
        </td>
        <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
      </tr>
<?php } ?>
  </table>
<?php }?>
<p><a href="ClassesList.php">Klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
<?php 
//Kill statement
$stmt_rsRegistrations->closeCursor();
} // Show if recordset not empty 
?>
    </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>