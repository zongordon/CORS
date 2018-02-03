<?php 
//Added header.php and news_sponsors_nav.php as includes.
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
require_once('Connections/DBconnection.php');
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompetition = "SELECT comp_raffled FROM competition WHERE comp_current = 1";
$rsCompetition = mysql_query($query_rsCompetition, $DBconnection) or die(mysql_error());
$row_rsCompetition = mysql_fetch_assoc($rsCompetition);

$pagetitle="Tuna Karate Cup";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup inställd, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes Several other code functions
//include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");
?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<?php include_once("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>