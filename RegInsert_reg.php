<?php
//Added start numbers for each registration (starting with 100)

ob_start();
//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Registrera egna t&auml;vlande";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Registrera egna tävlande, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');  

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// Select all classes
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = "SELECT cl.class_id, cl.comp_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, cl.class_gender_category";
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$row_rsClasses = mysql_fetch_assoc($rsClasses);
$totalRows_rsClasses = mysql_num_rows($rsClasses);

// Select the contestants and their information for the selected class
$Current_Comp_id = $row_rsClasses['comp_id'];

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsRegistrations = sprintf("SELECT re.reg_id, re.contestant_height, re.contestant_startnumber, co.contestant_name, co.contestant_birth, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE account_id = %s AND comp_id = '$Current_Comp_id' ORDER BY co.contestant_name", GetSQLValueString($_SESSION['MM_AccountId'], "int"));
$rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
$row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
$totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);

//Select data from the current competition including max number of registrations for the current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompActive = "SELECT comp_id, comp_end_reg_date, comp_max_regs FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1";
$rsCompActive = mysql_query($query_rsCompActive, $DBconnection) or die(mysql_error());
$row_rsCompActive = mysql_fetch_assoc($rsCompActive);
$totalRows_rsCompActive = mysql_num_rows($rsCompActive);        

// Select the currently highest start number
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsMax_startnumber = "SELECT MAX(contestant_startnumber)AS max_startnumber FROM registration INNER JOIN classes AS cl USING (class_id) JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1"; 
$rsMax_startnumber = mysql_query($query_rsMax_startnumber, $DBconnection) or die(mysql_error());
$row_rsMax_startnumber = mysql_fetch_assoc($rsMax_startnumber);

// Set a new start number (starting with 100)
$max_startnumber = $row_rsMax_startnumber['max_startnumber'];
    if ($max_startnumber < 100){
        $contestant_startnumber = 100;
    }
    else {
        $contestant_startnumber = $max_startnumber + 1;
    }
//Setting the date for today (including format), last enrolment date and check if the last enrolment date is passed or not
$now = date('Y-m-d');
$endEnrolmentDate = $row_rsCompActive['comp_end_reg_date'];
$passedDate = 0;
if ($endEnrolmentDate < $now) {
	$passedDate = 1;
}
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
       <div class="feature">    
