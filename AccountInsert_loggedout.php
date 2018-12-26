<?php
//Added information about and link to GDPR policy

if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

$pagetitle="L&auml;gga till eget konto";
//Includes Several code functions
include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");     
?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
   <div class="feature">    
      <div class="error">
<?php
//Declare and initialise variables
  $user_name='';$user_password='';$confirmed='';$contact_name='';$email='';$contact_phone='';$club_name='';$active='';$access_level='';$confirm_user_password='';
// Validate insert account data if button is clicked
 if (filter_input(INPUT_POST,'MM_insert') == 'new_account') {
    if (filter_input(INPUT_POST,'user_name')) { $user_name = encodeToUtf8(filter_input(INPUT_POST,'user_name'));}
    if (filter_input(INPUT_POST,'user_password')) { $user_password = encodeToUtf8(filter_input(INPUT_POST,'user_password'));}
    if (filter_input(INPUT_POST,'confirm_user_password')) { $confirm_user_password = filter_input(INPUT_POST,'confirm_user_password');}
    if (filter_input(INPUT_POST,'confirmed')) { $confirmed = filter_input(INPUT_POST,'confirmed');}     
    if (filter_input(INPUT_POST,'contact_name')) { $contact_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contact_name'), MB_CASE_TITLE,"UTF-8"));}
    if (filter_input(INPUT_POST,'contact_email')) { $email = filter_input(INPUT_POST,'contact_email');}
    if (filter_input(INPUT_POST,'contact_phone')) { $contact_phone = filter_input(INPUT_POST,'contact_phone');}
    if (filter_input(INPUT_POST,'club_name')) { $club_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'club_name'), MB_CASE_TITLE,"UTF-8"));}
    if (filter_input(INPUT_POST,'active')) { $active = filter_input(INPUT_POST,'active');}
    if (filter_input(INPUT_POST,'access_level')) { $access_level = filter_input(INPUT_POST,'access_level');}    
      $captcha=filter_input(INPUT_POST,'captcha');
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
        echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3>';
        $output_form = 'yes';
      }        
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
    }
  
    if (empty($contact_phone)) {
      // $contact_phone is blank
      echo '<h3>Du gl&ouml;mde att fylla i kontaktpersonens telefonnummer!</h3>';
      $output_form = 'yes';
    }

    if (empty($user_name)) {
      // $user_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i anv&auml;ndarnamn!</h3>';
      $output_form = 'yes';
    }
    //If user_name is not blank validate the input and check if it's already registered    
    else {        
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
        //Kill statement
        $stmt_rsUsername->closeCursor();
    }	
	
    if (empty($user_password)) {
      // $user_password is blank
      echo '<h3>Du gl&ouml;mde att fylla i l&ouml;senord!</h3>';
    $output_form = 'yes';
    }
	
    if (empty($confirm_user_password)) {
      // $confirm_user_password is blank
      echo '<h3>Du gl&ouml;mde att bekr&auml;fta l&ouml;senordet!</h3>';
      $output_form = 'yes';
    }
	
    if ($user_password != $confirm_user_password) {
      // $user_password and $confirm_user_password don't match
      echo '<h3>L&ouml;senorden var inte identiska!</h3>';
      $output_form = 'yes';
    }	
    
    if (empty($club_name)) {
      // $club_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i klubbens namn!</h3>';
      $output_form = 'yes';
    }

    if (empty($contact_name)) {
      // $contact_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i kontaktpersonens namn!</h3>';
      $output_form = 'yes';
    }
    
    if ($captcha <> $_SESSION['captcha']){
      echo '<h3>Du skrev inte in samma teckan som i bilden. Försök igen!</h3>';
      $output_form = 'yes';
    }    
 } 
 else {
    $output_form = 'yes';
 }

if ($output_form === 'yes') {
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');                   
    // Select information regarding all active accounts    
    $query3 = "SELECT club_name, contact_name, contact_email FROM account WHERE active = 1 ORDER BY club_name ASC";
    $stmt_rsAccounts = $DBconnection->query($query3);
    $totalRows_rsAccounts = $stmt_rsAccounts->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    } ?>
      </div>       
