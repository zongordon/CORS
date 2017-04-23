<?php 
//Added header.php and news_sponsors_nav.php as includes.
ob_start();

if (!isset($_SESSION)) {
  session_start();
}

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
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>    
<div id="content">
  <div class="feature">
    <img src="img/DSC_0069.jpg" alt="" width="300" height="253" />
    <h1>&nbsp;</h1>
    <h1>Tuna Karate Cup ligger i malp&aring;se tillvidare!</h1>
    <p>Efter beslut att st&auml;lla in senaste t&auml;vlingen, ligger den i malp&aring;se tillvidare.</p>
    <p>Om det visar sig att v&aring;r t&auml;vling trots allt har en plats i karate-Sverige kommer vi att f&ouml;rs&ouml;ka igen.</p>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>