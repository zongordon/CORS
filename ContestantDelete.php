<?php
//Moved meta description and keywords to header.php

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

//Delete contestant selected on previous page if contestant_id is provided
if (filter_input(INPUT_GET,'contestant_id') != "") {
    $contestant_id = filter_input(INPUT_GET,'contestant_id');
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');         
    $query = "DELETE FROM contestants WHERE contestant_id = :contestant_id";
    $stmt_rsDelete = $DBconnection->prepare($query);
    $stmt_rsDelete->bindValue(':contestant_id', $contestant_id, PDO::PARAM_INT);   
    $stmt_rsDelete->execute();
    }   catch(PDOException $ex) {
            echo "An Error occured with queryX: ".$ex->getMessage();
        }
    $deleteGoTo = "RegsHandleAll.php#registration_insert";
    header(sprintf("Location: %s", $deleteGoTo));
//Kill statements and DB connection
$stmt_rsDelete->closeCursor();
$DBconnection = null;    
}
$pagetitle="Ta bort deltagare - admin";
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
    <div class="story">
    <h3>Det finns ingen t&auml;vlande att ta bort!</h3>
<p><a href="RegsHandleAll.php">Tillbaka till Registrera t&auml;vlande</a></p>    
        <div class="feature">
        </div>
    </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>