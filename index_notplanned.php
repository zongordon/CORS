<?php 
// Removed navigation: include_once("includes/navigation.php")
//Inserted top banner
ob_start();

if (!isset($_SESSION)) {
  session_start();
}

$pagetitle="T&auml;vling";
// Includes Several other code functions
//include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
?>
<!-- start page -->
<div id="masthead">
    <a href="/"><img src="img/Banner_L.svg" alt="Left Logo" width="98" height="90" hspace="10"></a>
    <a href="/"><img src="img/Banner_M.png" alt="Middle Logo" width="553" height="90"></a>
    <a href="/"><img src="img/Banner_R.svg" alt="Right Logo" width="91" height="90" hspace="10"></a>
</div>
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>    
<div id="content">
  <div class="feature">
    <img src="img/DSC_0069.jpg" alt="" width="300" height="253" />
    <h1>&nbsp;</h1>
    <h1>T&auml;vlingen ligger i malp&aring;se tillvidare!</h1>
    <p>Efter beslut att st&auml;lla in senaste t&auml;vlingen, ligger den i malp&aring;se tillvidare.</p>
    <p>Om det visar sig att v&aring;r t&auml;vling trots allt har en plats i karate-Sverige kommer vi att f&ouml;rs&ouml;ka igen.</p>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>