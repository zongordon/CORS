<?php 
//Adapted code to PHP 7 (PDO) and added minor error handling. 
//Added header.php, restrict_access.php and news_sponsors_nav.php as includes.
//Added check of access level

ob_start();

if (!isset($_SESSION)) {
  session_start();
}

//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

//Catch anything wrong with query
try {
// Select information regarding active accounts
require('Connections/DBconnection.php');           
$query_rsAccounts = "SELECT account_id, club_name, active FROM account WHERE active = 1 ORDER BY club_name ASC";
$stmt_rsAccounts = $DBconnection->query($query_rsAccounts);
$row_rsAccounts = $stmt_rsAccounts->fetchAll(PDO::FETCH_ASSOC); 
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

// Select information regarding selected account    
$colname_rsSelectedClub = "";
if (filter_input(INPUT_POST,'account_id')) {
  $colname_rsSelectedClub = filter_input(INPUT_POST,'account_id');
}

//Catch anything wrong with query
try {
// Select information regarding active accounts
require('Connections/DBconnection.php');           
$query_rsSelectedClub = "SELECT account_id, club_name, active FROM account WHERE account_id = :account_id";
$stmt_rsSelectedClub = $DBconnection->prepare($query_rsSelectedClub);
$stmt_rsSelectedClub->execute(array(':account_id'=>$colname_rsSelectedClub));
$row_rsSelectedClub = $stmt_rsSelectedClub->fetch(PDO::FETCH_ASSOC); 
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Select information regarding active accounts
require('Connections/DBconnection.php');           
$query_rsCost = "SELECT coach_names, COUNT(reg_id), SUM(class_fee) FROM competition INNER JOIN classes USING(comp_id) INNER JOIN registration USING(class_id) INNER JOIN clubregistration USING (club_reg_id) INNER JOIN account USING(account_id) WHERE account_id = :account_id AND comp_current = 1";
$stmt_rsCost = $DBconnection->prepare($query_rsCost);
$stmt_rsCost->execute(array(':account_id'=>$colname_rsSelectedClub));
$row_rsCost = $stmt_rsCost->fetch(PDO::FETCH_ASSOC);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

//Catch anything wrong with query
try {
// Select registrations from selected account and class data from selected account and current competition
require('Connections/DBconnection.php');           
$query_rsRegistrations = "SELECT a.club_name, re.reg_id, re.contestant_height, co.contestant_name, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) INNER JOIN account as a USING (account_id) WHERE account_id = :account_id AND comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, co.contestant_name";
$stmt_rsRegistrations = $DBconnection->prepare($query_rsRegistrations);
$stmt_rsRegistrations->execute(array(':account_id'=>$colname_rsSelectedClub));
$row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC); 
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }    
    
$pagetitle="Rapport: samtliga anm&auml;lningar, coacher och kostnad per klubb";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Munktellarena.";
$pagekeywords="tuna karate cup, rapport för samtliga anmälningar, coacher och kostnad per klubb, karate, eskilstuna, Munktellarena, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes Several code functions
include_once('includes/functions.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');
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
<h3>Anm&auml;lningar, coacher och kostnad per klubb</h3>
<p>Rapporten visar vilka anm&auml;lningar som gjorts till aktuell t&auml;vling, vilka coacher som anm&auml;lts och den sammanlagda kostnaden f&ouml;r vald klubb.</p>
<p>V&auml;l klubb och klicka p&aring; V&auml;lj!</p>
      <form id="SelectClub" name="SelectClub" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="200" border="0">
          <tr>
            <td valign="middle">Klubb</td>
            <td><label>
              <select name="account_id" id="account_id">
                <?php
foreach($row_rsAccounts as $row_rsAccount) {  
?>
                <option value="<?php echo $row_rsAccount['account_id']?>"
            <?php if (!(strcmp($row_rsAccount['account_id'], filter_input(INPUT_POST,'account_id')))) {
                    echo "selected=\"selected\""; 
                  } ?>>
                <?php echo $row_rsAccount['club_name']?>
                </option>
<?php
} ?>
              </select>
            </label></td>
            <td><input type="submit" name="submit" id="submit" value="V&auml;lj" /></td>
          </tr>
        </table>
      </form>
  <?php if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
    <p>Det finns inget resultat att visa!</p>
  <?php } ?>
  <?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>  
      <table width="80%" border="1">
        <tr>
          <td><strong>Klubb</strong></td>
          <td><strong>T&auml;vlande</strong></td>
          <td><strong>L&auml;ngd (eventuellt)</strong></td>
          <td><strong>T&auml;vlingsklass</strong></td>
        </tr>
        <?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['club_name']; ?></td>
          <td nowrap="nowrap"><?php echo $row_rsRegistrations['contestant_name']; ?></td>
          <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
          <td> <?php echo $row_rsRegistrations['class_discipline'].' | '.$row_rsRegistrations['class_gender_category'].' | '.$row_rsRegistrations['class_category'].' | '; 
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
        </tr>
        <?php } ?>
        <tr>
          <td valign="top"><strong>Antal&nbsp;anm&auml;lningar</strong>:<br/><?php echo $totalRows_rsRegistrations;?></td>
          <td valign="top"><strong>Total kostnad</strong>:<br/><?php echo $row_rsCost['SUM(class_fee)'].' kr';?></td>
          <td valign="top" colspan="2"><strong>Coacher</strong>:<br/><?php echo $row_rsCost['coach_names'];?></td>
        </tr>        
    </table>
    <?php
  }
  ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statements and DB connection
$stmt_rsAccounts->closeCursor();
$stmt_rsRegistrations->closeCursor();
$stmt_rsCost->closeCursor();
$stmt_rsSelectedClub->closeCursor();
$DBconnection = null;
?>