<?php
//Added validation class with multiple validation features and removed most of existing validation code

ob_start();
session_start();

//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

//Handle input from form
$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Catch anything wrong with query
try {
// Select number of classes including last date for registrations, for the active competition
require('Connections/DBconnection.php');           
$query_rsClasses = "SELECT COUNT(class_id) FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1";
$stmt_rsClasses = $DBconnection->query($query_rsClasses);
$row_rsClasses = $stmt_rsClasses->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Get account_id for logged-in user
$account_id = $_SESSION['MM_AccountId'];

$pagetitle="Registrera egna t&auml;vlande";
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
<div class ="content">    
       <div class="feature">    
<?php 
//Setting the date for today (including format), last enrolment date and check if the last enrolment date is passed or not
$now = date('Y-m-d');
$endEnrolmentDate = $comp_end_reg_date;
$passedDate = 0;
if ($endEnrolmentDate < $now) {
	$passedDate = 1;
}
// Show if recordset (classes) rsClasses empty 
if ($row_rsClasses['COUNT(class_id)'] === 0) {?>
    <p>Det finns inga klasser att anm&auml;la till &auml;n!</p>
<?php
}
// If recordset (classes) rsClasses is NOT empty 
if ($row_rsClasses['COUNT(class_id)'] > 0) {

    //Show if the last date for registration is passed
    if ($passedDate === 1) { ?>
	<div class="error">
        <h3>Sista anm&auml;lningsdagen &auml;r passerad och inga till&auml;gg eller &auml;ndringar g&aring;r att g&ouml;ra online! Kontakta t&auml;vlingsledningen vid akuta behov.</h3>
	</div>
<?php
    }
  //Show if the last date for registration is NOT passed
  if ($passedDate === 0) { ?> 
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
<?php 
// Validate the club registration form if the "Spara" button is clicked
    $coach_names = '';
if ((filter_input(INPUT_POST,"MM_insert_clubregistration") === "new_club_reg") || (filter_input(INPUT_POST,"MM_update_clubregistration") === "update_club_reg")) {
    $coach_names = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'coach_names'), MB_CASE_TITLE,"UTF-8"));

    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('klubbens coacher')->value($coach_names)->pattern('text')->required()->min($length);
    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }
    if ($output_form === 'no') {
        //Insert new club registration if form validated ok
	if (filter_input(INPUT_POST,"MM_insert_clubregistration") === "new_club_reg") {
            //Catch anything wrong with query
            try {
            //INSERT coaches
            require('Connections/DBconnection.php');         
            $insertSQL = "INSERT INTO clubregistration (coach_names, account_id, comp_id) VALUES (:coach_names, :account_id, :comp_id)";
            $stmt = $DBconnection->prepare($insertSQL);
            $stmt->bindValue(':coach_names', $coach_names, PDO::PARAM_STR);
            $stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
            $stmt->bindValue(':comp_id', $comp_id, PDO::PARAM_INT);
            $stmt->execute();
            }   catch(PDOException $ex) {
                echo "An Error occured with queryX: ".$ex->getMessage();
                }            
        }
        //Update club registration if button is clicked            
	if (filter_input(INPUT_POST,"MM_update_clubregistration") === "update_club_reg") {
            $club_reg_id = filter_input(INPUT_POST,"club_reg_id");
            //Catch anything wrong with query
            try {
            //UPDATE coaches
            require('Connections/DBconnection.php');                     
            $updateSQL = "UPDATE clubregistration SET coach_names = :coach_names WHERE club_reg_id = :club_reg_id";
            $stmt = $DBconnection->prepare($updateSQL);
            $stmt->bindValue(':coach_names', $coach_names, PDO::PARAM_STR);
            $stmt->bindValue(':club_reg_id', $club_reg_id, PDO::PARAM_INT);
            $stmt->execute();
            }   catch(PDOException $ex) {
                echo "An Error occured with queryX: ".$ex->getMessage();
                }            
        }
    //Kill statement
    $stmt->closeCursor();
    }
}

