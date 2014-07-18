<?php
//Adjusted to display page title

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ><head><?php $pagetitle="Kontakter f&ouml;r t&auml;vlingen"?>
<meta http-equiv="Content-Type" content="; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="visa kontakter att kontakta, tuna karate cup, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" />
</head>
<?php include("includes/header.php"); ?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include("includes/navigation.php"); ?></div>    
<div id="content"> 
<div class="feature"><img height="199" width="300" alt="" src="img/rotating/rotate.php" />
      <h3>Kontakter</h3> 
      <p> F&ouml;r mer information: kontakta Bosse 070-592 59 10, Frank 073-558 59 34 eller skicka f&ouml;rfr&aring;gan till tunacup@karateklubben.com.</p> 
  </div> 
  <div class="story"></div> 
</div>
<?php include("includes/footer.php");?>
</body>
</html>