<h3>Skapa ett nytt konto f&ouml;r att kunna registera t&auml;vlande</h3>
<p><strong>Obs!</strong> Titta f&ouml;rst i listan l&auml;ngst ner s&aring; att ni inte redan har ett konto, innan du skapar ett nytt!<br/><strong>T&auml;vlande &auml;r redan kopplade till dessa konton, vilket g&ouml;r det l&auml;ttare f&ouml;r dig att anm&auml;la!</strong><br/>Kontakta oss ifall ni inte l&auml;ngre har tillg&aring;ng till mejladressen i listan. 
    <br>Fyll i formul&auml;ret och klicka p&aring; knappen &quot;Nytt konto&quot;. Obs! Alla f&auml;lt &auml;r obligatoriska att fylla i!</br></p>

    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" id="new_account" name="new_account">      
      <table width="450" border="0">
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
          <td>Skriv in samma tecken som i bilden!</td>
          <td><input name="captcha" type="text" id="captcha" size="25" /></td>
        </tr>
        <tr>
          <td><img src="Captcha.php" /><input name="active" type="hidden" id="active" value="1" />
            <input name="access_level" type="hidden" id="access_level" value="0" />
            <input name="confirmed" type="hidden" id="confirmed" value="0" /></td>
          <td><label>
            <input type="submit" name="new_account" id="new_account" value="Nytt konto" />
          </label></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="new_account" />
    </form>
På <?php echo $comp_url ?> använder vi cookies för att webbplatsen ska fungera på ett bra sätt för dig. 
Genom att använda siten samtycker du till vårt användande av cookies och vår behandling av personuppgifter.
Läs mer om hur vi arbetar med <a href="http://karateklubben.com/GDPR.html" target="_blank">dataintegritet</a>.
<!-- Show current accounts -->
<h3>Registrerade konton</h3>
<?php if ($totalRows_rsAccounts > 0) { ?>    
      <p>Detta &auml;r konton som redan finns registrerade.</p>
  <table width="100%" border="1" cellpadding="2">
    <tr>
      <td nowrap="nowrap"><strong>Klubbnamn</strong></td>
      <td nowrap="nowrap"><strong>Kontaktnamn</strong></td>
      <td nowrap="nowrap"><strong>E-post</strong></td>      
    </tr>
<?php   while($row_rsAccounts = $stmt_rsAccounts->fetch(PDO::FETCH_ASSOC)) { ;?>
      <tr>
        <td><?php echo $row_rsAccounts['club_name']; ?></td>
        <td><?php echo $row_rsAccounts['contact_name']; ?></td>
        <td><?php echo $row_rsAccounts['contact_email']; ?></td>
      </tr>
<?php   } ?>
  </table>
<?php
      } 
      else {
          echo 'Inga konton tillg&auml;ngliga &auml;n!';
      }
//Kill statement
$stmt_rsAccounts->closeCursor();
} 
        
	//Send the account information to the users email address and save it
  	else if ($output_form === 'no') {
        //Email to Competition Admin    
        $headers = "From: $comp_name <$comp_email>\r\n" .
        "MIME-Version: 1.0\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" .        
        "Content-Type: text/plain; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n";         
        $adm_email = "$comp_email";
        $subject_adm = 'Nytt konto registrerat: '.$comp_name;
	$text_adm = "Detta konto har registrerats på $comp_url:\n" .
	"Klubbnamn: $club_name\n" .
        "Kontaktperson: $contact_name\n" .
        "E-post: $email\n" .
        "Telefon: $contact_phone\n" .
	"Användarnamn: $user_name\n" .
	"\n";
        $msg_adm = "Nytt konto registrerat!\n$text_adm";

        // Send email to Competition Admin
        mail($adm_email, $subject_adm, $msg_adm, $headers);                
        
        //Email to Club Contact
        $headers = "From: $comp_name <$comp_email>\r\n" .
        "MIME-Version: 1.0\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" .        
        "Content-Type: text/plain; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n";         
        $subject = 'Ditt nya konto: '.$comp_name;
        $text = "Tack för att du registrerat ett konto på $comp_url!\n" .
        "Här är de inloggningsuppgifter som du registrerade:\n" .
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
   
 	echo '<br />' . $contact_name . ',<br />Tack f&ouml;r att du har skaffat ett konto p&aring; '.$comp_url.'!<br />Dina uppgifter skickades till: '. $email .'<br />Logga in och g&ouml;r dina anm&auml;lningar!</div>';
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
            } //Kill statement
            $stmt_rsAccount->closeCursor();
	} ?>
   </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>