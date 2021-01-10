<?php
//Changed from <div class="content" for correct layout

ob_start();
session_start();

//Initiate global variables
global $row_rsSelectedClub, $totalRows_rsClubReg, $totalRows_rsSelectedClub, $passedDate;

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Registrera t&auml;vlande - admin";
// require Class for validation of forms
require_once 'Classes/Validate.php';
// require Class for calculating contestant's age
require_once 'Classes/AgeCalc.php';
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
<div id="content">  
     <div class="feature">
<?php 
//Handle input from form
$editFormAction = filter_input(INPUT_SERVER,'PHP_SELF');
if (filter_input(INPUT_SERVER,'QUERY_STRING')) {
$editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}
  
//Define $colname_rsSelectedClub before the $_SESSION['MM_Account'] has been created
  if (empty($_SESSION['MM_Account'])) {  
    $colname_rsSelectedClub = "";
}
else {
    $colname_rsSelectedClub = $_SESSION['MM_Account']; 
}
//When a club is selected and "Välj klubb" clicked, initiate variable to select data from DB, to enable changing clubs
if (filter_input(INPUT_POST,'account_id')) {
    $colname_rsSelectedClub = filter_input(INPUT_POST,'account_id');    
} 
//Include reusable code for handling registration of contestants and teams to classes 
require_once('includes/regs_handle.php');

