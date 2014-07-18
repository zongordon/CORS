<?php
//Adjusted to display page title

global $editFormAction;

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Gl&ouml;mt ditt l&ouml;senord?"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall."
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content"> 
  <div class="feature">
       <div class="error">       
      <p><?php
 if ((isset($_POST["MM_select"])) && ($_POST["MM_select"] == "select_account")) {
    $contact_email = $_POST['contact_email'];
    $output_form = 'no';

	require_once('Connections/DBconnection.php');

   if (empty($contact_email)) {
      // $contact_email is blank
      echo '<h3>Du gl&ouml;mde att fylla i e-post!</h3>';
      $output_form = 'yes';
    }
  if (!empty($contact_email)) {
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $contact_email)) {
      // $contact_email is invalid because LocalName is bad
      echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3><br />';
      $output_form = 'yes';
    }
    else {
      // Strip out everything but the domain from the email
      $domain = preg_replace('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', '', $contact_email);
	  
	 // Now check if $domain is registered (USED ON A NON-WINDOWS SERVER)
      if (!checkdnsrr($domain)) {
         echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3><br /></a>';
         $output_form = 'yes';
     	}
 	}
	 
	$colname_rsContactemail = $contact_email; 
 	mysql_select_db($database_DBconnection, $DBconnection);
	$query_rsContactemail = sprintf("SELECT contact_email FROM account WHERE contact_email = %s", GetSQLValueString(	$colname_rsContactemail, "text"));
	$rsContactemail = mysql_query($query_rsContactemail, $DBconnection) or die(mysql_error());
	$row_rsContactemail = mysql_fetch_assoc($rsContactemail);
	$totalRows_rsContactemail = mysql_num_rows($rsContactemail);
	
	if ($totalRows_rsContactemail == 0) {
        // $contact_email is missing
        echo '<h3>Det finns ingen anv&auml;ndare med e-postadressen du angett!</h3>';
        $output_form = 'yes';		
    	mysql_free_result($rsContactemail);
	}
  } 

} 

  else {
    $output_form = 'yes';
  	}

  	if ($output_form == 'yes') {
?>
       </div>
<h3>Har du gl&ouml;mt ditt l&ouml;senord? </h3>
      <p> Fyll i din mejladress och klicka p&aring; Skicka, s&aring; skickas dina inloggningsuppgifter till den mejladress du skrivit in.</p>
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
if (isset($_POST['contact_email'])) {
  $colname_rsAccount = $_POST['contact_email'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccount = sprintf("SELECT * FROM account WHERE contact_email = %s", GetSQLValueString($colname_rsAccount, "text"));
$rsAccount = mysql_query($query_rsAccount, $DBconnection) or die(mysql_error());
$row_rsAccount = mysql_fetch_assoc($rsAccount);
$totalRows_rsAccount = mysql_num_rows($rsAccount);

$club_name = $row_rsAccount['club_name'];
$contact_name = $row_rsAccount['contact_name'];
$contact_phone = $row_rsAccount['contact_phone'];
$contact_email = $row_rsAccount['contact_email'];
$user_name = $row_rsAccount['user_name'];
$user_password = $row_rsAccount['user_password'];	

echo '<h3>' . $contact_name . ',<br />Dina inloggningsuppgifter skickades till: '. $contact_email .'. Logga in och g&ouml;r dina anm&auml;lningar.<h3/>';
        $subject = 'Login till kontot: tunacup.karateklubben.com';
	$text = "Här är de inloggningsuppgifter som är registrerade på http://tunacup.karateklubben.com:\n" .
	"Klubbnamn: $club_name\n" .
        "Kontaktperson: $contact_name\n" .
        "E-post: $contact_email\n" .
        "Telefon: $contact_phone\n" .
	"Användarnamn: $user_name\n" .
	"Lösenord: $user_password\n" .
	"Använd ovanstående till att logga in och anmäla tävlande till Tuna Karate Cup.\n" .
	"\n" .
	"Med vänliga hälsningar,\n" .
	"Eskilstuna Karateklubb, http://www.karateklubben.com";
        $msg = "Hej $contact_name,\n$text";

        $headers = "From: Tuna Karate Cup <tunacup@karateklubben.com>";
        // Send email to Club Contact
        mail($contact_email, $subject, $msg, $headers);                

	mysql_free_result($rsAccount);
	}	 
	?>
 </p>
  </div>
  <div class="story">
    <p>&nbsp;</p>
</div>
</div>
<br />
</body>
</html>