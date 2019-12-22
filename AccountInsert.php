<?php
//Added validation class with multiple validation features and removed most of existing validation code

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

$pagetitle="L&auml;gga till ett konto - admin";
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
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">  
   <div class="feature">    
       <div class="error">    
<?php
//Declare and initialise variables
  $user_name='';$user_password='';$confirm_user_password = '';$confirmed='';$contact_name='';$email='';$contact_phone='';$club_name='';$active='';$access_level='';
// Validate insert account data if button is clicked
 if (filter_input(INPUT_POST,'MM_insert') === 'new_account') {
    $club_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'club_name'), MB_CASE_TITLE,"UTF-8"));
    $contact_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contact_name'), MB_CASE_TITLE,"UTF-8"));
    $email = filter_input(INPUT_POST,'contact_email');
    $contact_phone = filter_input(INPUT_POST,'contact_phone');
    $user_name = encodeToUtf8(filter_input(INPUT_POST,'user_name'));
    $user_password = encodeToUtf8(filter_input(INPUT_POST,'user_password'));
    $confirm_user_password = filter_input(INPUT_POST,'confirm_user_password');  
    $access_level = filter_input(INPUT_POST,'access_level');
    $confirmed = filter_input(INPUT_POST,'confirmed');       
    $active = filter_input(INPUT_POST,'active');    
    
    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('klubbens namn')->value($club_name)->pattern('text')->required()->min($length);
    $val->name('kontaktperson')->value($contact_name)->pattern('text')->required()->min($length);
    $val->name('e-post')->value($email)->pattern('email')->required();
    $val->name('telefon')->value($contact_phone)->pattern('tel')->required();
    $val->name('anv&auml;ndarnamn')->value($user_name)->pattern('text')->required()->min($length);
    $val->name('l&ouml;senord')->value($user_password)->pattern('text')->required()->min($length);
    $val->name('bekr&auml;ftande l&ouml;senord')->value($confirm_user_password)->pattern('text')->required()->min($length)->equal($user_password);   
    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }
    
    //Validate that email isn't already used
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');               
    // Validate insert account data    
    $colname_rsContactemail = $email;     
    $query1 = "SELECT club_name, contact_email FROM account WHERE contact_email = :contact_email";
    $stmt_rsContactemail = $DBconnection->prepare($query1);
    $stmt_rsContactemail->execute(array(':contact_email' => $colname_rsContactemail));
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
    
    //Check if the user name already is registered            
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');                   
    //Validate account insert data against current accounts    
    $colname_rsUsername = $user_name;
    $query2 = "SELECT club_name, user_name FROM account WHERE user_name = :user_name";
    $stmt_rsUsername = $DBconnection->prepare($query2);
    $stmt_rsUsername->execute(array(':user_name' => $colname_rsUsername));
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
 else {
   $output_form = 'yes';
 } 
 
