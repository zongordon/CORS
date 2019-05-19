<?php 
//Added $confirm_user_password = ''; in declation and initialising of variables 
//Changed to radio buttons for "Kontotyp", "Aktivt konto" and "Bekräftat konto" and simplified code for validation
ob_start();

//Access level admin
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}
//Catch account_id for selected account
$colname_rsAccountId = filter_input(INPUT_GET, 'account_id');

//Catch anything wrong with query
try {
require('Connections/DBconnection.php');            
//Select data regarding all accounts
$query = "SELECT * FROM account WHERE account_id = :account_id";
$stmt_rsAccount = $DBconnection->prepare($query);
$stmt_rsAccount->execute(array(':account_id' => $colname_rsAccountId));
$row_rsAccount = $stmt_rsAccount->fetch(PDO::FETCH_ASSOC);
}   
catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
}

$pagetitle="&Auml;ndra anv&auml;ndarkonto - admin";
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
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">      
    <div class="feature">    
        <div class="error">    
<?php
//Declare and initialise variables
    $user_name='';$user_password='';$confirmed='';$contact_name='';$email='';$contact_phone='';$club_name='';$active='';$access_level='';
// Update account data if button is clicked and all fields are validated to be correct
if (filter_input(INPUT_POST, 'MM_update') == 'AccountForm') {
    $user_name = encodeToUtf8(filter_input(INPUT_POST, trim('user_name')));
    $user_password = encodeToUtf8(filter_input(INPUT_POST, trim('user_password')));
    $confirm_user_password = filter_input(INPUT_POST, trim('confirm_user_password'));
    $confirmed = filter_input(INPUT_POST, 'confirmed');    
    $contact_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST, trim('contact_name')), MB_CASE_TITLE,"UTF-8"));
    $email = filter_input(INPUT_POST, trim('contact_email'));
    $contact_phone = filter_input(INPUT_POST, trim('contact_phone'));
    $club_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST, trim('club_name')), MB_CASE_TITLE,"UTF-8"));
    $active = filter_input(INPUT_POST, 'active');
    $access_level = filter_input(INPUT_POST, 'access_level');
    $account_id = filter_input(INPUT_POST, 'account_id');			
    $output_form = 'no';
	    
    if (empty($email)) {
    // contact_email is blank
      echo '<h3>Du gl&ouml;mde att fylla i e-post!</h3>';
      $output_form = 'yes';
    }  
    //If contact_email is not blank validate the input and check if it's already registered 
    else {
      // Validate contact_email
      if(valid_email($email)){
            $output_form = 'no';
      } 
      else {
        // contact_email is invalid because LocalName is bad  
        echo '<h3>Den ifyllda e-postadressen inneh&aring;ller felaktiga tecken.</h3>';
        $output_form = 'yes';
      }
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');               
    // Validate insert account data    
    $query1 = "SELECT club_name, contact_email FROM account WHERE contact_email = :contact_email AND account_id <> :account_id";
    $stmt_rsContactemail = $DBconnection->prepare($query1);
    $stmt_rsContactemail->execute(array(':contact_email' => $email, ':account_id' => $account_id));
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
    }
 
    if (empty($contact_phone)) {
      // $contact_phone is blank
      echo '<h3>Du gl&ouml;mde att fylla i kontaktpersonens telefonnummer.</h3>';
      $output_form = 'yes';
    }

    if (empty($user_name)) {
      // $user_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i anv&auml;ndarnamn.</h3>';
      $output_form = 'yes';
    }
    //If user_name is not blank validate the input and check if it's already registered    
    else {        
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
        //Kill statement and DB connection
        $stmt_rsUsername->closeCursor();
    }	
    
    if (empty($user_password)) {
      // $user_password is blank
      echo '<h3>Du gl&ouml;mde att fylla i l&ouml;senord.</h3>';
      $output_form = 'yes';
    }
	
    if (empty($confirm_user_password)) {
      // $confirm_user_password is blank
      echo '<h3>Du gl&ouml;mde att bekr&auml;fta l&ouml;senordet.</h3>';
      $output_form = 'yes';
    }
	
    if ($user_password != $confirm_user_password) {
      // $user_password and $confirm_user_password don't match
      echo '<h3>L&ouml;senorden &auml;r inte identiska.</h3>';
      $output_form = 'yes';
    }
    
    if (empty($club_name)) {
      // $club_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i klubbens namn.</h3>';
      $output_form = 'yes';
    }

    if (empty($contact_name)) {
      // $contact_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i kontaktpersonens namn.</h3>';
      $output_form = 'yes';
    }    
} 

  else {
    $output_form = 'yes';
  	}
	
  	if ($output_form == 'yes') {
?>
      </div>    
<h3>&Auml;ndra &ouml;nskade v&auml;rden och klicka p&aring; &quot;Spara&quot; f&ouml;r att spara &auml;ndringen p&aring; kontot.  </h3>
  </div>
  <div class="story">
    <form id="AccountForm" name="AccountForm" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="200" border="0">
        <tr>
          <td valign="baseline" nowrap="nowrap">Klubbens namn</td>
          <td><label>
            <input name="club_name" type="text" id="club_name" value="<?php echo $row_rsAccount['club_name']; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>Kontaktperson</td>
          <td valign="top"><label>
            <input name="contact_name" type="text" id="contact_name" value="<?php echo $row_rsAccount['contact_name']; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>E-post</td>
          <td valign="top"><label>
            <input name="contact_email" type="text" id="contact_email" value="<?php echo $row_rsAccount['contact_email']; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>Telefon</td>
          <td><label>
            <input name="contact_phone" type="text" id="contact_phone" value="<?php echo $row_rsAccount['contact_phone']; ?>" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>Anv&auml;ndarnamn</td>
          <td><input name="user_name" type="text" id="user_name" value="<?php echo $row_rsAccount['user_name']; ?>" size="25" /></td>
        </tr>
        <tr>
          <td>L&ouml;senord</td>
          <td><input name="user_password" type="password" id="user_password" value="<?php echo $row_rsAccount['user_password']; ?>" size="25" /></td>
        </tr>
        <tr>
          <td>L&ouml;senord (bekr&auml;fta)</td>
          <td><input name="confirm_user_password" type="password" id="confirm_user_password" value="<?php echo $row_rsAccount['user_password']; ?>" size="25" /></td>
        </tr>
        <tr>
          <td>Kontotyp</td>
          <td><label>
            <input <?php if (!(strcmp($row_rsAccount['access_level'],1))) {echo "checked=\"checked\"";} ?> type="radio" name="access_level" value="1" id="access_level_0" />
Admin</label><label>      
            <input <?php if (!(strcmp($row_rsAccount['access_level'],0))) {echo "checked=\"checked\"";} ?> type="radio" name="access_level" value="0" id="access_level_1" />
Coach</label></td>
        </tr>
        <tr>
          <td>Aktivt konto</td>
          <td><label>
            <input <?php if (!(strcmp($row_rsAccount['active'],1))) {echo "checked=\"checked\"";} ?> type="radio" name="active" value="1" id="active_0" />
Ja</label><label>      
            <input <?php if (!(strcmp($row_rsAccount['active'],0))) {echo "checked=\"checked\"";} ?> type="radio" name="active" value="0" id="active_1" />
Nej</label></td>                  
        </tr>
        <tr>
          <td>Bekr&auml;ftat konto</td>
          <td><label>
            <input <?php if (!(strcmp($row_rsAccount['confirmed'],1))) {echo "checked=\"checked\"";} ?> type="radio" name="confirmed" value="1" id="confirmed_0" />
Ja</label><label>      
            <input <?php if (!(strcmp($row_rsAccount['confirmed'],0))) {echo "checked=\"checked\"";} ?> type="radio" name="confirmed" value="0" id="confirmed_1" />
Nej</label></td>                                    
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap"><input type="hidden" name="MM_update" value="AccountForm" /><input name="account_id" type="hidden" id="account_id" value="<?php echo $row_rsAccount['account_id']; ?>" /></td>
          <td><input name="AccountUpdate" type="submit" id="AccountUpdate" value="Spara" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
</div>
</div>
<?php
} 
	//Save the updated account information if the Update button is clicked and form validated correct 
  	else if ($output_form == 'no') {
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
            $stmt_rsAccount->bindValue(':contact_email', $email, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':contact_phone', $contact_phone, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':club_name', $club_name, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':active', $active, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':access_level', $access_level, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':account_id', $account_id, PDO::PARAM_INT);
            $stmt_rsAccount->execute();
            }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }

            $updateGoTo = "AccountsList.php";
            if (isset($_SERVER['QUERY_STRING'])) {
                $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
                $updateGoTo .= $_SERVER['QUERY_STRING'];
            }
            header(sprintf("Location: %s", $updateGoTo));
        }
//Kill statement
$stmt_rsAccount->closeCursor();        
include("includes/footer.php");
?>
</body>
</html>
<?php ob_end_flush();?>