<?php 
// Show if recordset rsClasses empty 
if ($totalRows_rsClasses == 0) {?>
    <p>Det finns inga klasser att anm&auml;la till &auml;n!</p>
<?php
}
// If recordset rsClasses is NOT empty 
if ($totalRows_rsClasses > 0) {

    //Show if the last date for registration is passed
    if ($passedDate == 1) { ?>
	<div class="error">
        <h3>Sista anm&auml;lningsdagen &auml;r passerad och inga till&auml;gg eller &auml;ndringar g&aring;r att g&ouml;ra online! Kontakta t&auml;vlingsledningen vid akuta behov.</h3>
	</div>
<?php
    }
  //Show if the last date for registration is NOT passed
  if ($passedDate == 0) { ?> 
        <h3>Registera t&auml;vlande klubbmedlemmar och anm&auml;l dem till deras t&auml;vlingsklasser</h3>
        <p>Anm&auml;lan g&ouml;rs i fyra steg:</p>
        <ol>
            <li>Skriv in namnen p&aring; de coacher som ska st&ouml;tta era t&auml;vlande.</li>
            <li>L&auml;gg in klubbens t&auml;vlande en och en. De l&auml;ggs till i listan under formul&auml;ret, allt eftersom de l&auml;ggs in. </li>
            <li>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i.</li>
            <li>Alla t&auml;vlingsanm&auml;lningar listas l&auml;ngst ned p&aring; sidan, s&aring; att du kan  ta bort dem om n&aring;got har blivit fel.</li>
        </ol>
        <h3>1. Skriv in klubbens coacher</h3>
        <p>Skriv in namnen p&aring; de coacher som ska st&ouml;tta era t&auml;vlande och klicka p&aring; spara.</p>
     <div class="error">        
<?php // Validate the club registration form if the "Spara" button is clicked
    $coach_names = "";
if (((isset($_POST["MM_insert_clubregistration"])) && ($_POST["MM_insert_clubregistration"] == "new_club_reg") || (isset($_POST["MM_update_clubregistration"])) && ($_POST["MM_update_clubregistration"] == "update_club_reg"))) {
    $coach_names = encodeToISO(mb_convert_case($_POST['coach_names'], MB_CASE_TITLE,"ISO-8859-1"));
    $output_form = 'no';
	
    if (empty($coach_names)) {
      // $coach_names is blank
      echo '<h3>Du gl&ouml;mde att fylla i klubbens coacher!</h3>';
      $output_form = 'yes';
    } 
	if ($output_form == 'no') {
                //Insert new club registration if form validated ok
		if ((isset($_POST["MM_insert_clubregistration"])) && ($_POST["MM_insert_clubregistration"] == "new_club_reg")) {
  		$insertSQL = sprintf("INSERT INTO clubregistration (coach_names, account_id, comp_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($coach_names, "text"),
                       GetSQLValueString($_SESSION['MM_AccountId'], "int"),
                       GetSQLValueString($_POST['comp_id'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
		}
                //Update club registration if form validated ok
  		if ((isset($_POST["MM_update_clubregistration"])) && ($_POST["MM_update_clubregistration"] == "update_club_reg")) {
  		$updateSQL = sprintf("UPDATE clubregistration SET coach_names=%s WHERE club_reg_id=%s",
                       GetSQLValueString($coach_names, "text"),
                       GetSQLValueString($_POST['club_reg_id'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
		}
 	}
} ?>
     </div>
<?php  
// Select the club_id for user currently registering coaches	
$colname_rsClubReg = "-1";
if (isset($_SESSION['MM_AccountId'])) {
  $colname_rsClubReg = $_SESSION['MM_AccountId'];
}		

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClubReg = sprintf("SELECT a.club_name, cl.club_reg_id, cl.coach_names, co.comp_id FROM clubregistration AS cl INNER JOIN competition AS co USING (comp_id) INNER JOIN account AS a USING (account_id) WHERE account_id = %s AND comp_current = 1", GetSQLValueString($colname_rsClubReg, "int"));
$rsClubReg = mysql_query($query_rsClubReg, $DBconnection) or die(mysql_error());
$row_rsClubReg = mysql_fetch_assoc($rsClubReg);
$totalRows_rsClubReg = mysql_num_rows($rsClubReg);
        // Show if recordset empty 
 	if ($totalRows_rsClubReg == 0) {?>     
    <form id="new_club_reg" name="new_club_reg" method="POST" action="<?php echo $editFormAction; ?>">
<?php
	} 
      // Show if recordset NOT empty         
      if ($totalRows_rsClubReg <> 0) {?>
	<form id="update_club_reg" name="update_club_reg" method="POST" action="<?php echo $editFormAction; ?>"> 
<?php
	}?>
    <table width="400" border="0">
      <tr>
        <td valign="top">Coacher</td>
        <td><label>
          <input name="coach_names" type="text" id="coach_names" value="<?php echo $row_rsClubReg['coach_names']; ?>" size="55" /></label></td>
      </tr>
      <tr>
        <td>
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsCompActive['comp_id']; ?>" />
          <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />        </td>
        <td><label>
	<?php if ($totalRows_rsClubReg == 0) { // Show if recordset empty ?>   
          <input type="submit" name="new_club_reg" id="new_club_reg" value="Spara" />
          <input type="hidden" name="MM_insert_clubregistration" value="new_club_reg" />
	<?php } ?> 
	  	<?php if ($totalRows_rsClubReg <> 0) { // Show if recordset NOT empty ?>
          <input type="submit" name="update_club_reg" id="update_club_reg" value="Spara" /></label>
          <input type="hidden" name="MM_update_clubregistration" value="update_club_reg" /> 
		<?php } ?>
        </label></td>
      </tr>
    </table>      
        </form>
<?php // Show if recordset not empty 
    if ($totalRows_rsClubReg > 0) {?>
      <h3><a name="contestants" id="contestants"></a>2. L&auml;gg in klubbens t&auml;vlande</h3>
      <p>L&auml;gg in klubbens t&auml;vlande en och en. Ange namn, f&ouml;delsedatum och k&ouml;n.</p>
        <div class="error">
<?php 
    $insert_contestant_name = "";
    $insert_contestant_birth = "";
    $insert_contestant_gender = "";
// Validate the contestant form if the button is clicked	
if (((isset($_POST["MM_insert_contestant"])) && ($_POST["MM_insert_contestant"] == "new_contestant"))) {
    $insert_contestant_name = encodeToISO(mb_convert_case($_POST['contestant_name'], MB_CASE_TITLE,"ISO-8859-1"));    
    $insert_contestant_birth = $_POST['contestant_birth'];
    $insert_contestant_gender = $_POST['contestant_gender'];
    $output_form = 'no';
	
	echo '<br />';	
        //echo 'Namnet på den registrerade:'. $_POST['contestant_name'];
        
    if (empty($insert_contestant_name)) {
      // $insert_contestant_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i namn!</h3>';
      $output_form = 'yes';
    }
    if (empty($insert_contestant_birth)) {
      // $insert_contestant_birth is blank
      echo '<h3>Du gl&ouml;mde att fylla i f&ouml;delsedatum!</h3>';
      $output_form = 'yes';
	}
    if (!empty($insert_contestant_birth) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $insert_contestant_birth)) {
    // $insert_contestant_birth is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; f&ouml;delsedatum!</h3>';
    $output_form = 'yes';
    }	
    if (empty($insert_contestant_gender)) {	
      // $insert_contestant_gender is blank
      echo '<h3>Du gl&ouml;mde att v&auml;lja k&ouml;n.</h3>';
      $output_form = 'yes';
    }
 
	if ($output_form == 'no') {		
		// Insert new contestant if the button is clicked and the form is validated ok
  		$insertSQL = sprintf("INSERT INTO contestants (contestant_name, contestant_birth, contestant_gender, account_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($insert_contestant_name, "text"),
                       GetSQLValueString($_POST['contestant_birth'], "date"),
                       GetSQLValueString($_POST['contestant_gender'], "text"),
                       GetSQLValueString($_SESSION['MM_AccountId'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
		
  		$insertGoTo = "RegInsert_reg.php#registration_insert";
		header(sprintf("Location: %s", $insertGoTo));  		
	}	
}
// Select all registered contestants for the club
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsContestants = sprintf("SELECT co.contestant_id, co.contestant_name, co.contestant_birth, co.contestant_gender FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND co.account_id = %s ORDER BY co.contestant_name", GetSQLValueString($_SESSION['MM_AccountId'], "text"));
$rsContestants = mysql_query($query_rsContestants, $DBconnection) or die(mysql_error());
$row_rsContestants = mysql_fetch_assoc($rsContestants);
$totalRows_rsContestants = mysql_num_rows($rsContestants);
?>
        </div>
<form id="new_contestant" name="new_contestant" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="450" border="0">
        <tr>
          <td>T&auml;vlandes namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="30" value="<?php echo $insert_contestant_name; ?>"/>
          </label></td>
        </tr>
        <tr>
          <td>F&ouml;delsedatum (t.ex. 1996-01-31)</td>
          <td valign="top"><label>
            <input name="contestant_birth" type="text" id="contestant_birth" value="<?php echo $insert_contestant_birth; ?>" size="8" maxlength="10"/>
          </label></td>
        </tr>
        <tr>
          <td>K&ouml;n</td>
          <td valign="top">
            <label>
              <input name="contestant_gender" type="radio" id="contestant_gender" value="Man" <?php if ($insert_contestant_gender == "Man") echo "checked='checked'"; ?>//>
              Man</label>
            <label>
              <input type="radio" name="contestant_gender" id="contestant_gender" value="Kvinna" <?php if ($insert_contestant_gender == "Kvinna") echo "checked='checked'"; ?>/>
              Kvinna</label>
          </td>
        </tr>
        <tr>
          <td><input type="hidden" name="MM_insert_contestant" value="new_contestant" /></td>
          <td><label>
          <?php if ($passedDate == 0) { ?>
              <input type="submit" name="new_contestant" id="new_contestant" value="Ny t&auml;vlande" />
          <?php } ?>
          </label></td>
        </tr>
      </table>
</form>   
<?php        
        // Show if recordset not empty 
        if ($totalRows_rsContestants > 0) { ?>
        <div class="error">            
<?php
	// Validate the contestant form if the button is clicked
    	$contestant_height = "";
        $class_gender = "";
        $contestant_gender = "";
	if ((isset($_POST["MM_insert_registration"])) && ($_POST["MM_insert_registration"] == "new_registration")) {
        $colname_rsClass = $_POST['class'];
        //Select Class gender for selected class
        mysql_select_db($database_DBconnection, $DBconnection);
        $query_rsClassGender = sprintf("SELECT class_gender FROM classes WHERE class_id = %s", GetSQLValueString($colname_rsClass, "int"));
        $rsClassGender = mysql_query($query_rsClassGender, $DBconnection) or die(mysql_error());
        $row_rsClassGender = mysql_fetch_assoc($rsClassGender);            
	$contestant_height = $_POST['contestant_height'];
        $class_gender = $row_rsClassGender['class_gender'];
        $contestant_gender = $_POST['contestant_gender'];
        $output_form = 'no';
	
	echo '<br />';	
 
    	// Check if input is numeric, if $contestant_height is entered	
    	if (!empty($contestant_height)) {	
	
    		if (!ctype_digit($contestant_height)) {	
      		// contestant_height input is not numeric
      		echo '<h3>Bara siffror &auml;r till&aring;tet i f&auml;ltet f&ouml;r l&auml;ngd!</h3>';
      		$output_form = 'yes';
	    	}
    	}  
        if ($class_gender <> $contestant_gender ) {	
      	// Compare $contestant_gender with $class_gender
      	echo '<h3>T&auml;vlandes k&ouml;n st&auml;mmer inte &ouml;verens med den valda klassen!</h3>';
      	$output_form = 'yes';
	} 
		if ($output_form == 'no') {
                    // Search for start number already set and if not, set start number for the contestant
                    $colname_rsContestant = $_POST['contestant_id'];
                    echo "Contestant id: ".$colname_rsContestant;
                    mysql_select_db($database_DBconnection, $DBconnection);
                    $query_rsContestant_Startnumber = sprintf("SELECT re.contestant_startnumber FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1 AND contestant_id = %s", GetSQLValueString($colname_rsContestant, "int"));
                    $rsContestant_Startnumber = mysql_query($query_rsContestant_Startnumber, $DBconnection) or die(mysql_error());
                    $row_rsContestant_Startnumber = mysql_fetch_assoc($rsContestant_Startnumber);
                    $totalRows_rsContestant_Startnumber = mysql_num_rows($rsContestant_Startnumber);
                    if ($totalRows_rsContestant_Startnumber == 0) {
                        $contestant_startnumber = $_POST['contestant_startnumber']; 
                    }
                    else {
                        $contestant_startnumber = $row_rsContestant_Startnumber['contestant_startnumber'];
                    }   
  		$insertSQL = sprintf("INSERT INTO registration (club_reg_id, contestant_id, contestant_height, contestant_startnumber, class_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['club_reg_id'], "int"),
                       GetSQLValueString($_POST['contestant_id'], "int"),
                       GetSQLValueString($_POST['contestant_height'], "int"),					   
                       GetSQLValueString($contestant_startnumber, "int"),					                                                   
                       GetSQLValueString($_POST['class'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());

  		$insertGoTo = "RegInsert_reg.php#registration_delete";
		header(sprintf("Location: %s", $insertGoTo));
		}
        mysql_free_result($rsClassGender);       
	}
?>
        </div>                                    
<h3><a name="registration_insert" id="registration_insert"></a>3. Anm&auml;l till t&auml;vlingklasser</h3>
<p>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i (en klass i taget).<strong> F&ouml;r kumite och &aring;ldrarna 10-13 &aring;r: skriv i l&auml;ngduppgift!</strong> D&aring; kan vi ta beslut om eventuell uppdelning av klassen i "korta" och "l&aring;nga". Ta bort t&auml;vlande helt och h&aring;llet genom att klicka p&aring; l&auml;nken.
<?php //Show if the maximum number of registrations is reached
      if ($totalRows_rsCompActive > ($row_rsCompActive['comp_max_regs']-1)) { ?>
        <div class="error">
        <h3>Maximala antalet till&aring;tna anm&auml;lningar (<?php echo $totalRows_rsCompActive; ?> st.) &auml;r uppn&aring;tt och inga till&auml;gg g&aring;r att g&ouml;ra online! Kontakta t&auml;vlingsledningen vid akuta behov.</h3>
        </div>
<?php
        //Email to to Tuna Karate Cup Admin if the maximum number of registrations is reached
        $club_name = $row_rsClubReg['club_name'];
        $headers = "From: Tuna Karate Cup <tunacup@karateklubben.com>\r\n" .
        "MIME-Version: 1.0\r\n" . 
        'X-Mailer: PHP/' . phpversion() . "\r\n" .        
        "Content-Type: text/plain; charset=utf-8\r\n" . 
        "Content-Transfer-Encoding: 8bit\r\n\r\n";         
        $adm_email = "tunacup@karateklubben.com";
        $subject_adm = 'Max antal anmälningar registrerade på: http://tunacup.karateklubben.com';
	$text_adm = "Nu har det maximalt tillåtna antalet ($totalRows_rsCompActive st.) anmälningar registrerats på tunacup.karateklubben.com:\n" .
        "Sista anmälningen gjordes av $club_name.\n" .        
        "\n" .
	"Med vänliga hälsningar,\n" .
	"Eskilstuna Karateklubb, http://www.karateklubben.com";
        $msg_adm = "Max antal anmälningar registrerade!\n$text_adm";

        // Send email to Tuna Karate Cup Admin
        mail($adm_email, $subject_adm, $msg_adm, $headers);                

      } ?>           
</p>
      <table width="100%" border="1">
        <tr>
          <td><strong>T&auml;vlande - F&ouml;delsedatum - K&ouml;n - L&auml;ngd (eventuellt) - T&auml;vlingsklass</strong></td>
        </tr>
<?php do { ?>
          <tr>
            <td><form id="new_registration" name="new_registration" method="POST" action="<?php echo $editFormAction; ?>">
              <table>
                <tr>
                  <td><label>
                    <input type="text" name="contestant_name" id="contestant_name" value="<?php echo $row_rsContestants['contestant_name']; ?>" size="20"/>
                  </label></td>
                  <td><label>
                    <input name="contestant_birth" type="text" id="contestant_birth" value="<?php echo $row_rsContestants['contestant_birth']; ?>" size="6" maxlength="10"/>
                  </label></td>
                  <td><label>
                    <input name="contestant_gender" type="text" id="contestant_gender" value="<?php echo $row_rsContestants['contestant_gender']; ?>" size="2" />
                  </label></td>
                  <td><label>
                    <input name="contestant_height" type="text" id="contestant_height" size="1" maxlength="3"/>
                  </label></td>
                  <td nowrap="nowrap"><label>
                    <select name="class" id="class">
                      <?php
	do {  
?>
                      <?php
	} while ($row_rsClasses = mysql_fetch_assoc($rsClasses)) ;//($row_rsClasses['class_gender'] = $row_rsContestants['contestant_gender']);
	$rows = mysql_num_rows($rsClasses);
  		if($rows > 0) {
      	mysql_data_seek($rsClasses, 0);
	  	$row_rsClasses = mysql_fetch_assoc($rsClasses);
  		}
?>
                      <?php
	do {  
?>
                      <?php
	} while ($row_rsClasses = mysql_fetch_assoc($rsClasses));
  	$rows = mysql_num_rows($rsClasses);
  		if($rows > 0) {
      	mysql_data_seek($rsClasses, 0);
	  	$row_rsClasses = mysql_fetch_assoc($rsClasses);
  		}
?>
                      <?php
	do {  
?>
    <option value="<?php echo $row_rsClasses['class_id']?>"<?php if (!(strcmp($row_rsClasses['class_id'], $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_weight_length'].' | '.$row_rsClasses['class_age'].' &aring;r'))) {echo "selected=\"selected\"";} ?>>
<?php echo $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_category'].' | '; 
      if ($row_rsClasses['class_age'] == "") { 
          echo "";          
      } 
      if ($row_rsClasses['class_age'] <> "") { 
          echo $row_rsClasses['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsClasses['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsClasses['class_weight_length'] <> "-") {
         echo $row_rsClasses['class_weight_length']; 
      }
?></option>
                      <?php
	} while ($row_rsClasses = mysql_fetch_assoc($rsClasses));
  	$rows = mysql_num_rows($rsClasses);
  		if($rows > 0) {
      	mysql_data_seek($rsClasses, 0);
	  	$row_rsClasses = mysql_fetch_assoc($rsClasses);
  		}
?>
                   </select>
                </label></td>
                <td><label>
<?php
          //Show if the last date for registrations is NOT passed
          if ($passedDate == 0) { 
                //Show if the maximum number of registrations is NOT reached
                if ($totalRows_rsCompActive < ($row_rsCompActive['comp_max_regs'])) { ?>
                <input type="submit" name="new_registration" id="new_registration" value="Anm&auml;l till klass" />
    <?php       } 
          } ?>                  
                </label>
                </td>
                <td nowrap="nowrap">
                <?php if ($passedDate == 0) { ?>
                <a href="ContestantDelete_reg.php?contestant_id=<?php echo $row_rsContestants['contestant_id']; ?>">Ta bort</a>          
                <?php } ?>                  
                </td>
               </tr>
              </table>
    <input name="contestant_id" type="hidden" id="contestant_id" value="<?php echo $row_rsContestants['contestant_id']; ?>" />
    <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />
    <input name="contestant_startnumber" type="hidden" id="contestant_startnumber" value="<?php echo $contestant_startnumber; ?>" />                  
    <input type="hidden" name="MM_insert_registration" value="new_registration" />
    </form></td>
          </tr>
<?php } while ($row_rsContestants = mysql_fetch_assoc($rsContestants)); ?>
      </table>
<?php 
           // Show if recordset not empty 
            if ($totalRows_rsRegistrations > 0) { ?>
	<h3><a name="registration_delete" id="registration_delete"></a>4. Ta bort anm&auml;lningar</h3>
        <p>Om n&aring;got har blivit fel kan du ta bort anm&auml;lan. <strong>Du f&aring;r ingen bekr&auml;ftelse p&aring; anm&auml;lan, men kan se resultatet bl.a. under l&auml;nken "Startlistor"!</strong></p>
      <table width="100%" border="1">
        <tr>
          <td><strong>Startnr.</strong></td>            
          <td><strong>T&auml;vlande</strong></td>
          <td><strong>F&ouml;delsedatum</strong></td>
          <td><strong>L&auml;ngd (eventuellt)</strong></td>
          <td><strong>T&auml;vlingsklass</strong></td>
          <td><strong>Ta bort anm&auml;lan</strong></td>          
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>                            
            <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
            <td><?php echo $row_rsRegistrations['contestant_birth']; ?></td>
            <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
      <td><?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
      if ($row_rsRegistrations['class_age'] == "") { 
          echo "";          
      } 
      if ($row_rsRegistrations['class_age'] <> "") { 
          echo $row_rsRegistrations['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsRegistrations['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsRegistrations['class_weight_length'] <> "-") {
         echo $row_rsRegistrations['class_weight_length']; 
      }
      ?></td>
            <td nowrap="nowrap">
			<?php if ($passedDate == 0) { ?>
           	<a href="RegDelete_reg.php?reg_id=<?php echo $row_rsRegistrations['reg_id']; ?>">Ta bort</a>
          	<?php } ?>
            </td></tr>
          <?php } while ($row_rsRegistrations = mysql_fetch_assoc($rsRegistrations)); ?>
      </table>
<?php       mysql_free_result($rsRegistrations);
            // Show if rsRegistrations recordset not empty
            }
        mysql_free_result($rsContestants);
        // Show if recordset $totalRows_rsContestants not empty 
        }            
    mysql_free_result($rsClubReg);
    // Show if rsClubReg recordset not empty
    }
  // Show if last registration date is NOT passed
  }
mysql_free_result($rsMax_startnumber);   
mysql_free_result($rsClasses);
// If recordset rsClasses is NOT empty 
}
?>   
       </div>                     
</div>                 
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsCompActive);
ob_end_flush();
?>