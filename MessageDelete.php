<?php
//Adapted code to PHP 7 (PDO) and added minor error handling. 
//Added header.php, restrict_access.php and news_sponsors_nav.php as includes.

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Fetch the selected message id from previous page
$colname_rsMessage = filter_input(INPUT_GET,'message_id');

//Delete the message where clicked on "Ta bort"
if (filter_input(INPUT_GET,'message_id') != "") {
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');         
    $deleteSQL = "DELETE FROM messages WHERE message_id = :message_id";
    $stmt_rsMessageDelete = $DBconnection->prepare($deleteSQL);
    $stmt_rsMessageDelete->bindValue(':message_id', $colname_rsMessage, PDO::PARAM_INT);   
    $stmt_rsMessageDelete->execute();
    }      
    catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }

    $deleteGoTo = "MessagesHandle.php";
    if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= filter_input(INPUT_SERVER,'QUERY_STRING');
    }
    header(sprintf("Location: %s", $deleteGoTo));
}

$pagetitle="Ta bort meddelande";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Munktellarenan.";
$pagekeywords="tuna karate cup, Ta bort meddelande, karate, eskilstuna, Munktellarenan, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
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
<h3>Det finns inget meddelande att ta bort!</h3>
<p><a href="MessagesHandle.php">Tillbaka till Hantera nyheter</a></p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php 
//Kill statement 
$stmt_rsMessageDelete->closeCursor();
$DBconnection = null;
ob_end_flush()
?>