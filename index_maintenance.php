<?php 
//Changed title
ob_start();

if (!isset($_SESSION)) {
  session_start();
}

$pagetitle="T&auml;vling";
// Includes Several other code functions
//include_once('includes/functions.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");
?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<?php include_once("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>