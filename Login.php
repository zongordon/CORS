<?php 
//Added session variable $_SESSION['MM_AccountId'] = $row_LoginRS['account_id'] to prevent problems if changing user_name

ob_start();

if (!isset($_SESSION)) {
  session_start();
}

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
<head><?php $pagetitle="Logga in"?>
<meta http-equiv="Content-Type" content="; charset=ISO-8859-1" />
<meta name="description" content="Logga in på Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, logga in, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">    
        <div class="error">
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user_name']) && (isset($_POST['user_password']))) {
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "LogedIn.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_DBconnection, $DBconnection);
  $loginUsername=$_POST['user_name'];
  $password=$_POST['user_password'];
  $tryLogin = "yes";
  
      if (empty($loginUsername)) {
      // $loginUsername is blank
      echo '<h3>Du gl&ouml;mde att fylla i anv&auml;ndarnamn!</h3>';
      $tryLogin = "no";
      }
      if (empty($password)) {
      // $password is blank
      echo '<h3>Du gl&ouml;mde att fylla i l&ouml;senord!</h3>';
      $tryLogin = "no";
      }      
      
  if ($tryLogin == "yes") {	      
   $query_rsUserexists=sprintf("SELECT user_name FROM account WHERE user_name=%s", GetSQLValueString($loginUsername, "text")); 
   $rsUserexists = mysql_query($query_rsUserexists, $DBconnection) or die(mysql_error());
   $totalRows_rsUserexists = mysql_num_rows($rsUserexists);   
   
     if ($totalRows_rsUserexists == 0) { // Show if recordset empty 
     echo '<h3>Anv&auml;ndarnamnet: "'.$loginUsername.'" finns inte! F&ouml;rs&ouml;k igen!</h3>';    
     $tryLogin = "no";
     }
     
     if ($tryLogin == "yes") {	      
        $LoginRS__query=sprintf("SELECT account_id, user_name, user_password, access_level FROM account WHERE user_name=%s AND user_password=%s",
        GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
        $LoginRS = mysql_query($LoginRS__query, $DBconnection) or die(mysql_error());
        $row_LoginRS = mysql_fetch_assoc($LoginRS);
        $loginFoundUser = mysql_num_rows($LoginRS);
        
        if ($loginFoundUser) {
            $loginStrGroup  = mysql_result($LoginRS,0,'access_level');
    
            //declare three session variables and assign them
            $_SESSION['MM_UserId'] = $loginUsername;
            $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
            $_SESSION['MM_AccountId'] = $row_LoginRS['account_id'];

            if (isset($_SESSION['PrevUrl']) && true) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
            }
        header("Location: " . $MM_redirectLoginSuccess );
        }
        else { 
        echo '<h3>Kombinationen av anv&auml;ndarnamn och l&ouml;senord var fel! F&ouml;rs&ouml;k igen!</h3>';	
        }
     }                  
   }
}
?>
        </div>
      <h3>Du &auml;r inte inloggad med tillg&aring;ng till alla sidor!</br>
Logga in till ditt klubbkonto f&ouml;r att anm&auml;la er eller &auml;ndra er anm&auml;lan!</h3>
<form id="LoginForm" name="LoginForm" method="POST" action="<?php echo $loginFormAction; ?>">
        <table width="200" border="0">
          <tr>
            <td><h1>Anv&auml;ndarnamn</h1></td>
            <td><input name="user_name" type="text" id="user_name" size="25" /></td>
          </tr>
          <tr>
            <td><h1>L&ouml;senord</h1></td>
            <td><input name="user_password" type="password" id="user_password" size="25" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
<td><input type="submit" name="LoginButton" id="LoginButton" value="Logga in" /></td>
          </tr>
        </table>
    </form>
<p><a href="ForgottenPassword.php">Gl&ouml;mt l&ouml;senordet?</a></p>
      <p>Har du inget anv&auml;ndarkonto &auml;n? <a href="AccountInsert.php">Skapa ett h&auml;r!</a></p>
  </div>
  <div class="story"></div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>