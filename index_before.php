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
    <a href="/"><img src="img/Banner.svg" alt="Tuna Karate Cup Logo" width="700" height="90"></a>
</div>
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="content">
  <div class="feature">
    <h1>Ny t&auml;vling planeras under 2021!</h1>
    <p>Vi har ans&ouml;kt om att f&aring; anordna v&aring;r t&auml;vling 22 maj och &aring;terkommer med mer information inom kort.</p>
    <iframe width="60%" height="315" src="https://www.youtube.com/embed/pl9HgW5pgTQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>
