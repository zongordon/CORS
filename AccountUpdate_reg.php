<?php 
//Removed duplicate code to includes/account_updates.php
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
//Access level registered users
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "true";

$colname_rsAccountId = $_SESSION['MM_AccountId'];

$editFormAction = $_SERVER['PHP_SELF'];
if (filter_input(INPUT_SERVER, 'QUERY_STRING')) {
  $editFormAction .= "?" . htmlentities(filter_input(INPUT_SERVER, 'QUERY_STRING'));
}

$pagetitle="&Auml;ndra anv&auml;ndarkonto";
// require Class for validation of forms
require_once 'Classes/Validate.php';
// Includes HTML Head
include_once('includes/header.php');
//Includes Several code functions
include_once('includes/functions.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
    <div class="feature">
<?php require_once 'includes/account_update.php';