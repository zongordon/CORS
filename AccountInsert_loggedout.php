<?php
//Made sure the white background is visible after submitting the form and changed from bold to normal confirmation text

//Convert strings to UTF-8
function encodeToUtf8($string) {
     return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}

//Convert strings to ISO-8859-1
function encodeToISO($string) {
     return mb_convert_encoding($string, "ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}

global $club_name, $contact_name, $contact_email, $contact_phone, $user_name, $user_password, $confirm_user_password ;

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="L&auml;gga till eget konto"?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, lägga till ett konto, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp"/>
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" /></head>
<?php include("includes/header.php"); ?>
<?php require_once('Connections/DBconnection.php'); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
   <div class="feature">    
      <div class="error">
<?php
// Validate insert account data
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_account")) {
    $club_name = encodeToISO(mb_convert_case($_POST['club_name'], MB_CASE_TITLE,"ISO-8859-1"));
    $contact_name = encodeToISO(mb_convert_case($_POST['contact_name'], MB_CASE_TITLE,"ISO-8859-1"));
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $user_name = encodeToISO($_POST['user_name']);
    $user_password = encodeToISO($_POST['user_password']);	
    $confirm_user_password = encodeToISO($_POST['confirm_user_password']);		
    $output_form = 'no';
	
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
   if (empty($contact_email)) {
      // $contact_email is blank
      echo '<h3>Du gl&ouml;mde att fylla i e-post!</h3>';
      $output_form = 'yes';
    }  
    
  if (!empty($contact_email)) {
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $contact_email)) {
      // $contact_email is invalid because LocalName is bad
      echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig!</h3>';
      $output_form = 'yes';
    }
    else {
      // Strip out everything but the domain from the email
      $domain = preg_replace('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', '', $contact_email);
	  
	 // Now check if $domain is registered ON A NON-WINDOWS SERVER
      if (!checkdnsrr($domain)) {
         echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig!</h3>';
         $output_form = 'yes';
     	}
 	}
        // Validate insert account data
	$colname_rsContactemail = $contact_email; 
 	mysql_select_db($database_DBconnection, $DBconnection);
	$query_rsContactemail = sprintf("SELECT club_name, contact_email FROM account WHERE contact_email = %s", GetSQLValueString(	$colname_rsContactemail, "text"));
	$rsContactemail = mysql_query($query_rsContactemail, $DBconnection) or die(mysql_error());
	$row_rsContactemail = mysql_fetch_assoc($rsContactemail);
	$totalRows_rsContactemail = mysql_num_rows($rsContactemail);
	
	if ($totalRows_rsContactemail > 0) {
        // $contact_email is already in use
        echo '<h3>E-postadressen &auml;r upptagen av '.$row_rsContactemail['club_name'].'!</h3>';
        $output_form = 'yes';		
	}
	mysql_free_result($rsContactemail);
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

	$colname_rsUsername = $user_name;
 	mysql_select_db($database_DBconnection, $DBconnection);
	$query_rsUsername = sprintf("SELECT club_name, user_name FROM account WHERE user_name = %s", GetSQLValueString(	$colname_rsUsername, "text"));
	$rsUsername = mysql_query($query_rsUsername, $DBconnection) or die(mysql_error());
	$row_rsUsername = mysql_fetch_assoc($rsUsername);
	$totalRows_rsUsername = mysql_num_rows($rsUsername);
	
	if ($totalRows_rsUsername > 0) {
    // $user_name is already in use
    echo '<h3>Anv&auml;ndarnamnet &auml;r upptaget!</h3>';
    $output_form = 'yes';		
	}
	mysql_free_result($rsUsername);
	
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
} 

  else {
    $output_form = 'yes';
  	}

  	if ($output_form == 'yes') {
// Select information regarding active accounts
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccounts = "SELECT club_name, contact_name, contact_email FROM account WHERE active = 1 ORDER BY club_name ASC";
$rsAccounts = mysql_query($query_rsAccounts, $DBconnection) or die(mysql_error());
$row_rsAccounts = mysql_fetch_assoc($rsAccounts);
?>
      </div>
<h3>Skapa ett nytt konto f&ouml;r att kunna registera t&auml;vlande</h3>
<p><strong>Obs!</strong> Titta f&ouml;rst i listan l&auml;ngst ner s&aring; att ni inte redan har ett konto, innan du skapar ett nytt!<br/><strong>T&auml;vlande &auml;r redan kopplade till dessa konton, vilket g&ouml;r det l&auml;ttare f&ouml;r dig att anm&auml;la!</strong><br/>Kontakta oss ifall ni inte l&auml;ngre har tillg&aring;ng till mejladressen i listan. 
    <br>Fyll i formul&auml;ret och klicka p&aring; knappen &quot;Nytt konto&quot;. Obs! Alla f&auml;lt &auml;r obligatoriska att fylla i!</br></p>

    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" id="new_account" name="new_account">      
      <table width="100%" border="0">
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
            <input name="contact_email" type="text" id="contact_email" size="25" value="<?php echo $contact_email; ?>"/>
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
          <td><input name="active" type="hidden" id="active" value="1" />
            <input name="access_level" type="hidden" id="access_level" value="0" />
            <input name="confirmed" type="hidden" id="confirmed" value="0" /></td>
          <td><label>
            <input type="submit" name="new_account" id="new_account" value="Nytt konto" />
          </label></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="new_account" />
    </form>
<!-- Show current accounts -->
<h3>Registrerade konton</h3>
      <p>Detta &auml;r konton som redan finns registrerade.</p>
  <table width="100%" border="1" cellpadding="2">
    <tr>
      <td nowrap="nowrap"><strong>Klubbnamn</strong></td>
      <td nowrap="nowrap"><strong>Kontaktnamn</strong></td>
      <td nowrap="nowrap"><strong>E-post</strong></td>      
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_rsAccounts['club_name']; ?></td>
        <td><?php echo $row_rsAccounts['contact_name']; ?></td>
        <td><?php echo $row_rsAccounts['contact_email']; ?></td>
      </tr>
      <?php } while ($row_rsAccounts = mysql_fetch_assoc($rsAccounts)); ?>
  </table>
