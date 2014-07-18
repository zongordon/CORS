<?php
//Changed SQL to only show messages intended for display not sent by email only
//Adjusted to display page title

require_once('Connections/DBconnection.php');

if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// Select all messages and comp_id for the current competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsNews = "SELECT m.message_id, message_subject, message, message_timestamp, co.comp_id FROM messages AS m INNER JOIN competition AS co ON m.comp_id = co.comp_id WHERE co.comp_current = 1 AND message_how = 'SiteOnly' OR co.comp_current = 1 AND message_how = 'SiteAndEmail' ORDER BY message_timestamp DESC";
$rsNews = mysql_query($query_rsNews, $DBconnection) or die(mysql_error());
$row_rsNews = mysql_fetch_assoc($rsNews);
$totalRows_rsNews = mysql_num_rows($rsNews);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Nyheter"?>
<meta http-equiv="Content-Type" content="; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<?php include("includes/header.php"); ?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include("includes/navigation.php"); ?></div>    
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
<?php } ?>      
<?php if ($totalRows_rsNews > 0) { // Show if recordset not empty ?>
      <h3>Nyheter</h3><br/>
    <table width="100%">

        <?php do { ?>
          <tr>
              <td valign ="top"><strong><?php echo $row_rsNews['message_subject']; ?></strong></td>
            <td valign ="top">&nbsp;</td>
          </tr>   
          <tr>            
            <td valign ="top"><?php echo $row_rsNews['message']; ?></td>
            <td nowrap="nowrap" valign ="top"><?php echo $row_rsNews['message_timestamp']; ?></td>
          </tr>
          <?php } while ($row_rsNews = mysql_fetch_assoc($rsNews)); ?>
      </table>
<?php } 
mysql_free_result($rsNews);?>                       
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>