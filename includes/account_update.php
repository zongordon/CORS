<?php
//Added class for table layout in css file
try {
require('Connections/DBconnection.php');            
//Select all data regarding the account
$query = "SELECT * FROM account WHERE account_id = :account_id";
$stmt_rsAccount = $DBconnection->prepare($query);
$stmt_rsAccount->execute(array(':account_id' => $colname_rsAccountId));
$row_rsAccount = $stmt_rsAccount->fetch(PDO::FETCH_ASSOC);
$totalRows_rsAccount = $stmt_rsAccount->rowCount();   
}   
catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
}
//Prevent Warning: Trying to access array offset on value of type null
if ($totalRows_rsAccount <> 0){;
    $user_name = $row_rsAccount['user_name'];
    $user_password = $row_rsAccount['user_password'];
    $confirmed = $row_rsAccount['confirmed'];    
    $contact_name = $row_rsAccount['contact_name'];
    $contact_email =  $row_rsAccount['contact_email'];
    $contact_phone = $row_rsAccount['contact_phone'];
    $club_name = $row_rsAccount['club_name'];
    $active = $row_rsAccount['active'];
    $access_level = $row_rsAccount['access_level'];
    $account_id = $row_rsAccount['account_id'];			
}
// Update account data if button is clicked and all fields are validated to be correct
if (filter_input(INPUT_POST, 'MM_update') == 'AccountForm') {
    $user_name = encodeToUtf8(filter_input(INPUT_POST, trim('user_name')));
    $user_password = encodeToUtf8(filter_input(INPUT_POST, trim('user_password')));
    $confirm_user_password = filter_input(INPUT_POST, trim('confirm_user_password'));
    $confirmed = filter_input(INPUT_POST, 'confirmed');    
    $contact_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST, trim('contact_name')), MB_CASE_TITLE,"UTF-8"));
    $contact_email = filter_input(INPUT_POST, trim('contact_email'));
    $contact_phone = filter_input(INPUT_POST, trim('contact_phone'));
    $club_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST, trim('club_name')), MB_CASE_TITLE,"UTF-8"));
    $active = filter_input(INPUT_POST, 'active');
    $access_level = filter_input(INPUT_POST, 'access_level');
    $account_id = filter_input(INPUT_POST, 'account_id');			
    
    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('klubbens namn')->value($club_name)->pattern('alphanum')->required()->min($length);
    $val->name('kontaktperson')->value($contact_name)->pattern('words')->required()->min($length);
    $val->name('e-post')->value($contact_email)->emailPattern()->required();
    $val->name('telefon')->value($contact_phone)->pattern('tel')->required();
    $val->name('anv&auml;ndarnamn')->value($user_name)->pattern('text')->required()->min($length);
    $val->name('l&ouml;senord')->value($user_password)->pattern('text')->required()->min($length);
    $val->name('bekr&auml;ftande l&ouml;senord')->value($confirm_user_password)->pattern('text')->required()->min($length)->equal($user_password);   
    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<div class="error"><h3>'.$error.'</h3></br></div>';
        }
        $output_form = 'yes';
    }
    //Validate that email isn't already used
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');               
    // Validate insert account data    
    $query1 = "SELECT club_name, contact_email FROM account WHERE contact_email = :contact_email AND account_id <> :account_id";
    $stmt_rsContactemail = $DBconnection->prepare($query1);
    $stmt_rsContactemail->execute(array(':contact_email' => $contact_email, ':account_id' => $account_id));
    $row_rsContactemail = $stmt_rsContactemail->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsContactemail = $stmt_rsContactemail->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }   
	if ($totalRows_rsContactemail > 0) {
        // $contact_email is already in use
        echo '<h3>E-postadressen &auml;r upptagen av '.$row_rsContactemail['club_name'].'!</h3>';
        $output_form = 'yes';		
	}
         //Kill statement
        $stmt_rsContactemail->closeCursor();
 
    //Check if it's already registered    
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');                   
    //Validate account insert data against current accounts    
    $query2 = "SELECT club_name, user_name FROM account WHERE user_name = :user_name AND account_id <> :account_id";
    $stmt_rsUsername = $DBconnection->prepare($query2);
    $stmt_rsUsername->execute(array(':user_name' => $user_name, ':account_id' => $account_id));
    $row_rsUsername = $stmt_rsUsername->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsUsername = $stmt_rsUsername->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }       
	if ($totalRows_rsUsername > 0) {
        // $user_name is already in use
            echo '<h3>Anv&auml;ndarnamnet &auml;r upptaget!</h3>';
            $output_form = 'yes';		
	}
        //Kill statement
        $stmt_rsUsername->closeCursor();
}	
  else {
    $output_form = 'yes';
  }