//Catch anything wrong with query
try {
//SELECT registered club coaches 
require('Connections/DBconnection.php');         
$query_rsClubReg = "SELECT a.club_name, cl.club_reg_id, cl.coach_names FROM clubregistration AS cl INNER JOIN competition AS co USING (comp_id) INNER JOIN account AS a USING (account_id) WHERE account_id = :account_id AND comp_current = 1";
$stmt_rsClubReg = $DBconnection->prepare($query_rsClubReg);
$stmt_rsClubReg->execute(array(':account_id'=>$account_id));
$row_rsClubReg = $stmt_rsClubReg->fetch(PDO::FETCH_ASSOC);
$totalRows_rsClubReg = $stmt_rsClubReg->rowCount();
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
    // Show if recordset empty 
    if ($totalRows_rsClubReg === 0) {?>     
    <form id="new_club_reg" name="new_club_reg" method="POST" action="<?php echo $editFormAction; ?>">
<?php
    } 
    // Show if recordset NOT empty         
    if ($totalRows_rsClubReg <> 0) {?>
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
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $comp_id; ?>" />
          <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />        </td>
        <td><label>
	<?php if ($totalRows_rsClubReg === 0) { // Show if recordset empty ?>   
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
<?php // Show if recordset of club registrations is not empty 
    if ($totalRows_rsClubReg > 0) {?>
      <h3><a name="contestants" id="contestants"></a>2. L&auml;gg in klubbens t&auml;vlande</h3>
      <p>L&auml;gg in klubbens t&auml;vlande en och en. Ange namn, f&ouml;delsedatum och k&ouml;n.</p>
        <div class="error">
<?php 
//Declare and initialise variables
    $insert_contestant_name = '';$insert_contestant_birth = '';$insert_contestant_gender = '';
// Validate the contestant input if the button is clicked	
if (filter_input(INPUT_POST,"MM_insert_contestant") === "new_contestant") {
    $insert_contestant_name = encodeToUtf8(mb_convert_case(filter_input(INPUT_POST,'contestant_name'), MB_CASE_TITLE,"UTF-8"));    
    $insert_contestant_birth = filter_input(INPUT_POST,'contestant_birth');
    $insert_contestant_gender = filter_input(INPUT_POST,'contestant_gender');

    $val = new Validation();
    $length = 5;//min length of strings
    $val->name('namn')->value($insert_contestant_name)->pattern('text')->required()->min($length);
    $val->name('f&ouml;delsedatum')->value($insert_contestant_birth)->datePattern('Y-m-d')->required();
    $val->name('k&ouml;n')->value($insert_contestant_gender)->pattern('text')->required();
	        
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    } 
    if ($output_form === 'no') {		
        //Catch anything wrong with query
        try {
        //INSERT new contestants
        require('Connections/DBconnection.php');         
        $insertSQL = "INSERT INTO contestants (contestant_name, contestant_birth, contestant_gender, account_id) VALUES (:contestant_name, :contestant_birth, :contestant_gender, :account_id)";
        $stmt = $DBconnection->prepare($insertSQL);
        $stmt->bindValue(':contestant_name', $insert_contestant_name, PDO::PARAM_STR);
        $stmt->bindValue(':contestant_birth', $insert_contestant_birth, PDO::PARAM_STR);
        $stmt->bindValue(':contestant_gender', $insert_contestant_gender, PDO::PARAM_STR);
        $stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->execute();
        }   catch(PDOException $ex) {
            echo "An Error occured with queryX: ".$ex->getMessage();
            }         
  	$insertGoTo = "RegInsert_reg.php#registration_insert";
	header(sprintf("Location: %s", $insertGoTo));  			
    }
}
//Catch anything wrong with query
try {
// Select all registered contestants for the club
require('Connections/DBconnection.php');           
$query_rsContestants = "SELECT co.contestant_id, co.contestant_name, co.contestant_birth, co.contestant_gender FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND co.account_id = :account_id ORDER BY co.contestant_name";
$stmt_rsContestants = $DBconnection->prepare($query_rsContestants);
$stmt_rsContestants ->execute(array(':account_id'=>$account_id));
$totalRows_rsContestants = $stmt_rsContestants->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
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
          <td>F&ouml;delsedatum (yyyy-mm-dd)</td>
          <td valign="top"><label>
            <input name="contestant_birth" type="text" id="contestant_birth" value="<?php echo $insert_contestant_birth; ?>" size="8" maxlength="10"/>
          </label></td>
        </tr>
        <tr>
          <td>K&ouml;n</td>
          <td valign="top">
            <label>
              <input name="contestant_gender" type="radio" id="contestant_gender" value="Man" <?php if ($insert_contestant_gender === "Man") { echo "checked='checked'";} ?>/>
              Man</label>
            <label>
              <input type="radio" name="contestant_gender" id="contestant_gender" value="Kvinna" <?php if ($insert_contestant_gender === "Kvinna") { echo "checked='checked'";} ?>/>
              Kvinna</label>
          </td>
        </tr>
        <tr>
          <td><input type="hidden" name="MM_insert_contestant" value="new_contestant" /></td>
          <td><label>
          <?php if ($passedDate === 0) { ?>
              <input type="submit" name="new_contestant" id="new_contestant" value="Ny t&auml;vlande" />
          <?php } ?>
          </label></td>
        </tr>
      </table>
</form>   
        <div class="error">            
<?php       
// Show if recordset not empty 
if ($totalRows_rsContestants > 0) {
    //Declare and initialise variables
    $contestant_height = '';$contestant_gender = '';$class_gender ='';
  // Validate the contestant form if the button is clicked  
  if (filter_input(INPUT_POST,"MM_insert_registration") === "new_registration") {
        $colname_rsClass = filter_input(INPUT_POST,'class_id');
        $contestant_height = filter_input(INPUT_POST,'contestant_height');
        $contestant_gender = filter_input(INPUT_POST,'contestant_gender');    
        $club_reg_id = filter_input(INPUT_POST,'club_reg_id');    
        $contestant_id = filter_input(INPUT_POST,'contestant_id');    
        $class_gender = filter_input(INPUT_POST,'class_gender');

    $val = new Validation();
    if(empty($contestant_height)){
        $contestant_height = 0;
        $val->name('l&auml;ngd')->value($contestant_height)->pattern('int');
    }else{    
    $min = 100;//minimum value of integers
    $max = 225;//maximum value of integers
    $val->name('l&auml;ngd')->value($contestant_height)->valuePattern($min,$max);
    }    
    //If validation succeeds set flag for entering data and show no form else show all errors and show form again      
    if($val->isSuccess()){
    	$output_form = 'no';
    }else{
        foreach($val->getErrors() as $error) {
        echo '<h3>'.$error.'</h3></br>';
        }
        $output_form = 'yes';
    }    
    if ($output_form === 'no') {
                
        // Search for start number already set for the competition and if not, set start number for the contestant
        $colname_rsContestant = filter_input(INPUT_POST,'contestant_id');
        
        //Catch anything wrong with query
        try {
        //Search for start number already set for the contestant
        require('Connections/DBconnection.php');         
        $query_rsContestant_Startnumber = "SELECT re.contestant_startnumber FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1 AND contestant_id = :contestant_id";
        $stmt_rsContestant_Startnumber = $DBconnection->prepare($query_rsContestant_Startnumber);
        $stmt_rsContestant_Startnumber->execute(array(':contestant_id'=>$colname_rsContestant));
        $row_rsContestant_Startnumber = $stmt_rsContestant_Startnumber->fetch(PDO::FETCH_ASSOC);
        }   catch(PDOException $ex) {
            echo "An Error occured with queryX: ".$ex->getMessage();
            }        
        if ($row_rsContestant_Startnumber['contestant_startnumber'] === NULL) {
            $contestant_startnumber = filter_input(INPUT_POST,'contestant_startnumber'); 
        } 
        else {
            $contestant_startnumber = $row_rsContestant_Startnumber['contestant_startnumber'];
        }   
        //Catch anything wrong with query
        try {
        //INSERT new registration if the button is clicked and the form is validated
        require('Connections/DBconnection.php');         
        $insertSQL = "INSERT INTO registration (club_reg_id, contestant_id, contestant_height, contestant_startnumber, class_id) VALUES (:club_reg_id, :contestant_id, :contestant_height, :contestant_startnumber, :class_id)";
        $stmt = $DBconnection->prepare($insertSQL);
        $stmt->bindValue(':club_reg_id', $club_reg_id, PDO::PARAM_INT);
        $stmt->bindValue(':contestant_id', $contestant_id, PDO::PARAM_INT);
        $stmt->bindValue(':contestant_height', $contestant_height, PDO::PARAM_INT);
        $stmt->bindValue(':contestant_startnumber', $contestant_startnumber, PDO::PARAM_INT);
        $stmt->bindValue(':class_id', $colname_rsClass, PDO::PARAM_INT);
        $stmt->execute();
        }   catch(PDOException $ex) {
            echo "An Error occured with queryX: ".$ex->getMessage();
            }           

  	$insertGoTo = "RegInsert_reg.php#registration_delete";
	header(sprintf("Location: %s", $insertGoTo));
                
        //Kill statement
        $stmt->closeCursor();
    }
  }

//Catch anything wrong with query
try {
//Select actual number of registrations for the current competition
require('Connections/DBconnection.php');           
$query_rsCurrRegs = "SELECT COUNT(reg_id) AS max_regs FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN competition as com USING (comp_id) WHERE comp_current = 1";
$stmt_rsCurrRegs = $DBconnection->query($query_rsCurrRegs);
$row_rsCurrRegs = $stmt_rsCurrRegs->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
    
//Catch anything wrong with query
try {
// Select the currently highest start number
require('Connections/DBconnection.php');           
$query_rsMax_startnumber = "SELECT MAX(contestant_startnumber)AS max_startnumber FROM registration INNER JOIN classes AS cl USING (class_id) JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1";
$stmt_rsMax_startnumber = $DBconnection->query($query_rsMax_startnumber);
$row_rsMax_startnumber = $stmt_rsMax_startnumber->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

// Set a new start number (starting with 100)
$max_startnumber = $row_rsMax_startnumber['max_startnumber'];
if ($max_startnumber < 100){
    $contestant_startnumber = 100;
}
else {
    $contestant_startnumber = $max_startnumber + 1;
}
?>
        </div>                                    
<h3><a name="registration_insert" id="registration_insert"></a>3. Anm&auml;l till t&auml;vlingklasser</h3>
<p>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i 
    (en klass i taget).<strong> F&ouml;r kumite och &aring;ldrarna 7-13 &aring;r: skriv i l&auml;ngduppgift!
    </strong> D&aring; kan vi ta beslut om eventuell uppdelning av klassen i "korta" och "l&aring;nga". 
    G&ouml;r &auml;ndringar eller ta bort t&auml;vlande helt och h&aring;llet genom att klicka p&aring; n&aring;gon av l&auml;nkarna.
<?php 
//Show if the maximum number of registrations is reached
if ($row_rsCurrRegs['max_regs'] === $comp_max_regs) { ?>
    <div class="error">
    <h3>Maximala antalet till&aring;tna anm&auml;lningar (<?php echo $comp_max_regs ?> st.) &auml;r uppn&aring;tt och inga till&auml;gg g&aring;r att g&ouml;ra online! Kontakta t&auml;vlingsledningen vid akuta behov.</h3>
    </div>
<?php
    //Email to competition admin if the maximum number of registrations is reached
    $club_name = $row_rsClubReg['club_name'];
    $headers = "From: $comp_name <$comp_email>\r\n" .
    "MIME-Version: 1.0\r\n" . 
    'X-Mailer: PHP/' . phpversion() . "\r\n" .        
    "Content-Type: text/plain; charset=utf-8\r\n" . 
    "Content-Transfer-Encoding: 8bit\r\n\r\n";         
    $adm_email = "$comp_email";
    $subject_adm = 'Max antal anmälningar registrerade på: '.$comp_name;
    $text_adm = "Nu har det maximalt tillåtna antalet ($comp_max_regs st.) anmälningar registrerats på $comp_name:\n" .
    "Sista anmälningen gjordes av $club_name.\n" .        
    "\n" .
    "Med vänliga hälsningar,\n" .
    "$comp_arranger, $comp_name, $comp_email";
    $msg_adm = "Max antal anmälningar registrerade!\n$text_adm";

    // Send email to competition admin
    mail($adm_email, $subject_adm, $msg_adm, $headers);                
} 
?>           
</p>
      <table width="800" border="1">
        <tr>
          <td><strong>T&auml;vlande - F&ouml;delsedatum - K&ouml;n - L&auml;ngd (eventuellt) - T&auml;vlingsklass</strong></td>
        </tr>
<?php while($row_rsContestants = $stmt_rsContestants->fetch(PDO::FETCH_ASSOC)) { 
    $contestant_gender = $row_rsContestants['contestant_gender'];
    //Calculate the age of the contestant at the date of the competition
    $date1 = new DateTime($comp_start_date);
    $date2 = new DateTime($row_rsContestants['contestant_birth']);
    $diff = $date2->diff($date1);
    $contestant_age = $diff->y;
    //If age > 18, set to 18 to match with classes
    if($contestant_age >18){
      $contestant_age = 18;
    }
    //If age < 10, add a "0" to be able to match with classes
    if($contestant_age <10){
      $contestant_age = '0'.$contestant_age;
    }

    //Catch anything wrong with query
    try {
// Select classes applicable for the contestant
    require('Connections/DBconnection.php');               
    $query_rsClassData = 
            "SELECT cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, "
            . "cl.class_gender_category, cl.class_weight_length, cl.class_age "
            . "FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id "
            . "WHERE "
            . "comp_current = 1 && cl.class_gender = :contestant_gender && SUBSTRING(cl.class_age, 1, 2) = :contestant_age "
            . "|| comp_current = 1 && cl.class_gender = :contestantgender && SUBSTRING(cl.class_age, 4, 2) = :contestantage "
            . "ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, cl.class_gender_category"; 
    $stmt_rsClassData = $DBconnection->prepare($query_rsClassData);
    $stmt_rsClassData->execute(array(':contestant_gender'=>$contestant_gender, ':contestant_age'=>$contestant_age, ':contestantgender'=>$contestant_gender, ':contestantage'=>$contestant_age));
    $row_rsClassData = $stmt_rsClassData->fetchAll(PDO::FETCH_ASSOC);   
    } catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
      }        
?>
          <tr>
            <td><form id="new_registration" name="new_registration" method="POST" action="<?php echo $editFormAction; ?>">
              <table>
                <tr>
                  <td><label>
                    <input type="text" name="contestant_name" id="contestant_name" value="<?php echo $row_rsContestants['contestant_name']; ?>" size="20"/>
                  </label></td>
                  <td><label>
                    <input name="contestant_birth" type="text" id="contestant_birth" value="<?php echo $row_rsContestants['contestant_birth']; ?>" size="8" maxlength="10"/>
                  </label></td>
                  <td><label>
                    <input name="contestant_gender" type="text" id="contestant_gender" value="<?php echo $row_rsContestants['contestant_gender']; ?>" size="4" />
                  </label></td>
                  <td><label>
                    <input name="contestant_height" type="text" id="contestant_height" size="1" maxlength="3"/>
                  </label></td>
                  <td nowrap="nowrap"><label>
                    <select name="class_id" id="class_id">
<?php
    foreach($row_rsClassData as $row_rsClasses) {
?>
      <option value="<?php echo $row_rsClasses['class_id']?>">
<?php echo $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_category'].' | '; 
      if ($row_rsClasses['class_age'] === "") { 
          echo "";          
      } 
      if ($row_rsClasses['class_age'] <> "") { 
          echo $row_rsClasses['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsClasses['class_weight_length'] === "-") {
          echo "";                    
      }
      if ($row_rsClasses['class_weight_length'] <> "-") {
         echo $row_rsClasses['class_weight_length']; 
      } ?>
      </option>
<?php
    //Kill statement
    $stmt_rsClassData->closeCursor();
    $stmt_rsCurrRegs->closeCursor();    
    }   
?>
                   </select>
                </label></td>
                <td><label>
<?php
          //Show if the last date for registrations is NOT passed
          if ($passedDate === 0) { 
                //Show if the maximum number of registrations is NOT reached
                if ($row_rsCurrRegs['max_regs'] < $comp_max_regs) { ?>
                <input type="submit" name="new_registration" id="new_registration" value="Anm&auml;l till klass" />
<?php           } 
          } 
?>                  
                </label>
                </td>
                <td nowrap="nowrap">
                <?php if ($passedDate === 0) { ?>    
                    <a href="ContestantUpdate_reg.php?contestant_id=<?php echo $row_rsContestants['contestant_id']; ?>">&Auml;ndra</a> |                  
                <?php } ?>    
                </td>  
                <td nowrap="nowrap">
                <?php if ($passedDate === 0) { ?>
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
<?php } ?>
      </table>
<?php 
//Catch anything wrong with query
try {
// Select the contestants and their information for the selected class
require('Connections/DBconnection.php');           
$query_rsRegistrations = "SELECT re.reg_id, re.contestant_height, re.contestant_startnumber, co.contestant_name, co.contestant_birth, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE account_id = :account_id AND comp_id = :comp_id ORDER BY co.contestant_name";
$stmt_rsRegistrations = $DBconnection->prepare($query_rsRegistrations);
$stmt_rsRegistrations ->execute(array(':account_id'=>$account_id,':comp_id'=>$comp_id));
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();   
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
           // Show if recordset not empty 
            if ($totalRows_rsRegistrations > 0) { ?>
	<h3><a name="registration_delete" id="registration_delete"></a>4. Ta bort anm&auml;lningar</h3>
        <p>Om n&aring;got har blivit fel kan du ta bort anm&auml;lan. <strong>Du f&aring;r ingen bekr&auml;ftelse p&aring; anm&auml;lan, men kan se resultatet bl.a. under l&auml;nken "Startlistor"!</strong></p>
      <table width="800" border="1">
        <tr>
          <td><strong>Startnr.</strong></td>            
          <td><strong>T&auml;vlande</strong></td>
          <td><strong>F&ouml;delsedatum</strong></td>
          <td><strong>L&auml;ngd (eventuellt)</strong></td>
          <td><strong>T&auml;vlingsklass</strong></td>
          <td><strong>Ta bort anm&auml;lan</strong></td>          
        </tr>
        <?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)){ ?>
          <tr>
            <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>                            
            <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
            <td><?php echo $row_rsRegistrations['contestant_birth']; ?></td>
            <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
      <td><?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
      if ($row_rsRegistrations['class_age'] === "") { 
          echo "";          
      } 
      if ($row_rsRegistrations['class_age'] <> "") { 
          echo $row_rsRegistrations['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsRegistrations['class_weight_length'] === "-") {
          echo "";                    
      }
      if ($row_rsRegistrations['class_weight_length'] <> "-") {
         echo $row_rsRegistrations['class_weight_length']; 
      }
      ?></td>
            <td nowrap="nowrap">
			<?php if ($passedDate === 0) { ?>
           	<a href="RegDelete_reg.php?reg_id=<?php echo $row_rsRegistrations['reg_id']; ?>">Ta bort</a>
          	<?php } ?>
            </td></tr>
        <?php } ?>
      </table>
<?php       //Kill statement
            $stmt_rsRegistrations->closeCursor();
            // Show if rsRegistrations recordset not empty
            }
            //Kill statements
            $stmt_rsContestants->closeCursor();
            $stmt_rsMax_startnumber->closeCursor();   
        // Show if recordset $totalRows_rsContestants not empty 
        }  
        //Kill statement
        $stmt_rsClubReg->closeCursor();        
    // Show if rsClubReg recordset not empty
    }
  // Show if last registration date is NOT passed
  }
// If recordset rsClasses is NOT empty 
}
?>   
       </div>                     
</div>                 
<?php 
//Kill statement
$stmt_rsClasses->closeCursor();
include("includes/footer.php");
?>
</body>
</html>
<?php ob_end_flush();?>
