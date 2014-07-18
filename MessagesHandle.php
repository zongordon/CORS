<?php
//Added function for confirmation before deletion for account
ob_start();

Global $insert_message_id;
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Hantera meddelanden";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, hantera meddelanden till registrerade användare och nyheter på sajt, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');  

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//Select comp_id from the current competition, for the first time when there are no messages yet
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompActive = "SELECT comp_id FROM competition WHERE comp_current = 1";
$rsCompActive = mysql_query($query_rsCompActive, $DBconnection) or die(mysql_error());
$row_rsCompActive = mysql_fetch_assoc($rsCompActive);

// Select all messages and comp_id for the current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsMessages = "SELECT m.message_id, message_subject, message, message_how, message_to, message_from, message_timestamp, co.comp_id FROM messages AS m INNER JOIN competition AS co ON m.comp_id = co.comp_id WHERE co.comp_current = 1 ORDER BY message_timestamp";
$rsMessages = mysql_query($query_rsMessages, $DBconnection) or die(mysql_error());
$row_rsMessages = mysql_fetch_assoc($rsMessages);
$totalRows_rsMessages = mysql_num_rows($rsMessages);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="story">
<?php if ($totalRows_rsMessages == 0) { // Show if recordset empty ?>
        <div class="error">        
        <h3>Det finns inga nyheter &auml;n!</h3>
        </div>
<?php } ?>
    </div>
    <div class="feature">
<h3>Hantera meddelanden och nyheter p&aring; sajten.</h3>
        <div class="error">
<?php 
    $insert_message_subject = "";
    $insert_message = "";
    $insert_message_how = "";
    $insert_message_to = "";
 // Validate the contestant form if the button is clicked	
