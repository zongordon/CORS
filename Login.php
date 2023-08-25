<?php 
//Removed one redundant session_start();

 ob_start();

if (!isset($_SESSION)) {
  session_start();
}

$pagetitle="Logga in";
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
// *** Validate request to login to this site.
$loginFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
$_SESSION['PrevUrl'] = filter_input(INPUT_SERVER,'accesscheck');

if (filter_input(INPUT_POST,'user_name') && filter_input(INPUT_POST,'user_password')) {
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "LogedIn.php";
  $MM_redirecttoReferrer = true;
  $loginUsername=filter_input(INPUT_POST,trim('user_name'));
  $password=filter_input(INPUT_POST,trim('user_password'));
  $tryLogin = "yes";
  
    // Verify reCAPTCHA
    $recaptchaSecretKey = 'SecretKey';
    $recaptchaResponse = filter_input(INPUT_POST, 'g-recaptcha-response');  
    $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $recaptchaData = array(
        'secret' => $recaptchaSecretKey,
        'response' => $recaptchaResponse,
    );
    $recaptchaOptions = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($recaptchaData),
        ),
    );
    $recaptchaContext = stream_context_create($recaptchaOptions);
    $recaptchaResult = file_get_contents($recaptchaVerifyUrl, false, $recaptchaContext);

      if ($recaptchaResult === false) {
        echo "Failed to verify reCAPTCHA.";
      } else 
            $recaptchaResult = json_decode($recaptchaResult, true);

      if (!$recaptchaResult['success']) {
        echo '<h3>Vänligen bekräfta att du inte är en robot!</h3>';
        $tryLogin = "no";
      }
      if (empty($loginUsername)) {
      // $loginUsername is blank
        echo '<h3>Du gl&ouml;mde att fylla i anv&auml;ndarnamn!</h3>';
        $tryLogin = "no";
      }
      if (empty($password)) {
      // $password is blank
        echo '<h3>Du gl&ouml;mde att fylla i l&ouml;senord!</h3>';
        $tryLogin = "no";
      }       
  if ($tryLogin == "yes") {	    
    //Catch anything wrong with query 
    try {
    require('Connections/DBconnection.php');        
    //Search if entered login username exists or not 
    $sql1 = "SELECT user_name FROM account WHERE user_name = :user_name";
    $stmt_rsUserexists = $DBconnection->prepare($sql1);
    $stmt_rsUserexists->execute(array(':user_name' => $loginUsername));
//    $row_rsUserexists = $stmt_rsUserexists->fetch(PDO::FETCH_ASSOC);
    $totalRows_rsUserexists = $stmt_rsUserexists->rowCount();
    }   
    catch(PDOException $ex) {
    echo "An Error occured: ".$ex->getMessage();
    }   
     if ($totalRows_rsUserexists == 0) { // Show if recordset empty 
     echo '<h3>Anv&auml;ndarnamnet: "'.$loginUsername.'" finns inte! F&ouml;rs&ouml;k igen!</h3>';    
     $tryLogin = "no";
     }
     
     if ($tryLogin == "yes") {	      
        //Catch anything wrong with query
        try {
        // Search if entered login credentials are valid and thereby login successful
        $sql2 = "SELECT account_id, user_name, user_password, access_level FROM account WHERE user_name = :login AND user_password = :password";
        $stmt_LoginRS = $DBconnection->prepare($sql2);
        $stmt_LoginRS->execute(array(':login' => $loginUsername, ':password' => $password));        
        $row_LoginRS = $stmt_LoginRS->fetch(PDO::FETCH_ASSOC); 
        $totalRows_LoginRS = $stmt_LoginRS->rowCount();
        }   
        catch(PDOException $ex) {
        echo "An Error occured!: ".$ex->getMessage();
        }   
        //Redirect to Logedin.php if login is successful
        if ($totalRows_LoginRS == 1) {
            $loginStrGroup  = $row_LoginRS['access_level'];
            //declare three session variables and assign them
            $_SESSION['MM_UserId'] = $loginUsername;
            $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
            $_SESSION['MM_AccountId'] = $row_LoginRS['account_id'];

            if (isset($_SESSION['PrevUrl']) && true) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
            }
        header("Location: " . $MM_redirectLoginSuccess );
        echo '<br/>Sessions: '.$_SESSION['MM_UserId'].', '.$_SESSION['MM_UserGroup'].', '.$_SESSION['MM_AccountId'];
        }
        else { 
        echo '<h3>Kombinationen av anv&auml;ndarnamn och l&ouml;senord var fel! F&ouml;rs&ouml;k igen!</h3>';	
        }
     $stmt_LoginRS->closeCursor();        
     }                  
   $stmt_rsUserexists->closeCursor();
   }
}
?>
        </div>
      <h3>Du &auml;r inte inloggad med tillg&aring;ng till alla sidor!</br>
Logga in till ditt klubbkonto f&ouml;r att anm&auml;la er eller &auml;ndra er anm&auml;lan!</h3>
<form id="LoginForm" name="LoginForm" method="POST" action="<?php echo $loginFormAction; ?>">
        <table width="200" border="0">
          <tr>
            <td><h1>Anv&auml;ndarnamn</h1></td>
            <td><input name="user_name" type="text" id="user_name" size="25" /></td>
          </tr>
          <tr>
            <td><h1>L&ouml;senord</h1></td>
            <td><input name="user_password" type="password" id="user_password" size="25" /></td>
          </tr>
          <tr>
            <td></td>
            <td><div class="g-recaptcha" data-theme="dark" data-sitekey="sitekey"></div></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" name="LoginButton" class = "button" id="LoginButton" value="Logga in" /></td>
          </tr>
        </table>
    </form>
På <?php echo $comp_url ?> använder vi cookies för att webbplatsen ska fungera på ett bra sätt för dig. 
Genom att använda siten samtycker du till vårt användande av cookies och vår behandling av personuppgifter.
Läs mer om hur vi arbetar med <a href="http://karateklubben.com/GDPR.html" target="_blank">dataintegritet</a>.         
<p><a href="ForgottenPassword.php">Gl&ouml;mt l&ouml;senordet eller anv&auml;ndarnamnet?</a></p>
      <p>Har du inget anv&auml;ndarkonto &auml;n? <a href="AccountInsert.php">Skapa ett h&auml;r!</a></p>
    </div>
  <div class="story"></div>
</div>
<?php
include_once("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();