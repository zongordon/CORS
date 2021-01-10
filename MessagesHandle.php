<?php
//Added class for table layout in css file
//Added class for styling button in css file
ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Catch anything wrong with query
try {
//Select comp_id from the current competition, for the first time when there are no messages yet    
require('Connections/DBconnection.php');           
$query = "SELECT comp_id FROM competition WHERE comp_current = 1";
$stmt_rsCompActive = $DBconnection->query($query);
$row_rsCompActive = $stmt_rsCompActive->fetch(PDO::FETCH_ASSOC);
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   

//Catch anything wrong with query
try {
// Select all messages and comp_id for the current competition
require('Connections/DBconnection.php');           
$queryMessages = "SELECT m.message_id, message_subject, message, message_how, message_to, message_from, message_timestamp, co.comp_id FROM messages AS m INNER JOIN competition AS co ON m.comp_id = co.comp_id WHERE co.comp_current = 1 ORDER BY message_timestamp DESC";
$stmt_rsMessages = $DBconnection->query($queryMessages);
$totalRows_rsMessages = $stmt_rsMessages->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}
$pagetitle="Hantera meddelanden";
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
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id ="content">    
    <div class ="feature">        
<?php if ($totalRows_rsMessages == 0) { // Show if recordset empty ?>
        <h3>Det finns inga nyheter &auml;n!</h3>
<?php } ?>
<h3>Hantera meddelanden och nyheter p&aring; sajten.</h3>
        <div class="error">
<?php 
    //Declare and initialise variables
    $insert_message_subject = '';$insert_message = '';$insert_message_how = '';$insert_message_to = '';
 // Validate the contestant form if the button is clicked
if (filter_input(INPUT_POST,"MM_insert_message") === "new_message") {
    $insert_message_subject = encodeToUtf8(filter_input(INPUT_POST,'message_subject'));
    $insert_message = encodeToUtf8(filter_input(INPUT_POST,'message'));
    $insert_message_how = filter_input(INPUT_POST,'message_how');
    $insert_message_to = filter_input(INPUT_POST,'message_to');
    $insert_message_from = $comp_email;

    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('titel')->value($insert_message_subject)->pattern('text')->required()->min($length);
    $val->name('meddelande')->value($insert_message)->pattern('text')->required()->min($length);
    $val->name('hur meddelandet ska sparas/skickas')->value($insert_message_how)->pattern('alpha')->required();
    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }       
    // $insert_message_how is set to send emails
    if ($insert_message_how === 'EmailOnly' || $insert_message_how === 'SiteAndEmail') {
        if (empty($insert_message_to)) {
        // $insert_message_to is blank
        echo '<h3>Du gl&ouml;mde att v&auml;lja till vilka meddelandet ska skickas.</h3>';    
        $output_form = 'yes';    
        }
    }
    // Insert new message if the button is clicked and the form is validated ok
    if ($output_form === 'no') {
    //Get comp_id from active competition    
    $comp_id = $row_rsCompActive['comp_id'];
    //Set timestamp for Now()
    $now = date('Y-m-d H:i');
                
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');         
    $query = "INSERT INTO messages (message_subject, message, message_how, message_to, message_from, message_timestamp, comp_id) VALUES (:message_subject, :message, :message_how, :message_to, :message_from, :message_timestamp, :comp_id)";
    $stmt = $DBconnection->prepare($query);
    $stmt->bindValue(':message_subject', $insert_message_subject, PDO::PARAM_STR);
    $stmt->bindValue(':message', $insert_message, PDO::PARAM_STR);
    $stmt->bindValue(':message_how', $insert_message_how, PDO::PARAM_STR);
    $stmt->bindValue(':message_to', $insert_message_to, PDO::PARAM_STR);
    $stmt->bindValue(':message_from', $insert_message_from, PDO::PARAM_STR);
    $stmt->bindValue(':message_timestamp', $now, PDO::PARAM_STR);
    $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
    $stmt->execute();
    }   
    catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }   
        //Select who to send email to (clubs with registered contestants to the current competition or all registered users)                
        if ($insert_message_how === 'EmailOnly' || $insert_message_how === 'SiteAndEmail') {
            //Select email addresses from clubs with registered coaches to the current competition
            if ($insert_message_to === 'CurrentComp') {
                
                //Catch anything wrong with query
                try {
                require('Connections/DBconnection.php');           
                $query_rsClubEmails = "SELECT contact_name, contact_email FROM account AS a INNER JOIN clubregistration AS cl USING (account_id) INNER JOIN competition AS c USING (comp_id) WHERE comp_current = 1";
                $stmt_rsClubEmails = $DBconnection->query($query_rsClubEmails);
                $totalRows_rsClubEmails = $stmt_rsClubEmails->rowCount();
                }   
                catch(PDOException $ex) {
                    echo "An Error occured with queryX: ".$ex->getMessage();
                }    
            }
            //Select email addresses from all clubs 
            if ($insert_message_to === 'All') {

                //Catch anything wrong with query
                try {
                require('Connections/DBconnection.php');           
                $query_rsClubEmails = "SELECT contact_name, contact_email FROM account";
                $stmt_rsClubEmails = $DBconnection->query($query_rsClubEmails);
                $totalRows_rsClubEmails = $stmt_rsClubEmails->rowCount();
                }   
                catch(PDOException $ex) {
                    echo "An Error occured with queryX: ".$ex->getMessage();
                }                            
            }
            while($row_rsClubEmails = $stmt_rsClubEmails->fetch(PDO::FETCH_ASSOC)) {  
            $contact_name = $row_rsClubEmails['contact_name'];                          
            $contact_email = $row_rsClubEmails['contact_email'];
  
            //Email to to selected Club Contacts
            $headers = "From: $comp_name <$comp_email>\r\n" .
            "MIME-Version: 1.0\r\n" . 
            'X-Mailer: PHP/' . phpversion() . "\r\n" .        
            "Content-Type: text/plain; charset=utf-8\r\n" . 
            "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
            $message_subject = $insert_message_subject;
            $message_body =  $insert_message; 
            $message = $message_body.
            "\n" .
            "\n" .        
            "Med vänliga hälsningar,\n" .
            "Administrationen för $comp_name, $comp_url";
            $msg = "Hej $contact_name,\n$message";
        
            // Send email to club contact
            mail($contact_email, '=?utf-8?B?'.base64_encode($message_subject).'?=', $msg, $headers);                
            }                   
        }
            $insertGoTo = "MessagesHandle.php#new_message";
            header(sprintf("Location: %s", $insertGoTo));  	
            $stmt_rsClubEmails->closeCursor();
    }	
}
?>
        </div>
