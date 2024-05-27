<?php 
//Added "name" in $patterns to allow character "-" when validating names: https://github.com/zongordon/CORS/issues/74
$contestant_age_min = '';
$contestant_age_max = '';
//Catch anything wrong with query
try {
// Select account data for the selected club
require('Connections/DBconnection.php');           
$query_rsSelectedClub = "SELECT account_id, club_name, active FROM account WHERE account_id = :account_id";
$stmt_rsSelectedClub = $DBconnection->prepare($query_rsSelectedClub);
$stmt_rsSelectedClub->execute(array(':account_id'=>$colname_rsSelectedClub));
$row_rsSelectedClub = $stmt_rsSelectedClub->fetch(PDO::FETCH_ASSOC);
$totalRows_rsSelectedClub = $stmt_rsSelectedClub->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }    
    
if ($colname_rsSelectedClub <> "") {//Prevents attempt to create session variable before club is selected by admin
//Creating Session variable for the selected club
$_SESSION['MM_Account'] = $row_rsSelectedClub['account_id'];
}

//Catch anything wrong with query
try {
//Select all active accounts
require('Connections/DBconnection.php');           
$query_rsAccounts = "SELECT account_id, club_name, active FROM account WHERE active = 1 ORDER BY club_name ASC";
$stmt_rsAccounts = $DBconnection->query($query_rsAccounts);
$row_rsAccounts = $stmt_rsAccounts->fetchAll(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
//Catch anything wrong with query
try {
// Select number of classes including last date for registrations, for the active competition
require('Connections/DBconnection.php');           
$query_rsClasses = "SELECT COUNT(class_id) FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1";
$stmt_rsClasses = $DBconnection->query($query_rsClasses);
$row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }    
// Show if recordset (classes) rsClasses empty 
if ($row_rsClasses['COUNT(class_id)'] === 0) {?>
    <p>Det finns inga klasser att anm&auml;la till &auml;n!</p>
<?php
}
// If recordset (classes) rsClasses is NOT empty 
if ($row_rsClasses['COUNT(class_id)'] > 0) {
       //Show if the last date for registration is passed and logged in user not Admin
    if ($passedDate === 1 && $MM_authorizedUsers === "0") { ?>
	<div class="error">
        <h3>Sista anm&auml;lningsdagen &auml;r passerad och inga till&auml;gg eller &auml;ndringar g&aring;r att g&ouml;ra online! Kontakta t&auml;vlingsledningen vid akuta behov.</h3>
	</div>
<?php
    }
  //Show if the last date for registration is NOT passed OR logged in user IS Admin
  if ($passedDate === 0 && $MM_authorizedUsers === "0" || $MM_authorizedUsers === "1") { ?>
<h3>Registera t&auml;vlande klubbmedlemmar och anm&auml;l dem till deras t&auml;vlingsklasser</h3>
<p>Anm&auml;lan g&ouml;rs i <?php if ($MM_authorizedUsers === "0") { echo 'fem';} else {echo 'fyra';} ?> steg:</p>
<ol>
<?php 
if ($MM_authorizedUsers === "1") {  
  echo'<li>V&auml;lj klubb</li>' ;
} ?>
  <li>Skriv in namnen p&aring; de coacher som ska st&ouml;tta klubbens t&auml;vlande.</li>
  <li>L&auml;gg in klubbens t&auml;vlande en och en och lag som ska t&auml;vla för klubben. De l&auml;ggs till i listan under formul&auml;ret, allt eftersom de l&auml;ggs in. </li>
  <li>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i.</li>
  <li>Alla t&auml;vlingsanm&auml;lningar listas l&auml;ngst ned p&aring; sidan, s&aring; att du kan  ta bort dem om n&aring;got har blivit fel.</li>
  </ol>
<?php
//Show if logged in is Admin
if ($MM_authorizedUsers === "1"){ ?>
<h3>1. V&auml;lj klubb</h3>
<p>
<?php if ($colname_rsSelectedClub === "") {//Show if no club is selected
        echo "<strong>Ingen klubb &auml;r vald &auml;n! </strong>";    
      } ?>
V&auml;l klubb och klicka p&aring; V&auml;lj!</p>
<form id="SelectClub" name="SelectClub" method="post" action="<?php echo $editFormAction; ?>">
  <table class="narrow_tbl" border="0">
    <tr>
      <td valign="top">Klubb</td>
      <td><label>
        <select name="account_id" id="account_id">
          <?php
foreach($row_rsAccounts as $row_rsAccount) {  
?>
          <option value="<?php echo $row_rsAccount['account_id']?>"
       <?php if (($colname_rsSelectedClub <> "") && !(strcmp($row_rsAccount['account_id'], $_SESSION['MM_Account']))) {
                echo "selected=\"selected\"";
             } ?>>
      <?php echo $row_rsAccount['club_name']?>
          </option>
<?php
} ;?>
        </select>
      </label></td>
      <td><input type="submit" name="submit" class= "button" id="submit" value="V&auml;lj klubb" /></td>
    </tr>
  </table>
</form>
<?php
} //Show if logged in is Admin
//Show club registration form if a club is selected and "Välj" button is clicked 
if ($totalRows_rsSelectedClub <> "") { // Do not show if recordset empty
?>
<h3>
<?php 
    if (!empty($_SESSION['MM_Account'])) {//Show if club is selected
        if ($MM_authorizedUsers === "0") { echo '1';} else {echo '2';} ?>. Skriv in klubbens coacher</h3>
        <p>Skriv in namnen p&aring; de coacher som ska st&ouml;tta era t&auml;vlande och klicka p&aring; spara.</p>
<?php 
// Validate the club registration form if the "Spara" or "Uppdatera" button is clicked
    $coach_names = '';
if ((filter_input(INPUT_POST,"MM_insert_clubregistration") === "new_club_reg") || (filter_input(INPUT_POST,"MM_update_clubregistration") === "update_club_reg")) {
    $coach_names = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'coach_names'), MB_CASE_TITLE,"UTF-8"));
 
    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('klubbens coacher')->value($coach_names)->pattern('text')->required()->min($length);
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
        $output_form = 'yes';
    }    
	if ($output_form == 'no') {		
            //Insert new club registration if form validated ok
            if (filter_input(INPUT_POST,"MM_insert_clubregistration") === "new_club_reg") {
            //Catch anything wrong with query
            try {
            //INSERT coaches
            require('Connections/DBconnection.php');         
            $insertSQL = "INSERT INTO clubregistration (coach_names, account_id, comp_id) VALUES (:coach_names, :account_id, :comp_id)";
            $stmt = $DBconnection->prepare($insertSQL);
            $stmt->bindValue(':coach_names', $coach_names, PDO::PARAM_STR);
            $stmt->bindValue(':account_id', $_SESSION['MM_Account'], PDO::PARAM_INT);
            $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
            $stmt->execute();
            }   catch(PDOException $ex) {
                echo "An Error occured with queryX: ".$ex->getMessage();
                }                            
            }

            //Update club registration if button is clicked            
            if (filter_input(INPUT_POST,"MM_update_clubregistration") === "update_club_reg") {
            $club_reg_id = filter_input(INPUT_POST,"club_reg_id");
            //Catch anything wrong with query
            try {
            //UPDATE coaches
            require('Connections/DBconnection.php');                     
            $updateSQL = "UPDATE clubregistration SET coach_names = :coach_names WHERE club_reg_id = :club_reg_id";
            $stmt = $DBconnection->prepare($updateSQL);
            $stmt->bindValue(':coach_names', $coach_names, PDO::PARAM_STR);
            $stmt->bindValue(':club_reg_id', $club_reg_id, PDO::PARAM_INT);
            $stmt->execute();
            }   catch(PDOException $ex) {
                echo "An Error occured with queryX: ".$ex->getMessage();
                }            
        }
    //Kill statement
    $stmt->closeCursor();
 	}
}
//Catch anything wrong with query
try {
//SELECT registered club coaches 
require('Connections/DBconnection.php');         
$query_rsClubReg = "SELECT a.club_name, cl.club_reg_id, cl.coach_names FROM clubregistration AS cl INNER JOIN competition AS co USING (comp_id) INNER JOIN account AS a USING (account_id) WHERE account_id = :account_id AND comp_current = 1";
$stmt_rsClubReg = $DBconnection->prepare($query_rsClubReg);
$stmt_rsClubReg->execute(array(':account_id'=>$_SESSION['MM_Account']));
$row_rsClubReg = $stmt_rsClubReg->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClubReg = $stmt_rsClubReg->rowCount();
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

 	if ($totalRows_rsClubReg === 0) { // Show if recordset empty ?>     
    <form id="new_club_reg" name="new_club_reg" method="POST" action="<?php echo $editFormAction; ?>">
<?php
	}?> 
<?php if ($totalRows_rsClubReg <> 0) { // Show if recordset NOT empty ?>
    <form id="update_club_reg" name="update_club_reg" method="POST" action="<?php echo $editFormAction; ?>"> 
<?php
	}?>                       
     <table class="narrow_tbl" border="0">
      <tr>
        <td valign="top">Coacher</td>
        <td><label>
          <input name="coach_names" type="text" id="coach_names" value="<?php if ($totalRows_rsClubReg <> 0) { echo $row_rsClubReg['coach_names'];} ?>" size="55" /></label></td>
      </tr>
      <tr>
        <td>
          <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $comp_id; ?>" />
          <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />        </td>
        <td><label>
	<?php if ($totalRows_rsClubReg === 0) { // Show if recordset empty ?>   
          <input type="submit" name="new_club_reg" class= "button" id="new_club_reg" value="Spara" />
          <input type="hidden" name="MM_insert_clubregistration" value="new_club_reg" />
	<?php } ?> 
	  	<?php if ($totalRows_rsClubReg <> 0) { // Show if recordset NOT empty ?>
          <input type="submit" name="update_club_reg" class= "button" id="update_club_reg" value="Uppdatera" /></label>
          <input type="hidden" name="MM_update_clubregistration" value="update_club_reg" /> 
		<?php } ?>         
        </label></td>
      </tr>
    </table>      
    </form>
 <?php   
    }//Show if club is selected
 }
 ?>
<?php 
    if ($totalRows_rsClubReg > 0) { // Show if recordset not empty ?>
<h3><a name="contestants" id="contestants"></a><?php if ($MM_authorizedUsers === "0") { echo '2';} else {echo '3';} ?>. L&auml;gg in klubbens t&auml;vlande</h3>
<p>L&auml;gg in klubbens t&auml;vlande en och en. Ange namn, f&ouml;delsedatum och k&ouml;n.</p>
<?php 
//Declare and initialise variables
    $insert_contestant_name = '';$insert_contestant_birth = '';$insert_contestant_gender = '';$insert_contestant_team = '';
// Validate the contestant form if the button is clicked	
if (filter_input(INPUT_POST,"MM_insert_contestant") === "new_contestant") {
    $insert_contestant_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contestant_name'), MB_CASE_TITLE,"UTF-8"));    
    $insert_contestant_birth = filter_input(INPUT_POST,'contestant_birth');
    $insert_contestant_gender = filter_input(INPUT_POST,'contestant_gender');

    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('namn')->value($insert_contestant_name)->pattern('name')->required()->min($length);
    $val->name('f&ouml;delsedatum')->value($insert_contestant_birth)->datePattern('Y-m-d')->required();
    $val->name('k&ouml;n')->value($insert_contestant_gender)->pattern('words')->required();
	        
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
        $output_form = 'yes';
    } 
	if ($output_form === 'no') {
            //Catch anything wrong with query
            try {
            //INSERT new contestants
            require('Connections/DBconnection.php');         
            $insertSQL = "INSERT INTO contestants (contestant_name, contestant_birth, contestant_birth_max, contestant_gender, "
                    . "account_id) VALUES (:contestant_name, :contestant_birth, :contestant_birth_max, :contestant_gender, :account_id)";
            $stmt = $DBconnection->prepare($insertSQL);
            $stmt->bindValue(':contestant_name', $insert_contestant_name, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_birth', $insert_contestant_birth, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_birth_max', $insert_contestant_birth, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_gender', $insert_contestant_gender, PDO::PARAM_STR);
            $stmt->bindValue(':account_id', $_SESSION['MM_Account'], PDO::PARAM_INT);
            $stmt->execute();
            }   catch(PDOException $ex) {
                    echo "An Error occured with queryX: ".$ex->getMessage();
                }         

                if ($MM_authorizedUsers === "1") { 
  		$insertGoTo = "RegsHandleAll.php#registration_insert";
                }else{
                $insertGoTo = "RegInsert_reg.php#registration_insert";
                }
		header(sprintf("Location: %s", $insertGoTo));  		
	}	
}
?>
<form id="new_contestant" name="new_contestant" method="POST" action="<?php echo $editFormAction; ?>">
      <table class="narrow_tbl" border="0">
        <tr>
          <td>T&auml;vlandes namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="30" value="<?php echo $insert_contestant_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>F&ouml;delsedatum (yyyy-mm-dd)</td>
          <td valign="top"><label>
            <input name="contestant_birth" type="text" id="contestant_birth" value="<?php echo $insert_contestant_birth; ?>" size="8" maxlength="10"/>
          </label></td>
        </tr>
        <tr>
          <td>K&ouml;n</td>
          <td valign="top">
            <label>
              <input name="contestant_gender" type="radio" id="contestant_gender" value="Man" <?php if ($insert_contestant_gender === "Man"){ echo "checked='checked'";} ?>/>
              Man</label>
            <label>
    <input type="radio" name="contestant_gender" id="contestant_gender" value="Kvinna" <?php if ($insert_contestant_gender === "Kvinna"){ echo "checked='checked'";} ?>/>
              Kvinna</label>
          </td>
        </tr>
        <tr>
          <td>
        <input type="hidden" name="MM_insert_contestant" value="new_contestant" />
        <input type="hidden" name="account_id" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
          </td>
          <td><label>
              <input type="submit" name="new_contestant" class= "button" id="new_contestant" value="Ny t&auml;vlande" />
          </label></td>
        </tr>
      </table>
</form>   
<?php 
//Catch anything wrong with query
try {
// Select all registered contestants for the club
$query_rsTeamMembers = "SELECT co.contestant_id, co.account_id, co.contestant_name, co.contestant_birth, "
        . "co.contestant_gender, co.contestant_team, co.contestant_team_member_1, co.contestant_team_member_2, "
        . "co.contestant_team_member_3, co.contestant_team_member_4, co.contestant_team_member_5 "
        . "FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND co.account_id = :account_id "
        . "WHERE contestant_team <> 1 ORDER BY co.contestant_name";
$stmt_rsTeamMembers = $DBconnection->prepare($query_rsTeamMembers);
$stmt_rsTeamMembers ->execute(array(':account_id'=>$_SESSION['MM_Account']));
$row_rsTeamMembers = $stmt_rsTeamMembers->fetchAll(PDO::FETCH_ASSOC);   
$totalRows_rsTeamMembers = $stmt_rsTeamMembers->rowCount();    
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
if ($totalRows_rsTeamMembers > 0) { // Show if recordset not empty ?> 
<p>L&auml;gg in klubbens lag. Ange namn p&aring; laget, och v&auml;lj vilka som ing&aring;r i laget.</p>        
<?php 
    //Declare and initialise variables
    $min_birthday='';$max_birthday='';$gender_init = '';$gender_final ='';$insert_contestant_team_member_3='';$insert_contestant_team_member_4='';$insert_contestant_team_member_5='';
// Validate the contestant form if the button is clicked	
if (filter_input(INPUT_POST,"MM_insert_team") === "new_team") {
    $insert_contestant_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contestant_name'), MB_CASE_TITLE,"UTF-8"));    
    $insert_contestant_team = filter_input(INPUT_POST,'contestant_team');
    $insert_contestant_team_member_1 = filter_input(INPUT_POST,'contestant_team_member_1');
    $insert_contestant_team_member_2 = filter_input(INPUT_POST,'contestant_team_member_2');
    if(filter_input(INPUT_POST,'contestant_team_member_3') === ''){ $insert_contestant_team_member_3 = 0;}else{$insert_contestant_team_member_3 = filter_input(INPUT_POST,'contestant_team_member_3');}
    if(filter_input(INPUT_POST,'contestant_team_member_4') === ''){ $insert_contestant_team_member_4 = 0;}else{$insert_contestant_team_member_4 = filter_input(INPUT_POST,'contestant_team_member_4');}
    if(filter_input(INPUT_POST,'contestant_team_member_5') === ''){ $insert_contestant_team_member_5 = 0;}else{$insert_contestant_team_member_5 = filter_input(INPUT_POST,'contestant_team_member_5');}

    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('lagets namn')->value($insert_contestant_name)->pattern('alphanum')->required()->min($length);
    $val->name('tillr&auml;ckligt m&aring;nga lagmedlemmar')->value($insert_contestant_team_member_1)->pattern('int')->required();
    $val->name('tillr&auml;ckligt m&aring;nga lagmedlemmar')->value($insert_contestant_team_member_2)->pattern('int')->required();
	        
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
        if($row_rsTeamMember['contestant_id']==$insert_contestant_team_member_1||$row_rsTeamMember['contestant_id']==$insert_contestant_team_member_2||$row_rsTeamMember['contestant_id']==$insert_contestant_team_member_3||$row_rsTeamMember['contestant_id']==$insert_contestant_team_member_4||$row_rsTeamMember['contestant_id']==$insert_contestant_team_member_5){
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
            elseif ($min_birthday === $row_rsTeamMember['contestant_birth']) {//The members's birth is the same as previous
            } 
            elseif ($min_birthday > $row_rsTeamMember['contestant_birth']) {//The members's birth is the earlier than previous
                $min_birthday = $row_rsTeamMember['contestant_birth']; 
            }
            elseif ($max_birthday === $row_rsTeamMember['contestant_birth']) {//The members's birth is the same as previous
            } 
            elseif ($max_birthday < $row_rsTeamMember['contestant_birth']) {//The members's birth is the later than previous
                $max_birthday = $row_rsTeamMember['contestant_birth']; 
            }                
        } else {//The contestant is not selected as team member    
          }          
    }
	if ($output_form === 'no') {
            //Catch anything wrong with query
            try {
            //INSERT new team
            require('Connections/DBconnection.php');         
            $insertSQL = "INSERT INTO contestants (contestant_name, contestant_birth, contestant_birth_max, contestant_gender, "
                    . "contestant_team, contestant_team_member_1, contestant_team_member_2, contestant_team_member_3, "
                    . "contestant_team_member_4, contestant_team_member_5, account_id) "
                    . "VALUES (:contestant_name, :contestant_birth, :contestant_birth_max,:contestant_gender, :contestant_team, "
                    . ":contestant_team_member_1, :contestant_team_member_2, :contestant_team_member_3, "
                    . ":contestant_team_member_4, :contestant_team_member_5, :account_id)";
            $stmt = $DBconnection->prepare($insertSQL);
            $stmt->bindValue(':contestant_name', $insert_contestant_name, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_birth', $min_birthday, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_birth_max', $max_birthday, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_gender', $gender_final, PDO::PARAM_STR);
            $stmt->bindValue(':contestant_team', $insert_contestant_team, PDO::PARAM_INT);
            $stmt->bindValue(':contestant_team_member_1', $insert_contestant_team_member_1, PDO::PARAM_INT);
            $stmt->bindValue(':contestant_team_member_2', $insert_contestant_team_member_2, PDO::PARAM_INT);
            $stmt->bindValue(':contestant_team_member_3', $insert_contestant_team_member_3, PDO::PARAM_INT);
            $stmt->bindValue(':contestant_team_member_4', $insert_contestant_team_member_4, PDO::PARAM_INT);
            $stmt->bindValue(':contestant_team_member_5', $insert_contestant_team_member_5, PDO::PARAM_INT);
            $stmt->bindValue(':account_id', $_SESSION['MM_Account'], PDO::PARAM_INT);
            $stmt->execute();
            }   catch(PDOException $ex) {
                    echo "An Error occured with queryX: ".$ex->getMessage();
                }         
                if ($MM_authorizedUsers === "1") { 
                    $insertGoTo = "RegsHandleAll.php#registration_insert";
                }else{
                    $insertGoTo = "RegInsert_reg.php#registration_insert";
                }
		header(sprintf("Location: %s", $insertGoTo)); 	
	}	
}        
?> 
<form id="new_team" name="new_team" method="POST" action="<?php echo $editFormAction; ?>">
      <table class="narrow_tbl" border="0">
        <tr>
          <td>Lagets namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="30" value="<?php echo $insert_contestant_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>Medlem 1</td>  
          <td nowrap="nowrap">
          <label><select name="contestant_team_member_1" id="contestant_team_member_1">             
      <option value="">V&auml;lj lagmedlem 1!              
<?php
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option type="number" value="<?php echo $row_rsTeamMember['contestant_id']?>">
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
      <option value="">V&auml;lj lagmedlem 2!              
<?php
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option type="number" value="<?php echo $row_rsTeamMember['contestant_id']?>">
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
      <option value="">V&auml;lj lagmedlem 3!              
<?php
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option type="number" value="<?php echo $row_rsTeamMember['contestant_id']?>">
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
      <option value="">V&auml;lj lagmedlem 4!              
<?php
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option type="number" value="<?php echo $row_rsTeamMember['contestant_id']?>">
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
      <option value="">V&auml;lj lagmedlem 5!              
<?php
    foreach($row_rsTeamMembers as $row_rsTeamMember) {
?>
      <option type="number" value="<?php echo $row_rsTeamMember['contestant_id']?>">
<?php echo $row_rsTeamMember['contestant_name'].' | '.$row_rsTeamMember['contestant_birth'].' | '.$row_rsTeamMember['contestant_gender'];?>
      </option>
<?php
    } ?>
          </select></label></td> 
        </tr>        
        <tr>
          <td>
        <input type="hidden" name="contestant_gender" value="<?php echo $row_rsTeamMember['contestant_gender']?>"/>
        <input type="hidden" name="contestant_birth" value="<?php echo $row_rsTeamMember['contestant_birth']?>"/>              
        <input type="hidden" name="contestant_team" value= 1 />
        <input type="hidden" name="MM_insert_team" value="new_team" />
        <input type="hidden" name="account_id" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
          </td>
          <td><label>
              <input type="submit" name="new_team" class= "button" id="new_team" value="Nytt lag" />
          </label></td>
        </tr>
      </table>
    </form>   
<?php 
}//Show if recordset rs_TeamMembers not empty 
//Catch anything wrong with query
try {
// Select all registered contestants for the club
require('Connections/DBconnection.php');           
$query_rsContestants = "SELECT co.contestant_id, co.account_id, co.contestant_name, co.contestant_birth, co.contestant_birth_max, "
        . "co.contestant_gender, co.contestant_team, co.contestant_team_member_1, co.contestant_team_member_2, "
        . "co.contestant_team_member_3, co.contestant_team_member_4, co.contestant_team_member_5 "
        . "FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND co.account_id = :account_id "
        . "ORDER BY co.contestant_team, co.contestant_name";
$stmt_rsContestants = $DBconnection->prepare($query_rsContestants);
$stmt_rsContestants ->execute(array(':account_id'=>$_SESSION['MM_Account']));
$totalRows_rsContestants = $stmt_rsContestants->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
        if ($totalRows_rsContestants > 0) { // Show if recordset not empty
        //Declare and initialise variables
        $contestant_height = '';$contestant_gender = '';$class_gender ='';
        // Validate the contestant form if the button is clicked  
        if (filter_input(INPUT_POST,"MM_insert_registration") === "new_registration") {
        $colname_rsClass = filter_input(INPUT_POST,'class_id');
        $contestant_height = filter_input(INPUT_POST,'contestant_height');
        $contestant_gender = filter_input(INPUT_POST,'contestant_gender');    
        $club_reg_id = filter_input(INPUT_POST,'club_reg_id');    
        $contestant_id = filter_input(INPUT_POST,'contestant_id');
        
    $val = new Validation();
    if(empty($contestant_height)){
        $contestant_height = 0;
        $val->name('l&auml;ngd')->value($contestant_height)->pattern('int');
    }else{    
    $min = 100;//minimum value of integers
    $max = 225;//maximum value of integers
    $val->name('l&auml;ngd')->value($contestant_height)->valuePattern($min,$max);
    }    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }
		if ($output_form == 'no') {		
                    // Search for start number already set for the competition and if not, set start number for the contestant
                    $colname_rsContestant = filter_input(INPUT_POST,'contestant_id');
        
                    //Catch anything wrong with query
                    try {
                    //Search for start number already set for the contestant
                    require('Connections/DBconnection.php');         
                    $query_rsContestant_Startnumber = "SELECT re.contestant_startnumber FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1 AND contestant_id = :contestant_id";
                    $stmt_rsContestant_Startnumber = $DBconnection->prepare($query_rsContestant_Startnumber);
                    $stmt_rsContestant_Startnumber->execute(array(':contestant_id'=>$colname_rsContestant));
                    $row_rsContestant_Startnumber = $stmt_rsContestant_Startnumber->fetch(PDO::FETCH_ASSOC);
                    }   catch(PDOException $ex) {
                            echo "An Error occured with queryX: ".$ex->getMessage();
                        } 
                        
                    if ($row_rsContestant_Startnumber['contestant_startnumber'] === NULL) {
                        $contestant_startnumber = filter_input(INPUT_POST,'contestant_startnumber'); 
                    } 
                    else {
                        $contestant_startnumber = $row_rsContestant_Startnumber['contestant_startnumber'];
                    }
                    
                    //Catch anything wrong with query
                    try {
                    //INSERT new registration if the button is clicked and the form is validated
                    require('Connections/DBconnection.php');         
                    $insertSQL = "INSERT INTO registration (club_reg_id, contestant_id, contestant_height, contestant_startnumber, class_id) VALUES (:club_reg_id, :contestant_id, :contestant_height, :contestant_startnumber, :class_id)";
                    $stmt = $DBconnection->prepare($insertSQL);
                    $stmt->bindValue(':club_reg_id', $club_reg_id, PDO::PARAM_INT);
                    $stmt->bindValue(':contestant_id', $contestant_id, PDO::PARAM_INT);
                    $stmt->bindValue(':contestant_height', $contestant_height, PDO::PARAM_INT);
                    $stmt->bindValue(':contestant_startnumber', $contestant_startnumber, PDO::PARAM_INT);
                    $stmt->bindValue(':class_id', $colname_rsClass, PDO::PARAM_INT);
                    $stmt->execute();
                    }   catch(PDOException $ex) {
                            echo "An Error occured with queryX: ".$ex->getMessage();
                        }
                    if ($MM_authorizedUsers === "1") { 
                        $insertGoTo = "RegsHandleAll.php#registration_delete";
                    }else{
                        $insertGoTo = "RegInsert_reg.php#registration_delete";
                    }
                    header(sprintf("Location: %s", $insertGoTo));                        
                //Kill statement
                $stmt->closeCursor();
		}
	}	
//Catch anything wrong with query
try {
//Select actual number of registrations for the current competition
require('Connections/DBconnection.php');           
$query_rsCurrRegs = "SELECT COUNT(reg_id) AS max_regs FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1";
$stmt_rsCurrRegs = $DBconnection->query($query_rsCurrRegs);
$row_rsCurrRegs = $stmt_rsCurrRegs->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Select the currently highest start number
require('Connections/DBconnection.php');           
$query_rsMax_startnumber = "SELECT MAX(contestant_startnumber)AS max_startnumber FROM registration INNER JOIN classes AS cl USING (class_id) JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1";
$stmt_rsMax_startnumber = $DBconnection->query($query_rsMax_startnumber);
$row_rsMax_startnumber = $stmt_rsMax_startnumber->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
// Set a new start number (starting with 100)
$max_startnumber = $row_rsMax_startnumber['max_startnumber'];
if ($max_startnumber < 100){
    $contestant_startnumber = 100;
}
else {
    $contestant_startnumber = $max_startnumber + 1;
}
?>    
<h3><a name="registration_insert" id="registration_insert"></a><?php if ($MM_authorizedUsers === "0") { echo '3';} else {echo '4';} ?>. Anm&auml;l till t&auml;vlingklasser</h3>
<p>V&auml;lj bland klubbens t&auml;vlande/lag och anm&auml;l till den eller de t&auml;vlingsklasser som de ska t&auml;vla i 
    (en klass i taget).<strong> F&ouml;r kumite och &aring;ldrarna 7-13 &aring;r: skriv i l&auml;ngduppgift!
    </strong> D&aring; kan vi ta beslut om eventuell uppdelning av klassen i "korta" och "l&aring;nga". 
    G&ouml;r &auml;ndringar eller ta bort t&auml;vlande helt och h&aring;llet genom att klicka p&aring; n&aring;gon av l&auml;nkarna.
<?php //Show if the maximum number of registrations is reached
      if ($row_rsCurrRegs['max_regs'] >= $comp_max_regs) { ?>
        <div class="error">
            <h3>Maximala antalet anm&auml;lningar (<?php echo $comp_max_regs ?> st.)<?php if ($MM_authorizedUsers === "0") {echo ' &auml;r uppn&aring;tt och inga till&auml;gg g&aring;r att g&ouml;ra online! Kontakta t&auml;vlingsledningen vid akuta behov.';}else{ echo ' &auml;r uppn&aring;tt. &Auml;ndra inst&auml;llningar under "T&auml;vlingar" f&ouml;r att andra ska kunna g&ouml;ra till&auml;gg online!';} ?></h3>
        </div>
<?php
      } ?>           
</p>
      <table class="wide_tbl" border="1">
        <tr>
          <th class="w_20">T&auml;vlande</th>
          <th class="w_10">F&ouml;delsedatum</th> 
          <th class="w_10">K&ouml;n</th>
          <th class="w_10">L&auml;ngd (eventuellt)</th>
          <th class="w_30">T&auml;vlingsklass</th>
          <th class="w_10">Anm&auml;l</th>
          <th class="w_5">&Auml;ndra</th>
          <th class="w_5">Ta bort</th>    
        </tr>
        <tr>
          <td colspan="8">
      <table class="medium_tbl" border="0">              
<?php while($row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC)) { 
    //Calculate the contestant's age at the date of the competition
    $calculate_age = new AgeCalc;
    $calculate_age->comp_start_date = $comp_start_date;
    $calculate_age->contestant_birth = $row_rsContestants['contestant_birth'];
    $calculate_age->contestant_birth_max = $row_rsContestants['contestant_birth_max'];
    $calculate_age->contestant_team = $row_rsContestants['contestant_team'];
    $calculate_age->contestant_gender = $row_rsContestants['contestant_gender'];
    $calculate_age->contestant_gender_mix = 'Mix';
    
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
            . "SUBSTRING(cl.class_age, 1, 2) <= :contestantage_min && SUBSTRING(cl.class_age, 4, 2) >= :contestantage_max "
            . "|| comp_current = 1 && cl.class_team = :contestantteam_ && cl.class_gender = :contestant_gender_mix && "
            . "SUBSTRING(cl.class_age, 1, 2) = :contestantagemin && SUBSTRING(cl.class_age, 1, 2) = :contestantagemax "
            . "|| comp_current = 1 && cl.class_team = :contestantteam__ && cl.class_gender = :contestantgender_mix && "
            . "SUBSTRING(cl.class_age, 1, 2) <= :contestantagemin_ && SUBSTRING(cl.class_age, 4, 2) >= :contestantagemax_ "
            . "ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, cl.class_gender_category"; 
    $stmt_rsClassData = $DBconnection->prepare($query_rsClassData);
    $stmt_rsClassData->execute(array(
        ':contestant_team'=>$calculate_age->contestant_team,':contestant_gender'=>$calculate_age->contestant_gender, 
        ':contestant_age_min'=>$calculate_age->calculate_age('contestant_age_min'),':contestant_age_max'=>$calculate_age->calculate_age('contestant_age_max'), 
        ':contestantteam'=>$calculate_age->contestant_team,':contestantgender'=>$calculate_age->contestant_gender, 
        ':contestantage_min'=>$calculate_age->calculate_age('contestant_age_min'),':contestantage_max'=>$calculate_age->calculate_age('contestant_age_max'),
        ':contestantteam_'=>$calculate_age->contestant_team,':contestant_gender_mix'=>$calculate_age->contestant_gender_mix, 
        ':contestantagemin'=>$calculate_age->calculate_age('contestant_age_min'),':contestantagemax'=>$calculate_age->calculate_age('contestant_age_max'),
        ':contestantteam__'=>$calculate_age->contestant_team,':contestantgender_mix'=>$calculate_age->contestant_gender_mix, 
        ':contestantagemin_'=>$calculate_age->calculate_age('contestant_age_min'),':contestantagemax_'=>$calculate_age->calculate_age('contestant_age_max'),));
    $row_rsClassData = $stmt_rsClassData->fetchAll(PDO::FETCH_ASSOC);    
    $totalRows_rsClassData = $stmt_rsClassData->rowCount();   
    } catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    } 
    ?>
          <tr>
            <td><form id="new_registration" name="new_registration" method="POST" action="<?php echo $editFormAction; ?>">
              <table class = "medium_tbl" border="0">
                <tr>
                  <td class="w_20"><label>
                    <input type="text" name="contestant_name" id="contestant_name" value="<?php if($row_rsContestants['contestant_team'] === 1){echo'Lag - ';} echo $row_rsContestants['contestant_name']; ?>" size="20"/>
                  </label></td>
                  <td class="w_10"><label>
                    <input name="contestant_birth" type="text" id="contestant_birth" value="<?php if($row_rsContestants['contestant_team'] === 1){echo'';}else{echo $row_rsContestants['contestant_birth'];} ?>" size="8" maxlength="10"/>
                  </label></td>
                  <td class="w_10"><label>
                    <input name="contestant_gender" type="text" id="contestant_gender" value="<?php echo $row_rsContestants['contestant_gender']; ?>" size="4"/>
                  </label></td>
                  <td class="w_10"><label>
                    <input name="contestant_height" type="text" id="contestant_height" size="1" maxlength="3" />
                      </label></td>
                  <td class="w_30" nowrap="nowrap">
<label><select name="class_id" id="class_id">                               
<?php
    foreach($row_rsClassData as $row_rsClasses) {
?>
      <option value="<?php echo $row_rsClasses['class_id']?>">
    <?php if($row_rsClasses['class_team'] === 1){echo'Lag - ';}echo $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_category'].' | '; 
      if ($row_rsClasses['class_age'] === "") { 
          echo "";          
      } 
      if ($row_rsClasses['class_age'] <> "") { 
          echo $row_rsClasses['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsClasses['class_weight_length'] === "-") {
          echo "";                    
      }
      if ($row_rsClasses['class_weight_length'] <> "-") {
         echo $row_rsClasses['class_weight_length']; 
      } ?>
      </option>
<?php
    //Kill statement
    $stmt_rsClassData->closeCursor();
    $stmt_rsCurrRegs->closeCursor();
    } ?>
</select></label></td>
                  <td class="w_10">
                  <?php if($totalRows_rsClassData == ''){
                            echo 'Inga passande klasser!';} 
                        elseif ($row_rsCurrRegs['max_regs'] >= $comp_max_regs) {
                            echo 'Max antal!';} 
                        else {?>
                            <input type="submit" name="new_registration" class= "button" id="new_registration" value="Anm&auml;l till klass" />
                  <?php }?>               
                  </td>
                  <td class="w_5" nowrap="nowrap" style="text-align:right;">
                    <a href="<?php if ($MM_authorizedUsers === "0") { echo 'ContestantUpdate_reg';} elseif ($MM_authorizedUsers === "1") {echo 'ContestantUpdate';} ?>.php?contestant_id=<?php echo $row_rsContestants['contestant_id']; ?>">&Auml;ndra</a>                    
                  </td>  
                  <td class="w_5" nowrap="nowrap" style="text-align:right;">
                    <a href="<?php if ($MM_authorizedUsers === "0") { echo 'ContestantDelete_reg';} elseif ($MM_authorizedUsers === "1") {echo 'ContestantDelete';} ?>.php?contestant_id=<?php echo $row_rsContestants['contestant_id']; ?>">Ta bort</a>                    
                  </td>
                  </tr>
              </table>
              <input name="contestant_id" type="hidden" id="contestant_id" value="<?php echo $row_rsContestants['contestant_id']; ?>" />
              <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />
              <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
              <input type="hidden" name="MM_insert_registration" value="new_registration" />
              <input name="contestant_startnumber" type="hidden" id="contestant_startnumber" value="<?php echo $contestant_startnumber; ?>" />              
            </form></td>
          </tr>
<?php } ?>
      </table>
          </td>
          </tr>          
      </table>
<?php 
//Catch anything wrong with query
try {
// Select the contestants and their information for the selected class
$colname_rsRegistrations = $_SESSION['MM_Account'];    
require('Connections/DBconnection.php');           
$query_rsRegistrations = "SELECT re.reg_id, re.contestant_height, re.contestant_startnumber, co.contestant_name, "
        . "co.contestant_birth, co.contestant_team, cl.class_id, cl.class_team, cl.class_category, cl.class_discipline, "
        . "cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age "
        . "FROM registration AS re INNER JOIN classes AS cl USING (class_id) "
        . "INNER JOIN contestants AS co USING (contestant_id) "
        . "WHERE account_id = :account_id AND comp_id = :comp_id ORDER BY co.contestant_team, co.contestant_name";
$stmt_rsRegistrations = $DBconnection->prepare($query_rsRegistrations);
$stmt_rsRegistrations ->execute(array(':account_id'=>$colname_rsRegistrations,':comp_id'=>$comp_id));
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
            if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>
    <h3><a name="registration_delete" id="registration_delete"></a><?php if ($MM_authorizedUsers === "0") { echo '4';} else {echo '5';} ?>. Genomförda anm&auml;lningar</h3>
    <p>Om n&aring;got har blivit fel kan du ta bort anm&auml;lan.</p>
      <table class="wide_tbl" border="1">
        <tr>
          <th>Startnr.</th>
          <th>T&auml;vlande</th>
          <th>F&ouml;delsedatum</th>
          <th>L&auml;ngd (eventuellt)</th>
          <th>T&auml;vlingsklass</th>
          <th>Ta bort anm&auml;lan</th>          
        </tr>
        <?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)){ ?>
          <tr>
            <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>              
            <td><?php if($row_rsRegistrations['contestant_team'] === 1){echo'Lag - ';}echo $row_rsRegistrations['contestant_name']; ?></td>
            <td><?php if($row_rsRegistrations['contestant_team'] === 1){echo'';}else{echo $row_rsRegistrations['contestant_birth'];} ?></td>
            <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
      <td><?php if($row_rsRegistrations['contestant_team'] === 1){echo'Lag - ';} echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
      if ($row_rsRegistrations['class_age'] === "") { 
          echo "";          
      } 
      if ($row_rsRegistrations['class_age'] <> "") { 
          echo $row_rsRegistrations['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsRegistrations['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsRegistrations['class_weight_length'] <> "-") {
         echo $row_rsRegistrations['class_weight_length']; 
      }
      ?></td>
            <td nowrap="nowrap">
			<?php if ($passedDate == 0) { ?>
           	<a href="<?php if ($MM_authorizedUsers === "0") { echo 'RegDelete_reg';} else {echo 'RegDelete';} ?>.php?reg_id=<?php echo $row_rsRegistrations['reg_id']; ?>">Ta bort</a>
          	<?php } ?>
            </td></tr>
        <?php }  ?>
      </table>    
<?php       $stmt_rsRegistrations->closeCursor();   
            // Show if rsRegistrations recordset not empty
            }
            $stmt_rsContestants->closeCursor();
            $stmt_rsMax_startnumber->closeCursor();  
        // Show if recordset $totalRows_rsContestants not empty 
        }            
        $stmt_rsClubReg->closeCursor();
        $stmt_rsTeamMembers->closeCursor();
    // Show if rsClubReg recordset not empty
    }
  // Show if last registration date is NOT passed
  }
// If recordset rsClasses is NOT empty 
}
?>    
     </div>
</div>    
<?php 
//Kill statements 
$stmt_rsAccounts->closeCursor();
$stmt_rsClasses->closeCursor();
$stmt_rsSelectedClub->closeCursor();
include("includes/footer.php");
?>
</body>
</html>
<?php ob_end_flush();