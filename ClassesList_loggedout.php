<?php
//Moved reusable code to includes/classes_list.php and added sorting possibilities

if (!isset($_SESSION)) {
  session_start();
}    

//Access level public
$MM_authorizedUsers = "";

$pagetitle="T&auml;vlingsklasser";
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");     
?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
    <div class="feature">
<?php require_once 'includes/classes_list.php';