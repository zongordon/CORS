<?php
//Added functions to update teams and moved reusable code to includes/contestant_update.php
ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Uppdatera deltagare - admin";
// require Class for validation of forms
require_once 'Classes/Validate.php';
// Includes HTML Head
include_once('includes/header.php');
//Includes Several code functions
include_once('includes/functions.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");
//Includes Restrict access code function
include_once('includes/restrict_access.php');?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div class="content">
        <div class="feature"> 
<?php
//Include reusable code for updating of contestants and teams
require_once 'includes/contestant_update.php';