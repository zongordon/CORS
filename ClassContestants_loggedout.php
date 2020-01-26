<?php
//Added code to show teams and moved reusable code to includes/class_contestants.php

//Access level top administrator
$MM_authorizedUsers = "";

$pagetitle="T&auml;vlande i klassen";
// Includes HTML Head
include_once('includes/header.php');
// Includes Several code functions
include_once('includes/functions.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">   
<?php require_once 'includes/class_contestants.php';
