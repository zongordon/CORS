<?php
//Changed $_SESSION['MM_Level'] to unset

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['MM_Accountid'] = NULL;
  $_SESSION['MM_Level'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['MM_Accountid']); 
  unset($_SESSION['MM_Level']); 	
  $logoutGoTo = "Login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Logga ut"?>
<meta http-equiv="Content-Type" content="; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup, karate, eskilstuna, sporthallen, wado, sj&auml;lvf&ouml;rsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php"); ?>
  <!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
  <!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Vill du logga ut fr&aring;n ditt klubbkonto s&aring; klicka p&aring; l&auml;nken nedan!</h3>
<p><a href="<?php echo $logoutAction ?>">Logga ut genom att klicka h&auml;r!</a></p>
<p><a href="javascript:history.go(-1);">Om inte, klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
  </div>
  <div class="story"></div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>