<form id="new_message" name="new_message" method="POST" action="<?php echo $editFormAction; ?>">
      <table class="narrow_tbl" border="0">
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
              <input type="radio" name="message_how" id="message_how" value="SiteOnly" <?php if ($insert_message_how == "SiteOnly"){ echo "checked='checked'"; }?>/>
              Spara som nyhet</label><br>
            <label>
              <input type="radio" name="message_how" id="message_how" value="EmailOnly" <?php if ($insert_message_how == "EmailOnly"){ echo "checked='checked'";} ?>/>
              Skicka som e-post</label><br>
            <label>
              <input type="radio" name="message_how" id="message_how" value="SiteAndEmail" <?php if ($insert_message_how == "SiteAndEmail"){ echo "checked='checked'";} ?>/>
              Spara som nyhet och skicka som e-post</label>              
          </td>
        </tr>
        <tr>
            <td valign ="top">S&auml;tt att spara/skicka meddelandet:</td>
          <td valign="top">
            <label>
              <input type="radio" name="message_to" id="message_to" value="CurrentComp" <?php if ($insert_message_to == "CurrentComp"){ echo "checked='checked'";} ?>/>
              Registrerade till aktuell t&auml;vling</label><br>
            <label>
              <input type="radio" name="message_to" id="message_to" value="All" <?php if ($insert_message_to === "All"){ echo "checked='checked'";} ?>/>
              Alla registrerade anv&auml;ndare</label>
            <label>
          </td>
        </tr>
        <tr>
          <td><input type="hidden" name="MM_insert_message" value="new_message" />
              <input type="hidden" name="comp_id" value="<?php echo $row_rsCompActive['comp_id']; ?>" />
          <td><label>
              <input type="submit" name="new_message" class = "button" id="new_message" value="Nytt meddelande" />
          </label></td>
        </tr>
      </table>
    </form>   
<?php        
if ($totalRows_rsMessages > 0) { // Show if recordset not empty ?>
	<h3><a name="message_delete" id="message_delete"></a>Ta bort meddelanden</h3>
	<p>Om n&aring;got har blivit fel kan du ta bort meddelandet.</p>
      <table class="wide_tbl" border="1">
        <tr>
          <td><strong>Titel</strong></td>
          <td><strong>Meddelande</strong></td>
          <td><strong>Sparat/skickat</strong></td>
          <td><strong>Datum och tid</strong></td>
          <td nowrap="nowrap"><strong>Ta bort</strong></td>          
        </tr>
        <?php while($row_rsMessages = $stmt_rsMessages->fetch(PDO::FETCH_ASSOC)) { ?>
          <tr>
            <td valign ="top"><?php echo $row_rsMessages['message_subject']; ?></td>
            <td valign ="top"><?php echo $row_rsMessages['message']; ?></td>
            <td valign ="top"><?php if ($row_rsMessages['message_how'] === "SiteOnly") {
                                    echo 'Sparat som nyhet';
                                    }
                                    if ($row_rsMessages['message_how'] === "EmailOnly") {
                                    echo 'Skickat som e-post';
                                    }
                                    if ($row_rsMessages['message_how'] === "SiteAndEmail") {
                                    echo 'Sparat som nyhet och skickat som e-post';
                                    } ?></td>
            <td valign ="top"><?php echo $row_rsMessages['message_timestamp']; ?></td>            
            <td valign ="top" nowrap="nowrap"><a href="#" onclick="return deleteMessage('<?php echo $row_rsMessages['message_id']; ?>')">Ta bort</a>
            </td></tr>
        <?php } ?>
      </table>
<?php 
} // Show if rsMessages recordset not empty
?>        
    </div>
</div>
<?php 
//Kill statements
$stmt_rsCompActive->closeCursor();
$stmt_rsMessages->closeCursor();
include("includes/footer.php");?>    
</body>
</html>
<?php ob_end_flush();?>