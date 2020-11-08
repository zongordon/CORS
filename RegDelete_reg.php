<?php
//Moved reusable code to includes/reg_delete.php

ob_start();
//Access level registered user
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

//Include reusable code for handling registration of contestants and teams to classes 
require_once('includes/reg_delete.php');
