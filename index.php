<?php
// Competition cancelled 
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
    <h1>&nbsp;</h1>
    <h1>T&auml;vlingen &auml;r INST&Auml;LLD!</h1>
    <p>T&auml;vlingsledningen har tagit beslutet att s&auml;lla oss till flertalet andra t&auml;vlingsarrang&ouml;rer och st&auml;lla in &aring;rets t&auml;vling, i dessa Corona-tider. Vi hoppas f&ouml;rst&aring;s att ni vill g&ouml;ra ett nytt f&ouml;rs&ouml;k tillsammans med oss n&auml;sta &aring;r och att vi kan genomf&ouml;ra t&auml;vlingen då ist&auml;llet!</p>
  </div>
   <iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Feskilstunakarateklubb%2Fvideos%2F2420821441538024%2F&show_text=0&width=560" width="560" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>