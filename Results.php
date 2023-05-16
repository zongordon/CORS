<?php
//Corrected bug that prevented from displaying teams' resulta

if (!isset($_SESSION)) {
  session_start();
}

//Handle input from form
$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

$sorting = "class_discipline, class_gender, class_age, class_weight_length, contestant_result";
if (filter_input(INPUT_GET,'sorting')) {
  $sorting = filter_input(INPUT_GET,'sorting');
}
//Catch anything wrong with DB connection
try {
require_once('Connections/DBconnection.php');     
// Select the results for the current competition
$query = "SELECT com.comp_current, com.comp_id, a.club_name, re.reg_id, re.contestant_result, co.contestant_name, re.contestant_height, cl.class_team, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_gender_category, cl.class_weight_length, cl.class_age FROM registration AS re INNER JOIN clubregistration AS clu USING (club_reg_id) INNER JOIN account AS a USING (account_id) INNER JOIN competition AS com USING (comp_id) INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) WHERE com.comp_current = 1 AND re.contestant_result > 0 ORDER BY $sorting";
$stmt_rsResult = $DBconnection->query($query);
$totalRows_rsResult = $stmt_rsResult->rowCount();
}   catch(PDOException $ex) {
    echo "An Error occured!"; //user friendly message
    //some_logging_function($ex->getMessage());
    }
$pagetitle="T&auml;vlingsresultat";
// Includes Several other code functions
//include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?>
<!-- start page -->
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature"><img height="199" width="300" alt="" src="includes/rotate.php" /> 
<h3>Resultat</h3> 
        <p>H&auml;r finns alla t&auml;vlingsresultat fr&aring;n t&auml;vlingen!</p> 
  </div>        
<div class="story">
  <?php if ($totalRows_rsResult == 0) { // Show if recordset empty ?>
    <p>Det finns inget resultat att visa &auml;n!</p>
  <?php } 
if ($totalRows_rsResult > 0) { // Show if recordset not empty ?>  
<h3>Samtliga resultat</h3>
<p>Nedan finns samtliga resultat vid t&auml;vlingen. &Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
<form action="<?php echo $editFormAction; ?>" method="GET" enctype="application/x-www-form-urlencoded" name="SelectSorting" id="SelectSorting">
  <table width="250" border="0">
    <tr>
      <td valign="middle">Sortering</td>
      <td><label>
        <select name="sorting" id="sorting">
      <option value="club_name, class_discipline, class_gender, class_age, class_weight_length, contestant_result"
          <?php if (!(strcmp($sorting, "club_name, class_discipline, class_gender, class_age, class_weight_length, contestant_result"))) {
                    echo "selected=\"selected\"";
                } ?>>Klubb</option>
      <option value="class_discipline, class_gender, class_age, class_weight_length, contestant_result"
          <?php if (!(strcmp($sorting, "class_discipline, class_gender, class_age, class_weight_length, contestant_result"))) {
                    echo "selected=\"selected\"";
                } ?>>T&auml;vlingsklass</option>
      <option value="contestant_name, club_name"
          <?php if (!(strcmp($sorting, "contestant_name, club_name"))) {
                    echo "selected=\"selected\"";
                } ?>>T&auml;vlande</option>
        </select>
      </label></td>
      <td><input type="submit" name="submit" id="submit" value="Sortera" /></td>
    </tr>
  </table>
</form>
<table class="wide_tbl" border="1">
  <tr>
    <td><strong>Klubb</strong></td>
    <td><strong>T&auml;vlande</strong></td>
    <td><strong>Placering</strong></td>
    <td><strong>T&auml;vlingsklass</strong></td>
    </tr>
<?php while($row_rsResult = $stmt_rsResult->fetch(PDO::FETCH_ASSOC)) {; 
        //Show only the top three contestants
	if (($row_rsResult['contestant_result'] > 0) && ($row_rsResult['contestant_result'] < 4)) { ?>
    <tr>
      <td><?php echo $row_rsResult['club_name']; ?></td>
      <td><?php echo $row_rsResult['contestant_name']; ?></td>
      <td align="center"><?php echo $row_rsResult['contestant_result'].':a'; ?></td>
      <td><?php if($row_rsResult['class_team'] === 1){echo'Lag - ';} echo $row_rsResult['class_discipline'].' | '.$row_rsResult['class_gender_category'].' | '.$row_rsResult['class_category'].' | '; 
      if ($row_rsResult['class_age'] == "") { 
          echo "";          
      } 
      if ($row_rsResult['class_age'] <> "") { 
          echo $row_rsResult['class_age'].' &aring;r'.' | '; 
      }
      if ($row_rsResult['class_weight_length'] == "-") {
          echo "";                    
      }
      if ($row_rsResult['class_weight_length'] <> "-") {
         echo $row_rsResult['class_weight_length']; 
      }
      ?>
</td>
</tr>

<?php      
        }      
     } 
?>      
</table>
<?php  
} // Show if recordset not empty
//Kill statement
$stmt_rsResult->closeCursor();
?>
  </div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>