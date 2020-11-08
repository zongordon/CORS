<?php
//Moved reusable code to includes/reg_delete.php

ob_start();

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

//Include reusable code for handling registration of contestants and teams to classes 
require_once('includes/reg_delete.php');
