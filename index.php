<?php 
//Changed title
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle="T&auml;vling";
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>    
<div id="content">
  <div class="feature">
    <img src="img/DSC_0069.jpg" alt="" width="300" height="253" />
    <h1>&nbsp;</h1>
    <h1><?php echo $row_rsCurrentComp['comp_name'];?> &auml;r genomf&ouml;rd</h1>
    <p>Vi tackar alla deltagare, klubbledare, domare, &aring;sk&aring;dare och funktion&auml;rer f&ouml;r ett v&auml;l genomf&ouml;rt <?php echo $row_rsCurrentComp['comp_name'];?>! Ett stort tack fr&aring;n <?php echo $row_rsCurrentComp['comp_arranger'];?>!</p>
    <p>Vi hoppas att vi f&aring;r se er alla och fler d&auml;rtill n&auml;sta &aring;r! Tills dess kommer sajten att vara f&ouml;rb&auml;ttrad ytterligare och ni kommer &auml;ven forts&auml;ttningsvis att kunna dra nytta av att redan ha skapat ett konto hos oss och lagt in en hel del t&auml;vlande. Vi kommer givetvis att arbeta med att hela arrangemanget flyter &auml;nnu b&auml;ttre n&auml;sta g&aring;ng.</p>
    <p>H&ouml;r g&auml;rna av er med f&ouml;rslag p&aring; hur vi kan f&ouml;rb&auml;ttra t&auml;vlingen till n&auml;sta &aring;r! Stort som sm&aring;tt, alla f&ouml;rslag &auml;r v&auml;lkomna och kommer att tas upp vid v&aring;r interna utv&auml;rdering.</p>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>