if (((isset($_POST["MM_insert_message"])) && ($_POST["MM_insert_message"] == "new_message"))) {
    $insert_message_subject = encodeToISO($_POST['message_subject']);
    $insert_message = encodeToISO($_POST['message']);
    $insert_message_how = $_POST['message_how'];
    $insert_message_to = $_POST['message_to'];
    $insert_message_from = "tunacup@karateklubben.com";
    $output_form = 'no';
	        
    if (empty($insert_message_subject)) {
      // $insert_message_subject is blank
      echo '<h3>Du gl&ouml;mde att fylla i titel!</h3>';
      $output_form = 'yes';
    }
    if (empty($insert_message)) {
      // $insert_message_subject is blank
      echo '<h3>Du gl&ouml;mde att fylla i meddelandet!</h3>';
      $output_form = 'yes';
    }
    if (empty($insert_message_how)) {	
      // $insert_message_how is blank
      echo '<h3>Du gl&ouml;mde att v&auml;lja hur meddelandet ska sparas/skickas.</h3>';
      $output_form = 'yes';
    }
    // $insert_message_how is set to send emails
    if ($insert_message_how == 'EmailOnly' || $insert_message_how == 'SiteAndEmail') {
        if (empty($insert_message_to)) {
        // $insert_message_to is blank
        echo '<h3>Du gl&ouml;mde att v&auml;lja till vilka meddelandet ska skickas.</h3>';    
        $output_form = 'yes';    
        }
    }
 
	if ($output_form == 'no') {		
		// Insert new message if the button is clicked and the form is validated ok
  		$insertSQL = sprintf("INSERT INTO messages (message_subject, message, message_how, message_to, message_from, comp_id) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($insert_message_subject, "text"),
                       GetSQLValueString($insert_message, "text"),
                       GetSQLValueString($insert_message_how, "text"),
                       GetSQLValueString($insert_message_to, "text"),
                       GetSQLValueString($insert_message_from, "text"),
                       GetSQLValueString($_POST['comp_id'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
                
                if ($insert_message_how == 'EmailOnly' || $insert_message_how == 'SiteAndEmail') {
                    //Select who to send email to (clubs with registered contestants to the current competition or all registered users)
                    if ($insert_message_to == 'CurrentComp') {
                    //Select email addresses from clubs with registered coaches to the current competition
                    //$contact_name ='Frank';    
                    //$contact_email = 'frank.staffas@gmail.com';    
                    mysql_select_db($database_DBconnection, $DBconnection);
                    $query_rsClubEmails = "SELECT contact_name, contact_email FROM account AS a INNER JOIN clubregistration AS cl USING (account_id) INNER JOIN competition AS c USING (comp_id) WHERE comp_current = 1";
                    $rsClubEmails = mysql_query($query_rsClubEmails, $DBconnection) or die(mysql_error());
                    }
                    //Select email addresses from all clubs 
                    if ($insert_message_to == 'All') {
                    //$contact_email = 'tunacup@karateklubben.com';    
                    //$contact_name ='Tuna';    
                    mysql_select_db($database_DBconnection, $DBconnection);
                    $query_rsClubEmails = "SELECT contact_name, contact_email FROM account";
                    $rsClubEmails = mysql_query($query_rsClubEmails, $DBconnection) or die(mysql_error());                  
                    }
                    do {  
                    $contact_name = $row_rsClubEmails['contact_name'];                          
                    $contact_email = $row_rsClubEmails['contact_email'];
  
                    //Email to to selected Club Contacts
                    $headers = "From: Tuna Karate Cup <tunacup@karateklubben.com>\r\n" .
                    "MIME-Version: 1.0\r\n" . 
                    'X-Mailer: PHP/' . phpversion() . "\r\n" .        
                    "Content-Type: text/plain; charset=utf-8\r\n" . 
                    "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
                    $message_subject = encodeToUtf8($insert_message_subject);
                    $message_body =  encodeToUtf8($insert_message); 
                    $message = $message_body.
                    "\n" .
                    "\n" .        
                    "Med vänliga hälsningar,\n" .
                    "Administrationen för Tuna Karate Cup, http://tunacup.karateklubben.com";
                    $msg = "Hej $contact_name,\n$message";
        
                    // Send email to club contact
                    mail($contact_email, $message_subject, $msg, $headers);                
               
                    } while ($row_rsClubEmails = mysql_fetch_assoc($rsClubEmails));                  
                }
  		$insertGoTo = "MessagesHandle.php#new_message";
		header(sprintf("Location: %s", $insertGoTo));  		
	}	
}
?>
        </div>
<form id="new_message" name="new_message" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="500" border="0">
        <tr>
          <td>Meddelandets titel</td>
          <td><label>
              <input name="message_subject" type="text" id="message_subject" size="50" value="<?php echo $insert_message_subject; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>Meddelande</td>
          <td valign="top"><label>
            <textarea name="message" id="message" value="<?php echo $insert_message; ?>" cols="40" rows="5"></textarea>
          </label></td>
        </tr>
        <tr>
            <td valign ="top">S&auml;tt att spara/skicka meddelandet:</td>
          <td valign="top">
              <label>
              <input type="radio" name="message_how" id="message_how" value="SiteOnly" <?php if ($insert_message_how == "SiteOnly") echo "checked='checked'"; ?>//>
              Spara som nyhet</label><br>
            <label>
              <input type="radio" name="message_how" id="message_how" value="EmailOnly" <?php if ($insert_message_how == "EmailOnly") echo "checked='checked'"; ?>/>
              Skicka som e-post</label><br>
            <label>
              <input type="radio" name="message_how" id="message_how" value="SiteAndEmail" <?php if ($insert_message_how == "SiteAndEmail") echo "checked='checked'"; ?>/>
              Spara som nyhet och skicka som e-post</label>              
          </td>
        </tr>
        <tr>
            <td valign ="top">S&auml;tt att spara/skicka meddelandet:</td>
          <td valign="top">
            <label>
              <input type="radio" name="message_to" id="message_to" value="CurrentComp" <?php if ($insert_message_to == "CurrentComp") echo "checked='checked'"; ?>/>
              Registrerade till aktuell t&auml;vling</label><br>
            <label>
              <input type="radio" name="message_to" id="message_to" value="All" <?php if ($insert_message_to == "All") echo "checked='checked'"; ?>/>
              Alla registrerade anv&auml;ndare</label>
            <label>
          </td>
        </tr>
        <tr>
          <td><input type="hidden" name="MM_insert_message" value="new_message" />
              <input type="hidden" name="comp_id" value="<?php echo $row_rsCompActive['comp_id']; ?>" />
          <input name="message_id" type="hidden" id="message_id" value="<?php echo $insert_message_id; ?>" /></td>
          <td><label>
              <input type="submit" name="new_message" id="new_message" value="Nytt meddelande" />
          </label></td>
        </tr>
      </table>
    </form>   
<?php        
if ($totalRows_rsMessages > 0) { // Show if recordset not empty ?>
	<h3><a name="message_delete" id="message_delete"></a>Ta bort meddelanden</h3>
	<p>Om n&aring;got har blivit fel kan du ta bort meddelandet.</p>
      <table width="100%" border="1">
        <tr>
          <td><strong>Titel</strong></td>
          <td><strong>Meddelande</strong></td>
          <td><strong>Sparat/skickat</strong></td>
          <td><strong>Datum och tid</strong></td>
          <td nowrap="nowrap"><strong>Ta bort</strong></td>          
        </tr>
        <?php do { ?>
          <tr>
            <td valign ="top"><?php echo $row_rsMessages['message_subject']; ?></td>
            <td valign ="top"><?php echo $row_rsMessages['message']; ?></td>
            <td valign ="top"><?php if ($row_rsMessages['message_how'] == "SiteOnly") {
                                    echo 'Sparat som nyhet';
                                    }
                                    if ($row_rsMessages['message_how'] == "EmailOnly") {
                                    echo 'Skickat som e-post';
                                    }
                                    if ($row_rsMessages['message_how'] == "SiteAndEmail") {
                                    echo 'Sparat som nyhet och skickat som e-post';
                                    } ?></td>
            <td valign ="top"><?php echo $row_rsMessages['message_timestamp']; ?></td>            
            <td valign ="top" nowrap="nowrap"><a href="#" onclick="return deleteMessage('<?php echo $row_rsMessages['message_id']; ?>')">Ta bort</a>
            </td></tr>
          <?php } while ($row_rsMessages = mysql_fetch_assoc($rsMessages)); ?>
      </table>
<?php 
} // Show if rsMessages recordset not empty
mysql_free_result($rsMessages);
?>        
    </div>
</div>
<?php include("includes/footer.php");?>    
</body>
</html>
<?php ob_end_flush();?>