<?php
  	} 
	//Send the account information to the users email address and save it
  	else if ($output_form == 'no') {
        //Email to to Tuna Karate Cup Admin    
        $headers = "From: Tuna Karate Cup <tunacup@karateklubben.com>\r\n" .
        "MIME-Version: 1.0\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" .        
        "Content-Type: text/plain; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n";         
        $adm_email = "tunacup@karateklubben.com";
        $subject_adm = 'Nytt konto registrerat: http://tunacup.karateklubben.com';
	$text_adm = "Detta konto har registrerats på tunacup.karateklubben.com:\n" .
	"Klubbnamn: $club_name\n" .
        "Kontaktperson: $contact_name\n" .
        "E-post: $contact_email\n" .
        "Telefon: $contact_phone\n" .
	"Användarnamn: $user_name\n" .
	"\n";
        $msg_adm = "Nytt konto registrerat!\n$text_adm";

        // Send email to Tuna Karate Cup Admin
        mail($adm_email, $subject_adm, $msg_adm, $headers);                
        
        //Email to to Club Contact
        $headers = "From: Tuna Karate Cup <tunacup@karateklubben.com>\r\n" .
        "MIME-Version: 1.0\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" .        
        "Content-Type: text/plain; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n";         
        $subject = 'Ditt nya konto: http://tunacup.karateklubben.com';
        $text = "Tack för att du registrerat ett konto på tunacup.karateklubben.com!\n" .
        "Här är de inloggningsuppgifter som du registrerade:\n" .
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
        
        // Send email to club contact
        mail($contact_email, $subject, $msg, $headers);                
   
 	echo '<br />' . $contact_name . ',<br />Tack f&ouml;r att du har skaffat ett konto p&aring; tunacup.karateklubben.com!<br />Dina uppgifter skickades till: '. $contact_email .'<br />Logga in och g&ouml;r dina anm&auml;lningar!</div>';
	// Insert account data if insert data is validated correctly
        $insertSQL = sprintf("INSERT INTO account (user_name, user_password, confirmed, contact_name, contact_email, contact_phone, club_name, active, access_level) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($user_name, "text"),
                       GetSQLValueString($user_password, "text"),
                       GetSQLValueString($_POST['confirmed'], "int"),
                       GetSQLValueString($contact_name, "text"),
                       GetSQLValueString($_POST['contact_email'], "text"),
                       GetSQLValueString($_POST['contact_phone'], "text"),
                       GetSQLValueString($club_name, "text"),
                       GetSQLValueString($_POST['active'], "int"),
                       GetSQLValueString($_POST['access_level'], "int"));

        mysql_select_db($database_DBconnection, $DBconnection);
        $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
	} ?>
   </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>