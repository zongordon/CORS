<?php 
ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Kopiera T&auml;vlingklasser";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, kopiera tävlingsklasser från en tävling till en annan, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
include_once('includes/functions.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//Catch comp_id sent from previous page to select correct competion's classes
if (isset($_GET['comp_id'])) {
  $comp_id = $_GET['comp_id'];
}
//Select all classes for selected competition and the competition's name
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClasses = sprintf("SELECT com.comp_name, cl.class_id, cl.class_category, cl.class_discipline, cl.class_gender, cl.class_age, cl.class_weight_length, cl.class_gender_category FROM classes AS cl INNER JOIN competition AS com USING (comp_id) WHERE comp_id=%s ORDER BY class_discipline, class_gender, class_age, class_weight_length, class_gender_category", GetSQLValueString($comp_id, "int"));
$rsClasses = mysql_query($query_rsClasses, $DBconnection) or die(mysql_error());
$row_rsClasses = mysql_fetch_assoc($rsClasses);
$totalRows_rsClasses = mysql_num_rows($rsClasses);

//Select all competitions except the one from where the classes are copied
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompetitions = sprintf("SELECT comp_name, comp_id FROM competition WHERE comp_id<>%s ", GetSQLValueString($comp_id, "int"));
$rsCompetitions = mysql_query($query_rsCompetitions, $DBconnection) or die(mysql_error());
$row_rsCompetitions = mysql_fetch_assoc($rsCompetitions);
$totalRows_rsCompetitions = mysql_num_rows($rsCompetitions);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php"); ?>

<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
  <!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
  <?php if ($totalRows_rsClasses == 0) { // Show if recordset empty ?>
    <p>Det finns inga t&auml;vlingsklasser att visa!</p>
  <?php } // Show if recordset empty ?>
<?php 
if ($totalRows_rsClasses > 0) { // Show if recordset not empty ?>
    <h3>Befintliga t&auml;vlingsklasser i <?php echo $row_rsClasses['comp_name'] ?></h3>
    <p>Kopiera t&auml;vlingsklasser genom att klicka i respektive ruta och klicka p&aring; "Kopiera"!</p>  
      <div class="error">    
<?php    
//If "Kopiera" button is clicked then validate and execute the below
if ((isset($_POST["MM_CopyClasses"])) && ($_POST["MM_CopyClasses"] == "copy_classes")) {
$output_form = 'no';

        if ($_POST['copy_class'] == "") {
        // all copy_class fields are blank
        echo '<h3>Du gl&ouml;mde att v&auml;lja n&aring;gon klass att kopiera!</h3><br/>';            
        $output_form = 'yes';    
        }
}
   else {  
   $output_form = 'yes';
   }    
if ($output_form == 'yes') {    
?>
    <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="copy_classes" id="copy_classes">
    <table width="100%" border="1">
      <tr>
        <td><strong>Disciplin</strong></td>
        <td><strong>K&ouml;nskategori</strong></td>
        <td><strong>Kategori</strong></td>
        <td><strong>&Aring;lder</strong></td>
        <td><strong>Vikt- eller l&auml;ngdkategori</strong></td>
        <td><strong>Kopiera</strong></td>
      </tr>
      <?php do { ?>
  <tr>
          <td><?php echo $row_rsClasses['class_discipline']; ?></td>
          <td><?php echo $row_rsClasses['class_gender_category']; ?></td>
          <td><?php echo $row_rsClasses['class_category']; ?></td>
          <td><?php echo $row_rsClasses['class_age']; ?></td>
          <td><?php echo $row_rsClasses['class_weight_length']; ?></td>
          <td><label>
              <input name="copy_class[]" type="checkbox" id="copy_class[]" value="<?php echo $row_rsClasses['class_id'];?>" checked />
            </label>
          </td>
  </tr>
  <?php } while ($row_rsClasses = mysql_fetch_assoc($rsClasses)); ?>
    <tr>
      <td valign="top">V&auml;lj t&auml;vling att kopiera till</td>
      <td><label>
        <select name="comp_id" id="comp_id">
          <?php
do {  
?>
          <option value="<?php echo $row_rsCompetitions['comp_id']?>"<?php if (!(strcmp($row_rsCompetitions['comp_id'], $_GET['comp_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsCompetitions['comp_name']?></option>
          <?php
} while ($row_rsCompetitions = mysql_fetch_assoc($rsCompetitions));
  $rows = mysql_num_rows($rsCompetitions);
  if($rows > 0) {
      mysql_data_seek($rsCompetitions, 0);
	  $row_rsCompetitions = mysql_fetch_assoc($rsCompetitions);
  }
?>
        </select>
      </label></td>
      <td>
      <input type="hidden" name="MM_CopyClasses" value="copy_classes" />
      <input type="submit" name="copy_classes" id="copy_classes" value="Kopiera" />
      </td>
    </tr>
    </table>
    </form>
        </div>          
  </div>
  <div class="story">
    <p>&nbsp;</p>
  </div>
</div>    
<?php 
}       //If the form shall not be displayed execute below    
  	else if ($output_form == 'no') {
          //If the "Kopiera" button is clicked and classes chosen for copy, then copy those classes to the selected competition  
          if ((isset($_POST["MM_CopyClasses"])) && ($_POST["MM_CopyClasses"] == "copy_classes")) {            
            foreach($_POST['copy_class'] as $class_id) {
            $comp_id = $_POST['comp_id'];
            $insertSQL = "INSERT INTO classes (comp_id, class_category, class_discipline, class_gender_category, class_gender, class_weight_length, class_age, class_fee)
                          SELECT $comp_id AS comp_id, class_category, class_discipline, class_gender_category, class_gender, class_weight_length, class_age, class_fee  
                          FROM classes
                          WHERE class_id = $class_id";                
             mysql_select_db($database_DBconnection, $DBconnection);
             $Result1 = mysql_query($insertSQL, $DBconnection) or die(mysql_error());
                
              $updateGoTo = "ClassesList.php";
                    if (isset($_SERVER['QUERY_STRING'])) {
                    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
                    $updateGoTo .= $_SERVER['QUERY_STRING'];
                    }        
              header(sprintf("Location: %s", $updateGoTo));
            } 
          }
        } 
} // Show if recordset not empty 
ob_end_flush();?>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsClasses);
mysql_free_result($rsCompetitions);
?> 