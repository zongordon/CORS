<?php 

if (!isset($_SESSION)) {
  session_start();
}
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Rapporter f&ouml;r admininstrat&ouml;rer";
// Includes Several code functions
include_once('includes/functions.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?>    
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<h3>Rapporter f&ouml;r admininstrat&ouml;rer</h3>
  </div>
  <div class="story">
    <ul>
        <li><a href="Rep_Registrations_Club_Admin.php">Summering &ouml;ver anm&auml;lda per klubb, alla t&auml;vlingar</a></li>
      <li><a href="Rep_Contestants_Club_Admin.php">Summering &ouml;ver t&auml;vlande per klubb, totalt i databasen</a></li>
    </ul>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>