<?php
//Adapted sql query to PHP 7 (PDO) and added minor error handling. Changed from charset=ISO-8859-1. 
//Added header.php and news_sponsors_nav.php as includes.
//Removed function GetSQLValueString

if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//Catch anything wrong with query
try {
require_once('Connections/DBconnection.php');    
// Select all messages and comp_id for the current competition
$query = "SELECT m.message_id, message_subject, message, message_timestamp, co.comp_id FROM messages AS m INNER JOIN competition AS co ON m.comp_id = co.comp_id WHERE co.comp_current = 1 AND message_how = 'SiteOnly' OR co.comp_current = 1 AND message_how = 'SiteAndEmail' ORDER BY message_timestamp DESC";
$stmt_rsNews = $DBconnection->query($query);
$totalRows_rsNews = $stmt_rsNews->rowCount();
}   catch(PDOException $ex) {
    echo "An Error occured!"; //user friendly message
    //some_logging_function($ex->getMessage());
    }
$pagetitle="Nyheter";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, logga in, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes Several other code functions
//include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");     
?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>    
<div id="content">
  <div class="feature"><img height="199" width="300" alt="" src="img/rotating/rotate.php" />
  <h3>Nyheter</h3>
      <p>H&auml;r l&auml;ggs senaste nytt upp f&ouml;r er information!</p>
      <p>H&aring;ll till godo!  </p>
  </div>
  <div class="story">
<?php if ($totalRows_rsNews == 0) { // Show if recordset empty ?>
        <div class="error">        
        <h3>Det finns inga nyheter &auml;n!</h3>
        </div>
<?php }       
      if ($totalRows_rsNews > 0) { // Show if recordset not empty ?>
        <h3>Nyheter</h3><br/>
        <table width="100%">
<?php   while($row_rsNews = $stmt_rsNews->fetch(PDO::FETCH_ASSOC)) {;?>
          <tr>
              <td valign ="top"><strong><?php echo $row_rsNews['message_subject']; ?></strong></td>
            <td valign ="top">&nbsp;</td>
          </tr>   
          <tr>            
            <td valign ="top"><?php echo $row_rsNews['message']; ?></td>
            <td nowrap="nowrap" valign ="top"><?php echo $row_rsNews['message_timestamp']; ?></td>
          </tr>
<?php   } ; ?>
      </table>
<?php } 
$stmt_rsNews->closeCursor();
$DBconnection = null;
?>                       
</div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>