if ($output_form == 'yes') { ?>
       </div>  
<h3>Skapa ett nytt konto f&ouml;r att kunna registera t&auml;vlande</h3>
    <p>Fyll i formul&auml;ret och klicka p&aring; knappen &quot;Nytt konto&quot;. Obs! Alla f&auml;lt &auml;r obligatoriska att fylla i och minst fem tecken i textf&auml;lten!</p>

    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" id="new_account" name="new_account">      
      <table width="400" border="0">
        <tr>
          <td>Klubbens namn</td>
          <td><label>
            <input name="club_name" type="text" id="club_name" size="25" value="<?php echo $club_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>Kontaktperson</td>
          <td valign="top"><label>
            <input name="contact_name" type="text" id="contact_name" size="25" value="<?php echo $contact_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>E-post</td>
          <td valign="top"><label>
            <input name="contact_email" type="text" id="contact_email" size="25" value="<?php echo $email; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>Telefon</td>
          <td><label>
            <input name="contact_phone" type="text" id="contact_phone" size="25" value="<?php echo $contact_phone; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>Anv&auml;ndarnamn</td>
          <td><input name="user_name" type="text" id="user_name" size="25" value="<?php echo $user_name; ?>"/></td>
        </tr>
        <tr>
          <td>L&ouml;senord</td>
          <td><input name="user_password" type="password" id="user_password" size="25" /></td>
        </tr>
        <tr>
          <td>L&ouml;senord (bekr&auml;fta)</td>
          <td><input name="confirm_user_password" type="password" id="confirm_user_password" size="25" /></td>
        </tr>
        <tr>
           <td>Kontotyp</td>
          <td><label>
            <input <?php if (!(strcmp($access_level,1))) {echo "checked=\"checked\"";} ?> type="radio" name="access_level" value="1" id="access_level_1" />
Admin</label><label>      
    <input <?php if (!(strcmp($access_level,0))) {echo "checked=\"checked\"";} ?> type="radio" name="access_level" value="0" id="access_level_0" checked=""/>
Coach</label></td>
        </tr>
        <tr>
          <td>Aktivt konto</td>
          <td><label>
                  <input <?php if (!(strcmp($active,1))) {echo "checked=\"checked\"";} ?> type="radio" name="active" value="1" id="active_1" checked=""/>
Ja</label><label>      
            <input <?php if (!(strcmp($active,0))) {echo "checked=\"checked\"";} ?> type="radio" name="active" value="0" id="active_0" />
Nej</label></td>                  
        </tr>
        <tr>
          <td>Bekr&auml;ftat konto</td>
          <td><label>
                  <input <?php if (!(strcmp($confirmed,1))) {echo "checked=\"checked\"";} ?> type="radio" name="confirmed" value="1" id="confirmed_1" checked=""/>
Ja</label><label>      
            <input <?php if (!(strcmp($confirmed,0))) {echo "checked=\"checked\"";} ?> type="radio" name="confirmed" value="0" id="confirmed_0" />
Nej</label></td>                                    
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><label>
            <input type="submit" name="new_account" id="new_account" value="Nytt konto" />
          </label></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="new_account" />
      </form>
<?php
} 
	//Send the account information to the users email address and save it
  	else if ($output_form == 'no') {
        $headers = "From: $comp_name <$comp_email>\r\n" .
        "MIME-Version: 1.0\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" .        
        "Content-Type: text/plain; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n";         
        $subject = 'Ditt nya konto: '.$comp_name;
        $text = "Tack för att du ville registrera ett konto på $comp_url!\n" .
        "Här är de inloggningsuppgifter som vi registrerade åt dig:\n" .
	"Klubbnamn: $club_name\n" .
        "Kontaktperson: $contact_name\n" .
        "E-post: $email\n" .
        "Telefon: $contact_phone\n" .
	"Användarnamn: $user_name\n" .
	"Lösenord: $user_password\n" .
	"Använd ovanstående till att logga in och anmäla tävlande till $comp_name.\n" .
	"\n" .
	"Med vänliga hälsningar,\n" .
	"$comp_arranger, $comp_name, $comp_email";
        $msg = "Hej $contact_name,\n$text";
        
        // Send email to club contact
        mail($email, $subject, $msg, $headers);                
    
        echo '<br />' . $contact_name . ',<br />Tack f&ouml;r att du har skaffat ett konto p&aring; '.$comp_name.'!<br />Dina uppgifter skickades till: '. $email .'. Logga in och g&ouml;r dina anm&auml;lningar.';
       //Catch anything wrong with query
            try {
            require('Connections/DBconnection.php'); 
            //INSERT account data in DB
            $query = "INSERT INTO 
                account (user_name, user_password, confirmed, contact_name, contact_email, contact_phone, club_name, active, access_level)
                VALUES (:user_name, :user_password, :confirmed, :contact_name, :contact_email, :contact_phone, :club_name, :active, :access_level)"; 
            $stmt_rsAccount = $DBconnection->prepare($query);                                  
            $stmt_rsAccount->bindValue(':user_name', $user_name, PDO::PARAM_STR);       
            $stmt_rsAccount->bindValue(':user_password', $user_password, PDO::PARAM_STR);    
            $stmt_rsAccount->bindValue(':confirmed', $confirmed, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':contact_name', $contact_name, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':contact_email', $email, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':contact_phone', $contact_phone, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':club_name', $club_name, PDO::PARAM_STR);
            $stmt_rsAccount->bindValue(':active', $active, PDO::PARAM_INT);
            $stmt_rsAccount->bindValue(':access_level', $access_level, PDO::PARAM_INT);
            $stmt_rsAccount->execute();
            }   
            catch(PDOException $ex) {
                echo "An Error occured: ".$ex->getMessage();
            }
	} ?>  
   </div>
</div>    
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>


























