<?php
//Changed path for rotate.php and rotating images

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle="Kontakter f&ouml;r t&auml;vlingen";
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
<div class="feature"><img height="199" width="300" alt="" src="includes/rotate.php" />
      <h3>Kontakter</h3> 
      <p>F&ouml;r mer information: kontakta oss p&aring; 073-558 59 34 eller skicka f&ouml;rfr&aring;gan till <?php echo $row_rsCurrentComp['comp_email']?>.</p> 
  </div> 
  <div class="story"></div> 
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>