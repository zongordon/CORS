<?php
// Removed navigation: include_once("includes/navigation.php")
//Inserted top banner
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle="T&auml;vling";
// Includes HTML Head
include_once('includes/header.php');?>
<!-- start page -->
<div id="masthead">
    <a href="/"><img src="img/Banner_L.svg" alt="Left Logo" width="98" height="90" hspace="10"></a>
    <a href="/"><img src="img/Banner_M.png" alt="Middle Logo" width="553" height="90"></a>
    <a href="/"><img src="img/Banner_R.svg" alt="Right Logo" width="91" height="90" hspace="10"></a>
</div>
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="content">
  <div class="feature">
    <img src="img/DSC_0069.jpg" alt="" width="300" height="253" />
    <h1>&nbsp;</h1>
    <h1>Ny t&auml;vling planeras under 2019!</h1>
    <p>Vi har ans&ouml;kt om att f&aring; anordna v&aring;r t&auml;vling 18 maj.</p>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>
