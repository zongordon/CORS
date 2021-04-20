<?php
/*Removed iniation of session:
if (!isset($_SESSION)) {
  session_start();
}*/
ob_start();
if (!isset($_SESSION)) {
  session_start();
}
// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $AccountId, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_AccountId set equal to their account_id. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($AccountId)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($AccountId, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}
    //Default redirection page
    $MM_restrictGoTo = "Login.php";
    //Select specific redirect page depending on target page if user not correctly logged in
    if ($pagetitle === "L&auml;gga till ett konto - admin") {
    $MM_restrictGoTo = "AccountInsert_loggedout.php";
    }
    if ($pagetitle === "Lista anv&auml;ndarkonton - admin") {
    $MM_restrictGoTo = "AccountList_reg.php";
    }
    if ($pagetitle === "&Auml;ndra anv&auml;ndarkonto - admin") {
    $MM_restrictGoTo = "AccountUpdate_reg.php";
    }
    if ($pagetitle === "T&auml;vlande i klassen - admin") {
    $MM_restrictGoTo = "ClassContestants_loggedout.php";
    }
    if ($pagetitle === "T&auml;vlingsklasser - admin") {
    $MM_restrictGoTo = "ClassesList_loggedout.php";
    } 
    if ($pagetitle === "Ta bort anm&auml;lan - admin") {
    $MM_restrictGoTo = "LogedIn.php";
    }
    if ($pagetitle === "Registrera t&auml;vlande - admin") {
    $MM_restrictGoTo = "RegInsert_reg.php";
    }
    if ($pagetitle === "Inloggad - admin") {
    $MM_restrictGoTo = "LogedIn_reg.php";
    }
    
if (!((isset($_SESSION['MM_AccountId'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_AccountId'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) {
      $MM_qsChar = "&";
  }    
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) {
  $MM_referrer .= "?" . $QUERY_STRING;
  }
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
ob_end_flush(); 