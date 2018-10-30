<?php 
///Replaced $row_rsCurrentComp['xxx'] with $xxx defined in header.php
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
$pagetitle = "T&auml;vling";
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");
?>
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include_once("includes/navigation.php"); ?></div>
<div id="content">    
  <div class="feature">
    <img height="199" width="300" alt="" src="img/rotating/rotate.php" />
    <h1>V&auml;lkomna till <?php echo $comp_name;?>!</h1>
    <p>En barn-, ungdoms- &amp; vuxent&auml;vling f&ouml;r samtliga karatestilar, &ouml;ppen f&ouml;r deltagare som tillh&ouml;r Mellersta Karatef&ouml;rbundet och anordnas av <?php echo $comp_arranger;?>.    </p>
    <p>Medtag giltigt t&auml;vlingskort (fr&aring;n 14 &aring;r) d&aring; de inte finns att inf&ouml;rskaffa p&aring; plats! L&auml;nk till Karatef&ouml;rbundets sida: <a href="http://iof2.idrottonline.se/SvenskaKarateforbundet/Tavling/Tavlingslicens/" target=_blank>l&auml;nk</a></p>
    <p>L&auml;nk till <a href="pdf/Inbjudan.pdf" target="_blank">inbjudan</a>, som pdf!</p>
  </div>
  <div class="story">
    <h2>Tid och plats</h2>
    <p>L&ouml;rdagen <?php echo $comp_start_date?> kl. 10.00 i Munktellarenan i Eskilstuna, Verkstadsgatan 5. <!-- --> L&auml;nk till <strong>planeringen per tatami (matta):</strong><a href="pdf/Tidsplanering.pdf" title="Planering per tatami" target="_blank">h&auml;r</a><br />
    Eniro-l&auml;nk till Munktellarenan i Eskilstuna:&nbsp;<a href="https://kartor.eniro.se/?c=59.378028,16.508754&z=14&q=%22verkstadsgatan,%205,%20eskilstuna%22;209077897;geo" target="_blank">h&auml;r</a></p>
    <h2>Anm&auml;lning av t&auml;vlande</h2>
    <p>Anm&auml;lan g&ouml;rs h&auml;r <strong>online</strong> av klubbledare/coach genom att f&ouml;rst skapa ett klubbkonto 
        via l&auml;nken &quot;Nytt konto&quot;, logga in och d&auml;refter g&ouml;ra anm&auml;lningar via l&auml;nken 
        &quot;Anm&auml;lan&quot;. Ni kan g&ouml;ra f&ouml;r&auml;ndringar av era anm&auml;lningar &auml;nda fram till sista 
        anm&auml;lningsdagen. OBS! En coach per fem t&auml;vlande. Ange namn p&aring; coacher i anm&auml;lan. <br />
    <h2>Sista anm&auml;lningsdag</h2>
    <p>Anm&auml;lan kan g&ouml;ras online av klubbledare/coach under rubriken &quot;Anm&auml;lan&quot; ovan, fram till 
        <strong><?php echo $comp_end_reg_date?></strong>. Ni kan INTE g&ouml;ra n&aring;gon efteranm&auml;lan!</p>
    <h2>T&auml;vlingsstegar</h2>
    <p>N&auml;r lottningen &auml;r avklarad kan du sj&auml;lv skriva ut t&auml;vlingsstegar h&auml;r fr&aring;n t&auml;vlingssajten, via l&auml;nken t&auml;vlingsklasser ovan<?php if ($comp_raffled === 1) { echo "<strong> eller som pdf <a href='pdf/Stegar.pdf' target='_blank'>h&auml;r</a>";} ?>! Inga stegar delas ut p&aring; plats!</strong></p>
    <h2>Startavgift och inbetalning</h2>
    <p>Avgiften &auml;r 200 kr per klass 7-13 &aring;r och 250 kr fr&aring;n 14 &aring;r. Anv&auml;nd bankgironummer 695‐9175 f&ouml;r inbetalning av t&auml;vlingsavgiften! Obs! <strong>Den ska vara inbetald senast p&aring; sista anm&auml;lningsdagen och vara gemensam fr&aring;n respektive klubb!</strong> Under l&auml;nken "Rapporter" hittar du, som inloggad, er kostnad.</p>
    <h2>Registrering, strykningar och inv&auml;gning</h2>
    <p>Klubben registreras p&aring; t&auml;vlingsdagen av <strong>en</strong> lagledare eller coach vid p&aring;visad plats i t&auml;vlingshallen. Coach/lagledare f&aring;r h&auml;r ta emot coachbrickor och tidsplanering. Eventuella strykningar meddelar ni vid registrering eller direkt p&aring; respektive matta. Obs! De t&auml;vlande m&aring;ste inte sj&auml;lva vara p&aring; plats vid registreringen! Inv&auml;gning och registrering sker p&aring; t&auml;vlingsdagen 08.30-09.30 samt 12.00- 12.30.</p>
    <h2>Entr&eacute; 40 kr</h2>
    <p>F&ouml;r &aring;sk&aring;dare (icke t&auml;vlande eller deras coacher) kostar det 40 kr i entr&eacute;. F&ouml;rskolebarn kommer in gratis!</p>
    <h2>Regler mm.</h2>
    <ul>
        <li>WKF t&auml;vlingsregler samt karatesektionens regeltill&auml;gg f&ouml;r barn och ungdom (7 &ndash; 13 &aring;r) - <a href="http://www.swekarate.se/tavling/reglerdokument/" target=_blank>l&auml;nk</a></li>
      <li>Kadetter och juniorer har inga krav p&aring; shitei kata, men ska utf&ouml;ra olika kata i varje omg&aring;ng</li>
      <li>Inga krav p&aring; olika kata f&ouml;r barnklasserna</li>
      <li>T&auml;vlingskort g&auml;ller f&ouml;r kadetter, juniorer och vuxna (fr&aring;n 14 &aring;r) f&ouml;r b&aring;de kata och kumite.  L&auml;nk till Karatef&ouml;rbundets sida: <a href="http://iof2.idrottonline.se/SvenskaKarateforbundet/Tavling/Tavlingslicens/" target=_blank>l&auml;nk</a></li>
      <li>&Aring;terkval g&auml;ller f&ouml;r kadetter, juniorer och vuxna</li>
      <li>Round Robin (alla m&ouml;ter alla) vid f&auml;rre &auml;n fem i klassen</li>
      <li>Duo kata (tv&aring; t&auml;vlande samtidigt) upp till 12 &aring;r</li>
      <li>Vi f&ouml;rbeh&aring;ller oss r&auml;tten att &auml;ndra t&auml;vlingsklasserna vid f&ouml;r f&aring; deltagare</li>
    </ul>
    <h2>Boende i Eskilstuna</h2>
    <p>Kontakta turistbyr&aring;n f&ouml;r information om boende: <a href="https://eskilstuna.nu/om-oss/eskilstuna-tourist-information/" target="_blank">Turistbyr&aring;n i Eskilstuna</a>!</p>
</div>
</div>
<?php 
include_once("includes/footer.php");
$stmt_rsCurrentComp->closeCursor();
$DBconnection = null;
?>
</body>
</html>
<?php ob_end_flush();?>