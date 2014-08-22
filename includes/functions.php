<?php
//Moved timezone transformation code and timestamp for Now() here

//Transform server time to GMT+1 and set timestamp for Now()
date_default_timezone_set('Europe/Stockholm');
$now = date('Y-m-d H:i');

if (!isset($_SESSION)) {
  session_start();
}

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();

//Convert strings to UTF-8
function encodeToUtf8($string) {
     return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}

//Convert strings to ISO-8859-1
function encodeToISO($string) {
     return mb_convert_encoding($string, "ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
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
    $MM_restrictGoTo = "LogIn.php";
    //Select redirect page depending on target page if user not correctly logged in
    if ($pagetitle == "L&auml;gga till ett konto - admin") {
    $MM_restrictGoTo = "AccountInsert_loggedout.php";
    }
    if ($pagetitle == "Lista konton - admin") {
    $MM_restrictGoTo = "AccountList_reg.php";
    }
    if ($pagetitle == "&Auml;ndra anv&auml;ndarkonto - admin") {
    $MM_restrictGoTo = "AccountUpdate_reg.php";
    }
    if ($pagetitle == "T&auml;vlande i klassen - admin") {
    $MM_restrictGoTo = "ClassContestants_loggedout.php";
    }
    if ($pagetitle == "T&auml;vlingsklasser - admin") {
    $MM_restrictGoTo = "ClassesList_loggedout.php";
    } 
    if ($pagetitle == "Ta bort anm&auml;lan - admin") {
    $MM_restrictGoTo = "LogedIn.php";
    }
    if ($pagetitle == "Registrera t&auml;vlande - admin") {
    $MM_restrictGoTo = "RegInsert_reg.php";
    }
    if ($pagetitle == "Inloggad - admin") {
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

//Create DB connection
require_once('Connections/DBconnection.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="<?php echo $pagedescription ?>"/>
<meta name="keywords" content="<?php echo $pagekeywords ?>" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
<script language="JavaScript" type="text/javascript" src="includes/PopUp.js"></script>
</head>
