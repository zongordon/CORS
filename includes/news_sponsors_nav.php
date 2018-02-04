<?php
//Moved news and sponsor code from header.php file
//Adapted sql query to PHP 7 (PDO) and added minor error handling.

require_once('Connections/DBconnection.php');

if (!isset($_SESSION)) {
  session_start();
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//Catch anything wrong with DB connection
try {
// Select all messages and comp_id for the current competition
$query = "SELECT message_subject FROM messages AS m INNER JOIN competition AS co ON m.comp_id = co.comp_id WHERE co.comp_current = 1 AND message_how = 'SiteOnly' OR co.comp_current = 1 AND message_how = 'SiteAndEmail' ORDER BY message_timestamp DESC";
$stmt_rsLatestNews = $DBconnection->query($query);
$totalRows_rsLatestNews = $stmt_rsLatestNews->rowCount();
}   catch(PDOException $ex) {
    echo "An Error occured!"; //user friendly message
    //some_logging_function($ex->getMessage());
    }
?>
<body>
<div id="masthead">
    <a href="/"><img src="img/Banner_L.png" alt="Left Logo" width="98" height="90" hspace="10"></a>
    <a href="/"><img src="img/Banner_M.jpg" alt="Middle Logo" width="553" height="90"></a>
    <a href="/"><img src="img/Banner_R.gif" alt="Right Logo" width="91" height="90" hspace="10"></a>
</div>
<div id="globalNav"><a href="/">Hem</a>|<a href="News.php">Nyheter</a>|<a href="Contacts.php">Kontakter</a>|<a href="ClassesList.php">T&auml;vlingsklasser</a>|<a href="RegsAll.php">Startlistor</a>|<a href="Results.php">Resultat</a>|<a href="http://tunacup.karateklubben.com/support/" target="_blank">L&auml;nk till Helpdesk-sajt</a>|<a href="http://www.karateklubben.com" target="_blank">Eskilstuna Karateklubb</a>
</div>
<div id="headlines">
  <div id="latestnews">
      <h3>Senaste Nytt</h3><br/>      
<?php 
if ($totalRows_rsLatestNews == 0) { // Show if recordset empty ?>
        <h4>Det finns inga nyheter &auml;n!</h4>
<?php 
} 
if ($totalRows_rsLatestNews > 0) { // Show if recordset not empty 
        while($row_rsLatestNews = $stmt_rsLatestNews->fetch(PDO::FETCH_ASSOC)) {; 
            $news = $row_rsLatestNews['message_subject']; 
            if(strlen($news) > 50) { $news = substr($news,0,50)."...";}?>
        <h4><a href="News.php"><?php echo $news; ?></a></h4>    
<?php   }
}
$stmt_rsLatestNews->closeCursor();
$DBconnection = null;
?>
  </div><br/>
  <div id="sponsors">
      <h3>Huvudsponsor</h3>
      <p><a href="http://www.eka-knivar.se" target="_blank"><img src="img/sponsors/EKA-Logo.png" alt="EKA Knivar" width="150" height="58" border="0" /></a></p>
      <h3>&Ouml;vriga sponsorer</h3>
      <p><a href="http://www.lazyposters.se/" target="_blank"><img src="img/sponsors/lazyposters black red_mini.jpg" width="150" height="17" border="0" alt="Lazy Posters"></a></p>      
      <p><img src="img/sponsors/Dental_Estetik_small.png" alt="Dental Estetik" width="150" height="104" border="0" /></p>
<!--Hide code       
      <p><a href="http://www.dynamate-is.se/" target="_blank"><img src="img/DynaMate-IS.gif" width="150" height="33" border="0" alt="DynaMate Industrial Services;"></a></p>      
      <p><a href="http://gulasidorna.eniro.se/f/narkiniemi-elkonsult:4392999" target="_blank"><img src="img/nKon.gif" alt="Narkiniemi Elkonsult" width="210" height="68" border="0" /></a></p>
      <p><a href="http://gulasidorna.eniro.se/f/k08-entreprenad-ab:14539027?search_word=k08-entreprenad&geo_area=v%C3%A4ster%C3%A5s" target="_blank"><img src="img/k08-entreprenad.gif" alt="K08 Entreprenad AB, Anl&auml;ggningsarbeten f;ouml;r el och telekommunikation" width="210" height="21" border="0" /></a></p>
      <p><a href="http://swesafe.se/" target="_blank"><img src="img/SweSafe.jpg" width="210" height="53" border="0" alt="SweSafe: L&aring;st, Larmat, Skyddat"></a></p>
      <p><a href="http://www.foretagsfakta.se/Eskilstuna/Svets__Smide_i_Eskilstuna_AB/972728" target="_blank"><img src="img/Svets_Smide.jpg" width="210" height="68" border="0" alt="Svets & Smide, Eskilstuna"></a></p>  
-->
  </div>
</div>