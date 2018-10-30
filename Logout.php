<?php
//Moved meta description and keywords to header.php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = filter_input(INPUT_SERVER,'PHP_SELF')."?doLogout=true";
if (filter_input(INPUT_SERVER, 'QUERY_STRING') != ''){
  $logoutAction .="&". htmlentities(filter_input(INPUT_SERVER,'QUERY_STRING'));
}

if (filter_input(INPUT_GET,'doLogout') == 'true'){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION = Array();    
  $logoutGoTo = "Login.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
$pagetitle="Logga ut";
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">
  <div class="feature">
<h3>Vill du logga ut fr&aring;n ditt klubbkonto s&aring; klicka p&aring; l&auml;nken nedan!</h3>
<p><a href="<?php echo $logoutAction ?>">Logga ut genom att klicka h&auml;r!</a></p>
<p><a href="javascript:history.go(-1);">Om inte, klicka h&auml;r s&aring; kommer du tillbaka till f&ouml;reg&aring;ende sida!</a></p>
  </div>
  <div class="story"></div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>