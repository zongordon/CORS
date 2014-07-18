<?php
//Changed to $_SESSION['MM_Account'] for remembering the account_id
ob_start();

//Initiate global variables
global $row_rsSelectedClub, $totalRows_rsClubReg, $totalRows_rsSelectedClub, $passedDate;

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Registrera t&auml;vlande - admin";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Registrera tävlande - admin, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');  

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//Select the current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompActive = "SELECT comp_id, comp_end_reg_date FROM competition WHERE comp_current = 1";
$rsCompActive = mysql_query($query_rsCompActive, $DBconnection) or die(mysql_error());
$row_rsCompActive = mysql_fetch_assoc($rsCompActive);
//$totalRows_rsCompActive = mysql_num_rows($rsCompActive);

//Select all active accounts
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccounts = "SELECT account_id, club_name, active FROM account WHERE active = 1 ORDER BY club_name ASC";
$rsAccounts = mysql_query($query_rsAccounts, $DBconnection) or die(mysql_error());
$row_rsAccounts = mysql_fetch_assoc($rsAccounts);
//$totalRows_rsAccounts = mysql_num_rows($rsAccounts);

//Select information regarding the selected account
$colname_rsSelectedClub = $_SESSION['MM_Account'];

    if (isset($_POST['account_id'])) {
    $colname_rsSelectedClub = $_POST['account_id'];    
    }
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsSelectedClub = sprintf("SELECT account_id, club_name, active FROM account WHERE account_id = %s", GetSQLValueString($colname_rsSelectedClub, "int"));
$rsSelectedClub = mysql_query($query_rsSelectedClub, $DBconnection) or die(mysql_error());
$row_rsSelectedClub = mysql_fetch_assoc($rsSelectedClub);
$totalRows_rsSelectedClub = mysql_num_rows($rsSelectedClub);     

//Creating Session variable for the selected club
$_SESSION['MM_Account'] = $row_rsSelectedClub['account_id'];
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">  
     <div class="feature">
<h3>Registera t&auml;vlande klubbmedlemmar och anm&auml;l dem till deras t&auml;vlingsklasser</h3>
<p>Anm&auml;lan g&ouml;rs i fem steg:</p>
<ol>
  <li>V&auml;lj klubb</li>
  <li>Skriv in namnen p&aring; de coacher som ska st&ouml;tta klubbens t&auml;vlande.</li>
  <li>L&auml;gg in klubbens t&auml;vlande en och en. De l&auml;ggs till i listan under formul&auml;ret, allt eftersom de l&auml;ggs in. </li>
  <li>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i.</li>
  <li>Alla t&auml;vlingsanm&auml;lningar listas l&auml;ngst ned p&aring; sidan, s&aring; att du kan  ta bort dem om n&aring;got har blivit fel.</li>
</ol>
<h3>1. V&auml;lj klubb</h3>
<p><?php if (empty($_SESSION['MM_Account'])) {
 echo "<strong>Ingen klubb &auml;r vald &auml;n! </strong>";    
} ?>
V&auml;l klubb och klicka p&aring; V&auml;lj!</p>
<form id="SelectClub" name="SelectClub" method="post" action="<?php echo $editFormAction; ?>">
  <table width="200" border="0">
    <tr>
      <td valign="top">Klubb</td>
      <td><label>
        <select name="account_id" id="account_id">
          <?php
