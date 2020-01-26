<?php
//Moved reusable code to includes/classes_list.php and added sorting possibilities

ob_start();

//Declare and initialise variables
$editFormAction = '';

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";
    
$pagetitle="T&auml;vlingsklasser - admin";
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
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<?php require_once 'includes/classes_list.php';