<?php
//Added header.php and news_sponsors_nav.php as includes.

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle="Kontakter f&ouml;r t&auml;vlingen";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="visa kontakter att kontakta, tuna karate cup, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
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
      <h3>Kontakter</h3> 
      <p>F&ouml;r mer information: kontakta Frank 073-558 59 34 eller skicka f&ouml;rfr&aring;gan till tunacup@karateklubben.com.</p> 
  </div> 
  <div class="story"></div> 
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>