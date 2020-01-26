<?php
//Added functions to handle teams and moved reusable code to includes/regs_handle.php

ob_start();
session_start();

//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Registrera egna t&auml;vlande";
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
<div class ="content">    
       <div class="feature">    
<?php 
//Handle input from form
$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

//Get account_id for logged-in user
$colname_rsSelectedClub = $_SESSION['MM_AccountId'];

//Setting the date for today (including format), last enrolment date and check if the last enrolment date is passed or not
$now = date('Y-m-d');
$endEnrolmentDate = $comp_end_reg_date;
$passedDate = 0;
if ($endEnrolmentDate < $now) {
	$passedDate = 1;
}
//Include reusable code for handling registration of contestants and teams to classes 
require_once('includes/regs_handle.php');
