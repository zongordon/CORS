<?php 
//Adjusted to display page title
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><?php $pagetitle="Tuna Karate Cup"?>
<meta http-equiv="Content-Type" content="; charset=ISO-8859-1" />
<meta name="description" content="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall." />
<meta name="keywords" content="tuna karate cup inställd, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp" />
<title><?php echo $pagetitle ?></title>
<link rel="stylesheet" href="3col_leftNav.css" type="text/css" /></head>
<?php include("includes/header.php"); ?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<div id="localNav"><?php include("includes/navigation.php"); ?></div>    
<div id="content">
  <div class="feature">
    <img src="img/DSC_0069.jpg" alt="" width="300" height="253" />
    <h1>&nbsp;</h1>
    <h1>Tuna Karate Cup ligger i malp&aring;se tillvidare!</h1>
    <p>Efter beslut att st&auml;lla in senaste t&auml;vlingen, ligger den i malp&aring;se tillvidare.</p>
    <p>Om det visar sig att v&aring;r t&auml;vling trots allt har en plats i karate-Sverige kommer vi att f&ouml;rs&ouml;ka igen.</p>
  </div>
  <div class="story"></div>
</div>
<?php include("includes/footer.php");?>
</html>
<?php ob_end_flush();?>