do {  
?>
          <option value="<?php echo $row_rsAccounts['account_id']?>"<?php if (!(strcmp($row_rsAccounts['account_id'], $_SESSION['MM_Account']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAccounts['club_name']?></option>
          <?php
} while ($row_rsAccounts = mysql_fetch_assoc($rsAccounts));
  $rows = mysql_num_rows($rsAccounts);
  if($rows > 0) {
      mysql_data_seek($rsAccounts, 0);
	  $row_rsAccounts = mysql_fetch_assoc($rsAccounts);
  }
?>
        </select>
      </label></td>
      <td><input type="submit" name="submit" id="submit" value="V&auml;lj klubb" /></td>
    </tr>
  </table>
</form>
<?php
//Show club registration form if a club is selected and "Välj" button is clicked 
if ($totalRows_rsSelectedClub <> "") { // Do not show if recordset empty
?>
<h3>2. Skriv in klubbens coacher</h3>
<p>Skriv in namnen p&aring; de coacher som ska st&ouml;tta era t&auml;vlande och klicka p&aring; spara.</p>
     <div class="error">
<?php
// Validate the club registration form if the "Spara" or "Uppdatera" button is clicked
if (((isset($_POST["MM_insert_clubregistration"])) && ($_POST["MM_insert_clubregistration"] == "new_club_reg") || (isset($_POST["MM_update_clubregistration"])) && ($_POST["MM_update_clubregistration"] == "update_club_reg"))) {
    $coach_names = encodeToISO(mb_convert_case($_POST['coach_names'], MB_CASE_TITLE,"ISO-8859-1"));
    $output_form = 'no';
            
    if (empty($coach_names)) {
      // $coach_names is blank
      echo '<h3>Du gl&ouml;mde att fylla i klubbens coacher!</h1>';
      $output_form = 'yes';
    }
	if ($output_form == 'no') {		
		if ((isset($_POST["MM_insert_clubregistration"])) && ($_POST["MM_insert_clubregistration"] == "new_club_reg")) {
  		$insertSQL = sprintf("INSERT INTO clubregistration (coach_names, account_id, comp_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($coach_names, "text"),
                       GetSQLValueString($_POST['account_id'], "int"),
                       GetSQLValueString($_POST['comp_id'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
		}
  		if ((isset($_POST["MM_update_clubregistration"])) && ($_POST["MM_update_clubregistration"] == "update_club_reg")) {
  		$updateSQL = sprintf("UPDATE clubregistration SET coach_names=%s WHERE club_reg_id=%s",
                       GetSQLValueString($_POST['coach_names'], "text"),
                       GetSQLValueString($_POST['club_reg_id'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
		}
 	}
}
// Select the names of the registered coaches	
$colname_rsClubReg = $row_rsSelectedClub['account_id'];
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClubReg = sprintf("SELECT a.account_id, a.club_name, c.club_reg_id, c.coach_names, c.club_startorder FROM clubregistration AS c INNER JOIN competition AS com USING (comp_id) INNER JOIN account AS a USING (account_id) WHERE comp_current = 1 AND account_id = %s", GetSQLValueString($colname_rsClubReg, "int"));
$rsClubReg = mysql_query($query_rsClubReg, $DBconnection) or die(mysql_error());
$row_rsClubReg = mysql_fetch_assoc($rsClubReg);
$totalRows_rsClubReg = mysql_num_rows($rsClubReg);

 	if ($totalRows_rsClubReg == 0) { // Show if recordset empty ?>     
    <form id="new_club_reg" name="new_club_reg" method="POST" action="<?php echo $editFormAction; ?>">
<?php
	}?> 
<?php if ($totalRows_rsClubReg <> 0) { // Show if recordset NOT empty ?>
    <form id="update_club_reg" name="update_club_reg" method="POST" action="<?php echo $editFormAction; ?>"> 
<?php
	}?>
     </div>                       
     <table width="400" border="0">
      <tr>
        <td valign="top">Coacher</td>
        <td><label>
          <input name="coach_names" type="text" id="coach_names" value="<?php echo $row_rsClubReg['coach_names']; ?>" size="55" /></label></td>
      </tr>
      <tr>
        <td>
          <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsCompActive['comp_id']; ?>" />
          <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />        </td>
        <td><label>
	<?php if ($totalRows_rsClubReg == 0) { // Show if recordset empty ?>   
          <input type="submit" name="new_club_reg" id="new_club_reg" value="Spara" />
          <input type="hidden" name="MM_insert_clubregistration" value="new_club_reg" />
	<?php } ?> 
	  	<?php if ($totalRows_rsClubReg <> 0) { // Show if recordset NOT empty ?>
          <input type="submit" name="update_club_reg" id="update_club_reg" value="Uppdatera" /></label>
          <input type="hidden" name="MM_update_clubregistration" value="update_club_reg" /> 
		<?php } ?>         
        </label></td>
      </tr>
    </table>      
</form>
 <?php   
 }
 ?>
<?php if ($totalRows_rsClubReg > 0) { // Show if recordset not empty ?>
<h3><a name="contestants" id="contestants"></a>3. L&auml;gg in klubbens t&auml;vlande</h3>
<p>L&auml;gg in klubbens t&auml;vlande en och en. Ange namn, f&ouml;delsedatum och k&ouml;n.</p>
<div class="error">
<?php 
//Validate the form input when button is clicked
    $insert_contestant_name = "";
    $insert_contestant_birth = "";
    $insert_contestant_gender = "";
// Validate the contestant form if the button is clicked	
if (((isset($_POST["MM_insert_contestant"])) && ($_POST["MM_insert_contestant"] == "new_contestant"))) {
    $insert_contestant_name = encodeToISO(mb_convert_case($_POST['contestant_name'], MB_CASE_TITLE,"ISO-8859-1"));    
    $insert_contestant_birth = $_POST['contestant_birth'];
    $insert_contestant_gender = $_POST['contestant_gender'];
    $output_form = 'no';
	
    if (empty($insert_contestant_name)) {
      // $insert_contestant_name is blank
      echo '<h3>Du gl&ouml;mde att fylla i namn!</h1>';
      $output_form = 'yes';
    }
    if (empty($insert_contestant_birth)) {
      // $insert_contestant_birth is blank
      echo '<h3>Du gl&ouml;mde att fylla i f&ouml;delsedatum!</h1>';
      $output_form = 'yes';
	}
    if (!empty($insert_contestant_birth) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $insert_contestant_birth)) {
    // $insert_contestant_birth is wrong format
    echo '<h3>Du anv&auml;nde fel format p&aring; f&ouml;delsedatum!</h1>';
    $output_form = 'yes';
    }	
    if (empty($insert_contestant_gender)) {	
      // $insert_contestant_gender is blank
      echo '<h3>Du gl&ouml;mde att v&auml;lja k&ouml;n.</h1>';
      $output_form = 'yes';
    }

	if ($output_form == 'no') {		
		// Insert new contestant if the button is clicked and the form is validated
  		$insertSQL = sprintf("INSERT INTO contestants (contestant_name, contestant_birth, contestant_gender, account_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($insert_contestant_name, "text"),
                       GetSQLValueString($_POST['contestant_birth'], "date"),
                       GetSQLValueString($_POST['contestant_gender'], "text"),
                       GetSQLValueString($_POST['account_id'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
		
  		$insertGoTo = "RegsHandleAll.php#registration_insert";
		header(sprintf("Location: %s", $insertGoTo));  		
	}	
}
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
          <td>
        <input type="hidden" name="MM_insert_contestant" value="new_contestant" />
        <input type="hidden" name="account_id" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
          </td>
          <td><label>
              <input type="submit" name="new_contestant" id="new_contestant" value="Ny t&auml;vlande" />
          </label></td>
        </tr>
      </table>
    </form>   
    <div class="error">
<?php
// Select all registered contestants for the club
$colname_rsContestants = $_SESSION['MM_Account'];
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsContestants = sprintf("SELECT co.contestant_id, co.contestant_name, co.contestant_birth, co.contestant_gender FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND co.account_id = %s ORDER BY co.contestant_name", GetSQLValueString($colname_rsContestants, "text"));
$rsContestants = mysql_query($query_rsContestants, $DBconnection) or die(mysql_error());
$row_rsContestants = mysql_fetch_assoc($rsContestants);
$totalRows_rsContestants = mysql_num_rows($rsContestants);

if ($totalRows_rsContestants > 0) { // Show if recordset not empty
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
		// Insert new registration if the button is clicked and the form is validated
  		$insertSQL = sprintf("INSERT INTO registration (club_reg_id, contestant_id, contestant_height, class_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['club_reg_id'], "int"),
                       GetSQLValueString($_POST['contestant_id'], "int"),
                       GetSQLValueString($_POST['contestant_height'], "int"),					   
                       GetSQLValueString($_POST['class'], "int"));

  		mysql_select_db($database_DBconnection, $DBconnection);
  		$Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());

  		$insertGoTo = "RegsHandleAll.php#registration_delete";
		header(sprintf("Location: %s", $insertGoTo));  
		}
        mysql_free_result($rsClassGender);        
	}	
// Select all classes for the current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = "SELECT cl.class_id, cl.comp_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, cl.class_gender_category";
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$row_rsClasses = mysql_fetch_assoc($rsClasses);
?>
        </div>    
<h3><a name="registration_insert" id="registration_insert"></a>4. Anm&auml;l till t&auml;vlingklasser</h3>
<p>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i (en klass i taget). <strong> F&ouml;r kumite och &aring;ldrarna 10-13 &aring;r: skriv i l&auml;ngduppgift!</strong> D&aring; kan vi ta beslut om eventuell uppdelning av klassen i "korta" och "l&aring;nga". Ta bort t&auml;vlande helt och h&aring;llet genom att klicka p&aring; l&auml;nken.</p>
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
                    <input name="contestant_height" type="text" id="contestant_height" size="1" maxlength="3" />
                  </label></td>
                  <td nowrap="nowrap"><label>
                    <select name="class" id="class">
                      <?php
do {  
?>
                      <?php
} while ($row_rsClasses = mysql_fetch_assoc($rsClasses)) ;
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
          <?php if ($passedDate == 0) { ?>
          <input type="submit" name="new_registration" id="new_registration" value="Anm&auml;l till klass" />
          <?php } ?>                  
                  </label></td>
                <td nowrap="nowrap">
          <?php if ($passedDate == 0) { ?>
          <a href="ContestantDelete.php?contestant_id=<?php echo $row_rsContestants['contestant_id']; ?>">Ta bort</a>          
		  <?php } ?>                  
				</td>
                </tr>
              </table>
              <input name="contestant_id" type="hidden" id="contestant_id" value="<?php echo $row_rsContestants['contestant_id']; ?>" />
              <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />
              <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Account']; ?>" />
              <input type="hidden" name="MM_insert_registration" value="new_registration" />
            </form></td>
          </tr>
          <?php } while ($row_rsContestants = mysql_fetch_assoc($rsContestants)); ?>
      </table>
<?php mysql_free_result($rsContestants);
} // Show if recordset not empty 
    //Select the contestants and their information for the selected class
    $colname_rsRegistrations = $_SESSION['MM_Account'];
    mysql_select_db($database_DBconnection, $DBconnection);
    $query_rsRegistrations = sprintf("SELECT re.reg_id, re.contestant_height, co.contestant_name, co.contestant_birth, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition AS com USING (comp_id) WHERE account_id = %s AND comp_current = 1 ORDER BY co.contestant_name", GetSQLValueString($colname_rsRegistrations, "int"));
    $rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
    $row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
    $totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);

    if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>
    <h3><a name="registration_delete" id="registration_delete"></a>5. Ta bort anm&auml;lningar</h3>
    <p>Om n&aring;got har blivit fel kan du ta bort anm&auml;lan.</p>
      <table width="100%" border="1">
        <tr>
          <td><strong>T&auml;vlande</strong></td>
          <td><strong>F&ouml;delsedatum</strong></td>
          <td><strong>L&auml;ngd (eventuellt)</strong></td>
          <td><strong>T&auml;vlingsklass</strong></td>
          <td><strong>Ta bort anm&auml;lan</strong></td>          
        </tr>
        <?php do { ?>
          <tr>
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
           	<a href="RegDelete.php?reg_id=<?php echo $row_rsRegistrations['reg_id']; ?>">Ta bort</a>
          	<?php } ?>
            </td></tr>
          <?php } while ($row_rsRegistrations = mysql_fetch_assoc($rsRegistrations)); ?>
      </table>
<?php mysql_free_result($rsRegistrations);
    } // Show if recordset not empty 
mysql_free_result($rsClubReg);
mysql_free_result($rsClasses);
mysql_free_result($rsSelectedClub); 
 }
mysql_free_result($rsAccounts);
mysql_free_result($rsCompActive);  
?>    
     </div>
</div>    
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>