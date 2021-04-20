<?php
//Changed path for rotate.php and rotating images

if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}
//Changed code for rotating images
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
  <div class="feature"><img height="199" width="300" alt="" src="includes/rotate.php" /> 
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
        <table class="wide_tbl" border="0">
<?php   while($row_rsNews = $stmt_rsNews->fetch(PDO::FETCH_ASSOC)) {;?>
          <tr>
              <td valign ="top"><strong><?php echo $row_rsNews['message_subject']; ?></strong></td>
            <td valign ="top">&nbsp;</td>
          </tr>   
          <tr>            
            <td valign ="top"><?php echo $row_rsNews['message']; ?></td>
            <td valign ="top"><?php echo $row_rsNews['message_timestamp']; ?></td>
          </tr>
<?php   } ; ?>
      </table>
<?php } ?>                       
</div>
</div>
<?php 
//Kill statement
$stmt_rsNews->closeCursor();
include_once("includes/footer.php");
?>
</body>
</html>