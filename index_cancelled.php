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
    <h1>2014 &aring;rs upplaga av Tuna Karate Cup  &auml;r INST&Auml;LLD!</h1>
    <p>Med respekt f&ouml;r de t&auml;vlande och v&aring;ra egna sponsorer beslutade idag t&auml;vlingsledningen att st&auml;lla in &aring;rets upplaga av t&auml;vlingen. Vi har f&aring;tt allt f&ouml;r f&aring; anm&auml;lningar, vilket orsakade mycket f&aring; deltagare i flertalet klasser. Det vore inte r&auml;ttvist mot de t&auml;vlande som reser hit f&ouml;r att f&aring; t&auml;vla, anser vi.</p>
    <p>Vi ska f&ouml;rs&ouml;ka analysera orsaken till de f&aring; anm&auml;lningarna och tar g&auml;rna emot hj&auml;lp av er ledare i den fr&aring;gan. Om analysen visar att v&aring;r t&auml;vling trots allt har en plats i karate-Sverige kommer vi att f&ouml;rs&ouml;ka igen.</p>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>