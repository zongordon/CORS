<?php
//Moved meta description and keywords to header.php
//Added link to go to previous page

if (!isset($_SESSION)) {
  session_start();
}

//Fetch the selected Class
if (filter_input(INPUT_GET, 'class_id')) {
    $colname_rsClass = filter_input(INPUT_GET, 'class_id');
}

    //Catch anything wrong with query
    try {
        require('Connections/DBconnection.php');           
        // Select the contestants and their information for the selected class
        $query3 = "SELECT a.club_name, re.reg_id, re.contestant_result, re.contestant_height, re.contestant_startnumber, co.contestant_name, co.contestant_gender, cl.class_id FROM registration AS re INNER JOIN classes AS cl USING (class_id) INNER JOIN contestants AS co USING (contestant_id) INNER JOIN account AS a USING (account_id) INNER JOIN clubregistration AS clu USING (club_reg_id) WHERE cl.class_id = :class_id ORDER BY club_startorder, reg_id";
        $stmt_rsRegistrations = $DBconnection->prepare($query3);
        $stmt_rsRegistrations->execute(array(':class_id' => $colname_rsClass));
        $totalRows_rsRegistrations = $stmt_rsRegistrations->rowCount();
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }              
        //Catch anything wrong with query
    try {
        // Select data for the selected class
        $query4 = "SELECT class_id, class_category, class_discipline, class_gender_category, class_age, class_weight_length FROM classes WHERE class_id = :class_id";
        $stmt_rsClass = $DBconnection->prepare($query4);
        $stmt_rsClass->execute(array(':class_id' => $colname_rsClass));
        $row_rsClass = $stmt_rsClass->fetch(PDO::FETCH_ASSOC);
    }   
    catch(PDOException $ex) {
        echo "An Error occured: ".$ex->getMessage();
    }                   

$pagetitle="T&auml;vlande i klassen";
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
    <?php if ($totalRows_rsRegistrations == 0) { // Show if recordset empty ?>
  <h3>Det finns ingen t&auml;vlande i klassen!</h3>
  <p><a href="ClassesList_loggedout.php">Tillbaka till T&auml;vlingsklasser</a></p>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_rsRegistrations > 0) { // Show if recordset not empty ?>  
  <h3>
<?php 
echo $row_rsClass['class_discipline'].' | '.$row_rsClass['class_gender_category'].' | '.$row_rsClass['class_category'];
if ($row_rsClass['class_age'] == "") { 
        echo ""; 
} 
if ($row_rsClass['class_age'] <> "") { 
    echo ' | '.$row_rsClass['class_age'].' &aring;r'.'  ';     
}
if ($row_rsClass['class_weight_length'] == "") { 
    echo "";     
} 
if ($row_rsClass['class_weight_length'] <> "") { 
echo ' | '.$row_rsClass['class_weight_length'];
}
?></h3>
  <table width="80%" border="1">
    <tr>
      <td><strong>Startnr.</strong></td>        
      <td><strong>Klubb</strong></td>
      <td><strong>T&auml;vlande</strong></td>
      <td><strong>L&auml;ngd (eventuellt)</strong></td>
      </tr>
<?php while($row_rsRegistrations = $stmt_rsRegistrations->fetch(PDO::FETCH_ASSOC)) { ?>
      <tr>
        <td><?php echo $row_rsRegistrations['contestant_startnumber']; ?></td>          
        <td><?php echo $row_rsRegistrations['club_name']; ?></td>
        <td><?php echo $row_rsRegistrations['contestant_name']; ?></td>
        <td><?php if ($row_rsRegistrations['contestant_height'] == "") { echo ''; }?><?php if ($row_rsRegistrations['contestant_height'] <> "") { echo $row_rsRegistrations['contestant_height'].' cm'; } ?></td>
</tr>
<?php } ?>
  </table>
<p><a href="javascript:history.go(-1);">Klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
<?php } // Show if recordset not empty ?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
//Kill statement and DB connection
$stmt_rsRegistrations->closeCursor();
$DBconnection = null;
?>