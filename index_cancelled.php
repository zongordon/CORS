<?php
//Removed incorrect link 
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle="T&auml;vling";
// Includes HTML Head
include_once('includes/header.php');?>
<!-- start page -->
<div id="masthead">
    <img src="img/Banner.svg" alt="Tuna Cup logo" width="700" height="90">
</div>
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="content">
  <div class="feature">
    <h1>&nbsp;</h1>
    <h1>T&auml;vlingen &auml;r INST&Auml;LLD!</h1>
    <p>Situationen med Covid-19 g&ouml;r att t&auml;vlingsledningen har tagit beslutet att st&auml;lla in &aring;rets t&auml;vling. Vi hoppas f&ouml;rst&aring;s att ni vill g&ouml;ra ett nytt f&ouml;rs&ouml;k tillsammans med oss n&auml;sta &aring;r och att vi kan genomf&ouml;ra t&auml;vlingen då ist&auml;llet!</p>
  </div>
<iframe width="60%" height="315" src="https://www.youtube.com/embed/pl9HgW5pgTQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</html>
<?php ob_end_flush();?>