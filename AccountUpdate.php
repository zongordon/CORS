<?php 
//Secured input (UTF-8) into database after upgrading to PHP 5.6.23, causing problem with special characters - https://github.com/zongordon/CORS/issues/16
ob_start();

//Access level admin
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="&Auml;ndra anv&auml;ndarkonto - admin";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, ändra uppgifterna i valt användarkonto, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_rsAccount = "-1";
if (isset($_GET['account_id'])) {
  $colname_rsAccount = $_GET['account_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccount = sprintf("SELECT * FROM account WHERE account_id = %s", GetSQLValueString($colname_rsAccount, "int"));
$rsAccount = mysql_query($query_rsAccount, $DBconnection) or die(mysql_error());
$row_rsAccount = mysql_fetch_assoc($rsAccount);
$totalRows_rsAccount = mysql_num_rows($rsAccount);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">      
    <div class="feature">    
        <div class="error">    
<?php
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "AccountForm")) {
    $club_name = encodeToUtf8(mb_convert_case($_POST['club_name'], MB_CASE_TITLE,"UTF-8"));
    $contact_name = encodeToUtf8(mb_convert_case($_POST['contact_name'], MB_CASE_TITLE,"UTF-8"));
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];
    $user_name = encodeToUtf8($_POST['user_name']);
    $user_password = encodeToUtf8($_POST['user_password']);	
    $confirm_user_password = encodeToUtf8($_POST['confirm_user_password']);			
    $output_form = 'no';

	echo '<br />';	
	
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

    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $contact_email)) {
      // $contact_email is invalid because LocalName is bad
      echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3>';
      $output_form = 'yes';
    }
    else {
      // Strip out everything but the domain from the email
      $domain = preg_replace('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', '', $contact_email);
	  
	 // Now check if $domain is registered ON A NON-WINDOWS SERVER
      if (!checkdnsrr($domain)) {
         echo '<h3>Den ifyllda e-postadressen &auml;r inte giltig.</h3>';
         $output_form = 'yes';
     	}
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
      echo '<h3>L&ouml;senorden var inte identiska.</h3>';
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
          <td>Administrat&ouml;rskonto</td>
          <td><label>
            <input name="access_level" type="checkbox" id="access_level" value="1" <?php if (!(strcmp($row_rsAccount['access_level'],1))) {echo "checked=\"checked\"";} ?> />
          </label></td>
        </tr>
        <tr>
          <td>Aktivt konto</td>
          <td><label>
<input name="active" type="checkbox" id="active" value="1" <?php if (!(strcmp($row_rsAccount['active'],1))) {echo "checked=\"checked\"";} ?> />
          </label></td>
        </tr>
        <tr>
          <td>Bekr&auml;ftat konto</td>
          <td><label>
<input name="confirmed" type="checkbox" id="confirmed" value="1" <?php if (!(strcmp($row_rsAccount['confirmed'],1))) {echo "checked=\"checked\"";} ?> />
          </label></td>
        </tr>
        <tr>
          <td align="right" valign="baseline" nowrap="nowrap"><input type="hidden" name="MM_update" value="AccountForm" />            <input name="account_id" type="hidden" id="account_id" value="<?php echo $row_rsAccount['account_id']; ?>" /></td>
          <td><input name="AccountUpdate" type="submit" id="AccountUpdate" value="Spara" /></td>
        </tr>
      </table>
    </form>
    <p>&nbsp;</p>
</div>
</div>
<?php
} 
	//Save the account information 
  	else if ($output_form == 'no') {
	
  $updateSQL = sprintf("UPDATE account SET user_name=%s, user_password=%s, confirmed=%s, contact_name=%s, contact_email=%s, contact_phone=%s, club_name=%s, active=%s, access_level=%s WHERE account_id=%s",
                       GetSQLValueString($user_name, "text"),
                       GetSQLValueString($user_password, "text"),
                       GetSQLValueString(isset($_POST['confirmed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($contact_name, "text"),
                       GetSQLValueString($_POST['contact_email'], "text"),
                       GetSQLValueString($_POST['contact_phone'], "text"),
                       GetSQLValueString($club_name, "text"),
                       GetSQLValueString(isset($_POST['active']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['access_level']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['account_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());

  $updateGoTo = "AccountsList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsAccount);
?>
<?php ob_end_flush();?>