<?php
//Moved meta description and keywords to header.php

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

//Fetch the selected Class
if (filter_input(INPUT_GET, 'class_id')) {
    $colname_rsClass = filter_input(INPUT_GET, 'class_id');
// Delete the selected class when clicking the Delete link
    require('Connections/DBconnection.php');           
    $query = "DELETE FROM classes WHERE class_id = :class_id";
    $stmt_rsUserexists = $DBconnection->prepare($query);
    $stmt_rsUserexists->bindValue(':class_id', $colname_rsClass, PDO::PARAM_INT);   
    $stmt_rsUserexists->execute();
}

    $deleteGoTo = "ClassesList.php";
    if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
    }
    header(sprintf("Location: %s", $deleteGoTo));

$pagetitle="Ta bort t&auml;vlingsklass";
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
<h3>Det finns ingen t&auml;vlingsklass att ta bort!</h3>
<p><a href="ClassesList.php">Tillbaka till T&auml;vlingsklasser</a> </p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>