// Show form if the button Update isn't clicked	
if ($output_form === 'yes') { ;?>
        <p><h3>&Auml;ndra &ouml;nskade v&auml;rden och klicka p&aring; &quot;Spara&quot; f&ouml;r att spara &auml;ndringen p&aring; kontot.</h3></p> 
    <form id="AccountForm" name="AccountForm" method="POST" action="<?php echo $editFormAction; ?>">
      <table class="narrow_tbl" border="0">
        <tr>
          <td valign="baseline" nowrap="nowrap">Klubbens namn</td>
          <td><label>
            <input name="club_name" type="text" id="club_name" value="<?php echo $club_name; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>Kontaktperson</td>
          <td valign="top"><label>
            <input name="contact_name" type="text" id="contact_name" value="<?php echo $contact_name; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>E-post</td>
          <td valign="top"><label>
            <input name="contact_email" type="text" id="contact_email" value="<?php echo $contact_email; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>Telefon</td>
          <td><label>
            <input name="contact_phone" type="text" id="contact_phone" value="<?php echo $contact_phone; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>Anv&auml;ndarnamn</td>
          <td><input name="user_name" type="text" id="user_name" value="<?php echo $user_name; ?>" size="25" /></td>
        </tr>
        <tr>
          <td>L&ouml;senord</td>
          <td><input name="user_password" type="password" id="user_password" value="<?php echo $user_password; ?>" size="25" /></td>
        </tr>
        <tr>
          <td>L&ouml;senord (bekr&auml;fta)</td>
          <td><input name="confirm_user_password" type="password" id="confirm_user_password" value="<?php echo $user_password; ?>" size="25" /></td>
        </tr>
<?php if($MM_authorizedUsers === "0"){//If not admin->adapt form?>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap">
          <input type="hidden" name="MM_update" value="AccountForm" />            
          <input type="hidden" name="confirmed" value="0" />            
          <input type="hidden" name="active" value="1" />            
          <input type="hidden" name="access_level" value="0" />                    
          </td>
        </tr>
<?php }//If Admin->adapt form
         else {?>        
        <tr>
          <td>Kontotyp</td>
          <td><label>
            <input <?php if (!(strcmp($access_level,1))) {echo "checked=\"checked\"";} ?> type="radio" name="access_level" value="1" id="access_level_0" />
Admin</label><label>      
            <input <?php if (!(strcmp($access_level,0))) {echo "checked=\"checked\"";} ?> type="radio" name="access_level" value="0" id="access_level_1" />
Coach</label></td>
        </tr>
        <tr>
          <td>Aktivt konto</td>
          <td><label>
            <input <?php if (!(strcmp($active,1))) {echo "checked=\"checked\"";} ?> type="radio" name="active" value="1" id="active_0" />
Ja</label><label>      
            <input <?php if (!(strcmp($active,0))) {echo "checked=\"checked\"";} ?> type="radio" name="active" value="0" id="active_1" />
Nej</label></td>                  
        </tr>
        <tr>
          <td>Bekr&auml;ftat konto</td>
          <td><label>
            <input <?php if (!(strcmp($confirmed,1))) {echo "checked=\"checked\"";} ?> type="radio" name="confirmed" value="1" id="confirmed_0" />
Ja</label><label>      
            <input <?php if (!(strcmp($confirmed,0))) {echo "checked=\"checked\"";} ?> type="radio" name="confirmed" value="0" id="confirmed_1" />
Nej</label></td>                                    
        </tr>
<?php }//If Admin->adapt form ?>        
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap"><input type="hidden" name="MM_update" value="AccountForm" /><input name="account_id" type="hidden" id="account_id" value="<?php echo $account_id; ?>" /></td>
          <td><input name="AccountUpdate" type="submit" class= "button" id="AccountUpdate" value="Spara" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
    </div>
</div>      
<?php }//If output form
	//Save the updated account information if the Update button is clicked and form validated correct 
  	else if ($output_form === 'no') { //Don't show form
            //Catch anything wrong with query
            try {
            require('Connections/DBconnection.php');           
            //UPDATE account according to changes
            $updateSQL = "UPDATE account 
            SET user_name=:user_name, 
                user_password = :user_password, 
                confirmed = :confirmed, 
                contact_name = :contact_name, 
                contact_email = :contact_email, 
                contact_phone = :contact_phone, 
                club_name = :club_name, 
                active = :active, 
                access_level = :access_level 
                WHERE account_id = :account_id"; 
            $stmt_rsAccount = $DBconnection->prepare($updateSQL);                                  
            $stmt_rsAccount->bindValue(':user_name', $user_name, PDO::PARAM_STR);       
            $stmt_rsAccount->bindValue(':user_password', $user_password, PDO::PARAM_STR);    
            $stmt_rsAccount->bindValue(':confirmed', $confirmed, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':contact_name', $contact_name, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':contact_email', $contact_email, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':contact_phone', $contact_phone, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':club_name', $club_name, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':active', $active, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':access_level', $access_level, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':account_id', $account_id, PDO::PARAM_INT);
            $stmt_rsAccount->execute();
            }   
            //Catch eny error
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }
            if ($MM_authorizedUsers === "1") { 
                $updateGoTo = "AccountsList.php";
            }else{
                $updateGoTo = "AccountList_reg.php";
            }        
            header(sprintf("Location: %s", $updateGoTo));    
        }
//Kill statement
$stmt_rsAccount->closeCursor();        
include("includes/footer.php");
?>
</body>
</html>
<?php ob_end_flush();