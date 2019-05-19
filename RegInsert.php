<?php
//Added conversion to upper title case for contestant_name, coach_names 
ob_start();

Global $contestant_name, $coach_names;

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_contestant")) {
  $contestant_name = encodeToISO(mb_convert_case($_POST['contestant_name'], MB_CASE_TITLE,"ISO-8859-1"));
  $insertSQL = sprintf("INSERT INTO contestants (contestant_name, contestant_birth, contestant_gender, account_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($contestant_name, "text"),
                       GetSQLValueString($_POST['contestant_birth'], "date"),
                       GetSQLValueString($_POST['contestant_gender'], "text"),
                       GetSQLValueString($_POST['account_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());

  $insertGoTo = "RegInsert_reg.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_club_reg")) {
  $coach_names = encodeToISO(mb_convert_case($_POST['coach_names'], MB_CASE_TITLE,"ISO-8859-1"));
  $insertSQL = sprintf("INSERT INTO clubregistration (coach_names, account_id, comp_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($coach_names, "text"),
                       GetSQLValueString($_POST['account_id'], "int"),
                       GetSQLValueString($_POST['comp_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());

  $insertGoTo = "RegInsert_reg.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_registration")) {
  $insertSQL = sprintf("INSERT INTO registration (club_reg_id, contestant_id, class_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['club_reg_id'], "int"),
                       GetSQLValueString($_POST['contestant_id'], "int"),
                       GetSQLValueString($_POST['class'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update_club_reg")) {
  $coach_names = encodeToISO(mb_convert_case($_POST['coach_names'], MB_CASE_TITLE,"ISO-8859-1"));  
  $updateSQL = sprintf("UPDATE clubregistration SET coach_names=%s, account_id=%s, comp_id=%s WHERE club_reg_id=%s",
                       GetSQLValueString($coach_names, "text"),
                       GetSQLValueString($_POST['account_id'], "int"),
                       GetSQLValueString($_POST['comp_id'], "int"),
                       GetSQLValueString($_POST['club_reg_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
}

$colname_rsClubReg = "1";
if (isset($_SESSION['MM_Accountid'])) {
  $colname_rsClubReg = $_SESSION['MM_Accountid'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClubReg = sprintf("SELECT club_reg_id, coach_names FROM clubregistration WHERE account_id = %s", GetSQLValueString($colname_rsClubReg, "int"));
$rsClubReg = mysql_query($query_rsClubReg, $DBconnection) or die(mysql_error());
$row_rsClubReg = mysql_fetch_assoc($rsClubReg);
$totalRows_rsClubReg = mysql_num_rows($rsClubReg);

mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompActive = "SELECT comp_id FROM competition WHERE comp_current = 1";
$rsCompActive = mysql_query($query_rsCompActive, $DBconnection) or die(mysql_error());
$row_rsCompActive = mysql_fetch_assoc($rsCompActive);
$totalRows_rsCompActive = mysql_num_rows($rsCompActive);
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
<p>Anm&auml;lan g&ouml;rs i fyra steg:</p>
<ol>
  <li>Skriv in namnen p&aring; de coacher som ska st&ouml;tta era t&auml;vlande.</li>
  <li>L&auml;gg in klubbens t&auml;vlande en och en. De l&auml;ggs till i listan under formul&auml;ret, allt eftersom de l&auml;ggs in. </li>
  <li>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i.</li>
  <li>Alla t&auml;vlingsanm&auml;lningar listas l&auml;ngst ned p&aring; sidan, s&aring; att du kan  ta bort dem om n&aring;got har blivit fel.</li>
</ol>
<h3>1. Skriv in klubbens coacher</h3>
<p>Skriv in namnen p&aring; de coacher som ska st&ouml;tta era t&auml;vlande och klicka p&aring; spara.</p>
</p>
<form id="new_club_reg" name="new_club_reg" method="POST" action="<?php echo $editFormAction; ?>">
  <?php if ($totalRows_rsClubReg == 0) { // Show if recordset empty ?>
    <table width="400" border="0">
      <tr>
        <td valign="top">Coacher</td>
        <td><label>
          <input name="coach_names" type="text" id="coach_names" size="55" />
        </label></td>
      </tr>
      <tr>
        <td><input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Accountid']; ?>" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsCompetition['comp_id']; ?>" /></td>
        <td><label>
          <input type="submit" name="new_club_reg" id="new_club_reg" value="Spara" />
        </label></td>
      </tr>
    </table>
    <?php } // Show if recordset empty ?>
<input type="hidden" name="MM_insert" value="new_club_reg" />
</form>
<form id="update_club_reg" name="update_club_reg" method="POST" action="<?php echo $editFormAction; ?>">
    <?php 
    if ($totalRows_rsClubReg > 0) { // Show if recordset not empty 
     ?>    
    <table width="400" border="0">
      <tr>
        <td valign="top">Coacher</td>
        <td><label>
          <input name="coach_names" type="text" id="coach_names" value="<?php echo $row_rsClubReg['coach_names']; ?>" size="55" />
          </textarea>
        </label></td>
      </tr>
      <tr>
        <td><input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsCompetition['comp_id']; ?>" />
          <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Accountid']; ?>" /></td>
        <td><label>
          <input type="submit" name="update_club_reg" id="update_club_reg" value="Spara" />
        </label></td>
      </tr>
    </table>
    <?php
    //Select contestants from a specific account
    $colname_rsContestants = "adm";
    if (isset($_SESSION['MM_Username'])) {
    $colname_rsContestants = $_SESSION['MM_Username'];
    }
    mysql_select_db($database_DBconnection, $DBconnection);
    $query_rsContestants = sprintf("SELECT * FROM contestants AS co JOIN account AS a ON co.account_id = a.account_id AND user_name = %s ORDER BY contestant_name", GetSQLValueString($colname_rsContestants, "text"));
    $rsContestants = mysql_query($query_rsContestants, $DBconnection) or die(mysql_error());
    $row_rsContestants = mysql_fetch_assoc($rsContestants);
    $colname_rsContestants = "1";
    
    //Select classes from current competition
    mysql_select_db($database_DBconnection, $DBconnection);
    $query_rsClasses = "SELECT cl.class_id, cl.comp_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM classes AS cl JOIN competition AS co ON cl.comp_id = co.comp_id WHERE co.comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender_category, cl.class_age";
    $rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
    $row_rsClasses = mysql_fetch_assoc($rsClasses);
    $totalRows_rsClasses = mysql_num_rows($rsClasses);

    } // Show if recordset not empty 
    ?>
<input name="MM_update" type="hidden" id="MM_update" value="update_club_reg" />
<input type="hidden" name="MM_update" value="update_club_reg" />
</form>
<h3>2. L&auml;gg in klubbens t&auml;vlande</h3>
<p>L&auml;gg in klubbens t&auml;vlande en och en. Ange namn, k&ouml;n och eventuellt l&auml;ngd f&ouml;r klasser d&auml;r det &auml;r till&auml;mpbart.</p>
<form id="new_contestant" name="new_contestant" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="400" border="0">
        <tr>
            <td>T&auml;vlandes namn</td>
          <td><label>
              <input name="contestant_name" type="text" id="contestant_name" size="25" />
          </label></td>
        </tr>
        <tr>
          <td>F&ouml;delsedatum</td>
          <td valign="top"><label>
            <input type="text" name="contestant_birth" id="contestant_birth" />
          </label></td>
        </tr>
        <tr>
            <td>K&ouml;n</td>
          <td valign="top"><p>
            <label>
              <input type="radio" name="contestant_gender" value="Man" id="contestant_gender_0" />
              Man</label>
            <label>
              <input type="radio" name="contestant_gender" value="Kvinna" id="contestant_gender_1" />
              Kvinna</label>
          </p></td>
        </tr>
        <tr>
          <td><input type="hidden" name="MM_insert" value="new_contestant" />
          <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Accountid']; ?>" /></td>
          <td><label>
              <input type="submit" name="new_contestant" id="new_contestant" value="Ny t�vlande" />
          </label></td>
        </tr>
      </table>
    </form>
    </p>
  </div>
  <div class="story">
    <?php 
    //Select all information about the contestants from the selected club
if (isset($_SESSION['MM_Accountid'])) {
  $colname_rsContestants = $_SESSION['MM_Accountid'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsContestants = sprintf("SELECT * FROM contestants WHERE account_id  = %s ORDER BY contestant_name", GetSQLValueString($colname_rsContestants, "int"));
$rsContestants = mysql_query($query_rsContestants, $DBconnection) or die(mysql_error());
$row_rsContestants = mysql_fetch_assoc($rsContestants);
$totalRows_rsContestants = mysql_num_rows($rsContestants);

    if ($totalRows_rsContestants > 0) { // Show if recordset not empty 
    ?>
      <h3>3. Anm&auml;l till t&auml;vlingklasser</h3>
      <p>V&auml;lj bland klubbens t&auml;vlande och anm&auml;l till den eller de t&auml;vlingsklasser som han/hon ska t&auml;vla i. Ta bort t&auml;vlande helt och h&aring;llet genom att klicka p&aring; l&auml;nken.</p>
      <table width="80%" border="1">
        <tr>
          <td>T&auml;vlande - F&ouml;delsedatum - K&ouml;n - L&auml;ngd (eventuellt) - T&auml;vlingsklass</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><form id="new_registration" name="new_registration" method="POST" action="<?php echo $editFormAction; ?>">
              <table width="200">
                <tr>
                  <td><label>
                    <input type="text" name="contestant_name" id="contestant_name" value="<?php echo $row_rsContestants['contestant_name']; ?>" size="25"/>
                  </label></td>
                  <td><label>
                    <input type="text" name="contestant_birth" id="contestant_birth" value="<?php echo $row_rsContestants['contestant_birth']; ?>" size="10"/>
                  </label></td>
                  <td><label>
                    <input name="contestant_gender" type="text" id="contestant_gender" value="<?php echo $row_rsContestants['contestant_gender']; ?>" size="5" />
                  </label></td>
                  <td><label>
                    <input name="contestant_height" type="text" id="contestant_height" value="-" size="3" />
                  </label></td>
                  <td nowrap="nowrap"><label>
                    <select name="class" id="class">
<?php
do {  
} while ($row_rsClasses = mysql_fetch_assoc($rsClasses)) ;//$row_rsClasses['class_gender'] = $row_rsContestants['contestant_gender'];
  $rows = mysql_num_rows($rsClasses);
  if($rows > 0) {
  mysql_data_seek($rsClasses, 0);
  $row_rsClasses = mysql_fetch_assoc($rsClasses);
  }
do {  
} while ($row_rsClasses = mysql_fetch_assoc($rsClasses));
  $rows = mysql_num_rows($rsClasses);
  if($rows > 0) {
  mysql_data_seek($rsClasses, 0);
  $row_rsClasses = mysql_fetch_assoc($rsClasses);
  }
do {  
?>
<option value="<?php echo $row_rsClasses['class_id']?>"<?php if (!(strcmp($row_rsClasses['class_id'], $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_weight_length'].' | '.$row_rsClasses['class_age'].' �r'))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsClasses['class_discipline'].' | '.$row_rsClasses['class_gender_category'].' | '.$row_rsClasses['class_weight_length'].' | '.$row_rsClasses['class_age'].' &aring;r'?></option>
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
                    <input type="submit" name="new_registration" id="new_registration" value="Anm&auml;l till klass" />
                  </label></td>
                  <td nowrap="nowrap"><a href="ContestantDelete_reg.php?contestant_id=<?php echo $row_rsContestants['contestant_id']; ?>">Ta bort t&auml;vlande</a></td>
                </tr>
              </table>
              <input name="contestant_id" type="hidden" id="contestant_id" value="<?php echo $row_rsContestants['contestant_id']; ?>" />
              <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubReg['club_reg_id']; ?>" />
              <input name="account_id" type="hidden" id="account_id" value="<?php echo $_SESSION['MM_Accountid']; ?>" />
              <input type="hidden" name="MM_insert" value="new_registration" />
            </form></td>
          </tr>
          <?php } while ($row_rsContestants = mysql_fetch_assoc($rsContestants)); ?>
      </table>
<?php 
    //Select registrated competitors for active classes
    $colname_rsRegistrations = "1";
    if (isset($_SESSION['MM_Accountid'])) {
    $colname_rsRegistrations = $_SESSION['MM_Accountid'];
    }
    mysql_select_db($database_DBconnection, $DBconnection);
    $query_rsRegistrations = sprintf("SELECT re.reg_id, co.contestant_name, co.contestant_birth, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category,cl.class_weight_length, cl.class_age FROM registration AS re  INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE account_id = %s ORDER BY cl.class_discipline, cl.class_gender_category,cl.class_weight_length, cl.class_age, co.contestant_name", GetSQLValueString($colname_rsRegistrations, "int"));
    $rsRegistrations = mysql_query($query_rsRegistrations, $DBconnection) or die(mysql_error());
    $row_rsRegistrations = mysql_fetch_assoc($rsRegistrations);
    $totalRows_rsRegistrations = mysql_num_rows($rsRegistrations);
    } // Show if recordset not empty ?>
    </p>
    <h3>&nbsp;</h3>
    <?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>
  <h3>4. Ta bort anm&auml;lningar</h3>
      <p>Om n&aring;got har blivit fel kan du ta bort anm&auml;lan.</p>
      <table width="80%" border="1">
        <tr>
          <td>T&auml;vlande</td>
          <td>Disciplin</td>
          <td>K&ouml;n</td>
          <td>L&auml;ngd (eventuellt)</td>
          <td>&Aring;lder</td>
          <td>Ta bort anm&auml;lan</td>          
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
            <td><?php echo $row_rsRegistrations['class_discipline']; ?></td>
            <td><?php echo $row_rsRegistrations['class_gender_category']; ?></td>
            <td><?php echo $row_rsRegistrations['class_weight_length'].' cm'; ?></td>
            <td><?php echo $row_rsRegistrations['class_age'].' &aring;r'; ?></td>
            <td nowrap="nowrap"><a href="RegDelete_reg.php?reg_id=<?php echo $row_rsRegistrations['reg_id']; ?>">Ta bort</a>
          </td></tr>
          <?php } while ($row_rsRegistrations = mysql_fetch_assoc($rsRegistrations)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
   </div>
</div>
<!-- end page -->
<?php include("includes/footer.php");
mysql_free_result($rsContestants);
mysql_free_result($rsClubReg);
mysql_free_result($rsClasses);
mysql_free_result($rsRegistrations);
mysql_free_result($rsCompActive);
ob_end_flush();?>