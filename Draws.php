<?php
//Corrected bug that gave error message because missing selection of club (no selection done when opening the page)

if (!isset($_SESSION)) {
  session_start();
}

// Select information regarding selected account    
$colname_rsSelectedClub = "all";
if (filter_input(INPUT_POST,'account_id')) {
  $colname_rsSelectedClub = filter_input(INPUT_POST,'account_id');
}

//Catch anything wrong with query
try {
// Select class data from all or selected account and current competition
require('Connections/DBconnection.php');
if ($colname_rsSelectedClub === "all") {
$query_rsRegistrations = "SELECT DISTINCT cl.class_id, cl.class_team, cl.class_category, cl.class_discipline, cl.class_gender, "
        . "cl.class_gender_category, cl.class_weight_length, cl.class_age "
        . "FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) "
        . "INNER JOIN competition as com USING (comp_id) INNER JOIN account as a USING (account_id) "
        . "WHERE comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length";
$stmt_rsRegistrations = $DBconnection->query($query_rsRegistrations);
} 
else {    
$query_rsRegistrations = "SELECT DISTINCT cl.class_id, cl.class_team, cl.class_category, cl.class_discipline, cl.class_gender, "
        . "cl.class_gender_category, cl.class_weight_length, cl.class_age "
        . "FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) "
        . "INNER JOIN competition as com USING (comp_id) INNER JOIN account as a USING (account_id) "
        . "WHERE account_id = :account_id AND comp_current = 1 ORDER BY cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length";
$stmt_rsRegistrations = $DBconnection->prepare($query_rsRegistrations);
$stmt_rsRegistrations->execute(array(':account_id'=>$colname_rsSelectedClub));
}
$totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }    
    
//Catch anything wrong with query
try {
// Select information regarding active accounts
require('Connections/DBconnection.php');           
$query_rsAccounts = "SELECT DISTINCT account_id, club_name FROM registration AS re INNER JOIN classes AS cl USING (class_id) "
        . "INNER JOIN contestants AS co USING (contestant_id) INNER JOIN competition as com USING (comp_id) "
        . "INNER JOIN account as a USING (account_id) WHERE comp_current = 1 ORDER BY club_name ASC";
$stmt_rsAccounts = $DBconnection->query($query_rsAccounts);
$row_rsAccounts = $stmt_rsAccounts->fetchAll(PDO::FETCH_ASSOC); 
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
 
$pagetitle="Lottning";
// Includes Several code functions
include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
    <div class="feature">
        <p>V&auml;l klubb och klicka p&aring; V&auml;lj f&ouml;r att se de t&auml;vlingsstegar som ber&ouml;r den klubben!</p>
      <form id="SelectClub" name="SelectClub" method="POST" action="<?php echo $editFormAction; ?>">
        <table width=30% border="0">
          <tr>
            <td valign="middle">Klubb:</td>
            <td><label>
              <select name="account_id" id="account_id">
                  <option value="all" selected=\"selected\">Alla</option>
<?php
foreach($row_rsAccounts as $row_rsAccount) {  
?>
                <option value="<?php echo $row_rsAccount['account_id']?>"
            <?php
            if (filter_input(INPUT_POST,'account_id') <> ''){
                if (!(strcmp($row_rsAccount['account_id'], filter_input(INPUT_POST,'account_id')))) {
                echo "selected=\"selected\""; 
                } 
            }?>>
                <?php echo $row_rsAccount['club_name']?>
                </option>
<?php
} ?>
              </select>
            </label></td>
            <td><input type="submit" name="submit" class = "button" id="submit" value="V&auml;lj" /></td>
          </tr>
        </table>
      </form>
<h3>Lottade t&auml;vlingsstegar</h3>
<?php if ($totalRows_rsRegistrations === 0) { // Show if recordset empty ?>
    <p>Det finns inget resultat att visa!</p>
<?php } ?>
<?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>
    <p>Klicka p&aring; l&auml;nkarna till t&auml;vlingsstegarna och v&auml;lj utskrift fr&aring;n sk&auml;rm eller PDF!</p>
    <table class="wide_tbl" border="1">
      <tr>
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>T&auml;vlingsstege</strong></td>
      </tr>
<?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) {?>
        <tr>
          <td><?php if($row_rsRegistrations['class_team'] === 1){echo'Lag - ';} echo $row_rsRegistrations['class_discipline']; ?></td>
          <td><?php echo $row_rsRegistrations['class_gender_category']; ?></td>
          <td><?php echo $row_rsRegistrations['class_category']; ?></td>
          <td><?php echo $row_rsRegistrations['class_age']; ?></td>
          <td><?php echo $row_rsRegistrations['class_weight_length']; ?></td>
          <td><a href="javascript:MM_openBrWindow('ElimLadder.php?class_id=<?php echo $row_rsRegistrations['class_id']; ?>','T&auml;vlingsstege','',1145,800,'true')">L&auml;nk (v&auml;lj utskrift fr&aring;n sk&auml;rm eller PDF)</a></td>
        </tr>
<?php } ?>
    </table>
    <?php
      } // Show if recordset not empty 
    ?>
  </div>
</div>
<?php 
//Kill statements
$stmt_rsRegistrations->closeCursor();
$stmt_rsAccounts->closeCursor();
include_once("includes/footer.php");?>
</body>
</html>