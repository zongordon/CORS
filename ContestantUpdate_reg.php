<?php
//Moved more reusable code to includes/contestant_update.php
ob_start();
//Access level registered club representative
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

$pagetitle="Uppdatera deltagare";

//Include reusable code for updating of contestants and teams
require_once 'includes/contestant_update.php';
