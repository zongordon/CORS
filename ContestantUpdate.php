<?php
//Moved more reusable code to includes/contestant_update.php
ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Uppdatera deltagare - admin";

//Include reusable code for updating of contestants and teams
require_once 'includes/contestant_update.php';