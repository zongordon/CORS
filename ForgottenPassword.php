<?php
//Removed kill DB as it's included in footer.php
ob_start();

$pagetitle="Gl&ouml;mt ditt l&ouml;senord eller anv&auml;ndarnamnet?";
// Includes Several code functions
include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?>  
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content"> 
  <div class="feature">      
<?php
 //Validate the form if button is clicked
 if (filter_input(INPUT_POST,'MM_select') == 'select_account') {
    $contact_email = filter_input(INPUT_POST,'contact_email');
    $output_form = 'no'; ?> 
<div class="error">       
<?php
  if (empty($contact_email)) {
      // $contact_email is blank
      echo '<h3>Du gl&ouml;mde att fylla i e-post!</h3>';
      $output_form = 'yes';
  }
  //If contact_email is not blank validate the input and check if it's already registered 
  else {
     // Validate contact_email
      if(valid_email($contact_email)){
            $output_form = 'no';
            //Catch anything wrong with query
            try {   
            //SELECT contactt email from account
            require('Connections/DBconnection.php');         
            $query = "SELECT contact_email FROM account WHERE contact_email =:contact_email";
            $stmt_rsContactemail = $DBconnection->prepare($query);
            $stmt_rsContactemail->execute(array(':contact_email'=>$contact_email));
            $row_rsContactemail = $stmt_rsContactemail->fetch(PDO::FETCH_ASSOC);
            $totalRows_rsContactemail = $stmt_rsContactemail->rowCount();
            }      
            catch(PDOException $ex) {
                echo "An Error occured with queryX: ".$ex->getMessage();
            }
            if ($totalRows_rsContactemail === 0) {
            // $contact_email is missing
            echo '<h3>Det finns ingen anv&auml;ndare med e-postadressen du angett!</h3>';
            $output_form = 'yes';		
            }
      }
      else {
        // contact_email is invalid because LocalName is bad  
        echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3>';
        $output_form = 'yes';
      }
  }
?>
</div> <?php
}
else {
    $output_form = 'yes';
}
if ($output_form === 'yes') {
?>
<h3>Har du gl&ouml;mt ditt l&ouml;senord? </h3>
      <p> Fyll i din mejladress och klicka p&aring; Skicka, s&aring; skickas dina inloggningsuppgifter till den mejladress som finns registrerad f&ouml;r kontot.</p>
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" id="select_account" name="select_account">      
      <table border="0">
        <tr>
          <td>E-post</td>
          <td valign="top"><label>
            <input name="contact_email" type="text" id="contact_email" size="35" />
            <input type="submit" name="Submit" id="Submit" value="Skicka" />
          </label></td>
        </tr>
      </table>
      <input type="hidden" name="MM_select" value="select_account" />
    </form>
<?php
} 
else if ($output_form == 'no') {

$colname_rsAccount = "-1";
if (filter_input(INPUT_POST,'contact_email')) {
  $colname_rsAccount = filter_input(INPUT_POST,'contact_email');
}
//Catch anything wrong with query
try {
//SELECT data from account
require('Connections/DBconnection.php');         
$query = "SELECT * FROM account WHERE contact_email = :contact_email";
$stmt_rsAccount = $DBconnection->prepare($query);
$stmt_rsAccount->execute(array(':contact_email'=>$colname_rsAccount));
$row_rsAccount = $stmt_rsAccount->fetch(PDO::FETCH_ASSOC);
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   
$club_name = $row_rsAccount['club_name'];
$contact_name = $row_rsAccount['contact_name'];
$contact_phone = $row_rsAccount['contact_phone'];
$contact_email = $row_rsAccount['contact_email'];
$user_name = $row_rsAccount['user_name'];
$user_password = $row_rsAccount['user_password'];	

echo '<h3>' . $contact_name . ',<br />Dina inloggningsuppgifter skickades till: '. $contact_email .'. Logga in och g&ouml;r dina anm&auml;lningar.<h3/>';
       // Send email to club contact
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
        "E-post: $contact_email\n" .
        "Telefon: $contact_phone\n" .
	"Användarnamn: $user_name\n" .
	"Lösenord: $user_password\n" .
	"Använd ovanstående till att logga in och anmäla tävlande till $comp_name.\n" .
	"\n" .
	"Med vänliga hälsningar,\n" .
	"$comp_arranger, $comp_name, $comp_email";
        $msg = "Hej $contact_name,\n$text";

        // Send email to Club Contact
        mail($contact_email, $subject, $msg, $headers);                

//Kill statement
$stmt_rsAccount->closeCursor();
} ?>
    <div class="story">
    <p>&nbsp;</p>
    </div>
  </div>
</div>    
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>