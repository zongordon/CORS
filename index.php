﻿<?php 
//Changed imformation and corrected links

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
    <h1>V&auml;lkomna till <?php echo $comp_name;?>!</h1>
    <p>En barn- och ungdomst&auml;vling f&ouml;r samtliga karatestilar och som anordnas av <?php echo $comp_arranger;?>.<br>
    Medtag giltigt t&auml;vlingskort (fr&aring;n 14 &aring;r) d&aring; de inte finns att inf&ouml;rskaffa p&aring; plats! L&auml;nk till Karatef&ouml;rbundets sida: <a href="https://www.karatesweden.se/tavla-i-karate/tavlingslicens/" target=_blank>l&auml;nk</a><br>
    L&auml;nk till <a href="pdf/Inbjudan.pdf" target="_blank">inbjudan</a>, som pdf!</p>
    <h2>V&auml;skf&ouml;rbud </h2>
    <p>V&auml;skf&ouml;rbud g&auml;ller fr&aring;n den 1 november p&aring; st&ouml;rre idrottsevenemang i Sverige. Publiken f&aring;r inte ta in n&aring;gra v&auml;skor st&ouml;rre &auml;n 40 x 20 x 20 cm och mindre v&auml;skor kommer att bli 
        genoms&ouml;kta vid entrén. Obs! Detta g&auml;ller inte de t&auml;vlande! Referens: <a href="https://polisen.se/lagar-och-regler/vaskforbud-pa-storre-evenemang/" target="_blank">Polisen.se</a></p>
  </div>
<iframe width="60%" height="315" src="https://www.youtube.com/embed/pl9HgW5pgTQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  <div class="story">
    <h2>Tid och plats</h2>
    <p>L&ouml;rdagen <?php echo $comp_start_date?> kl. <?php echo $comp_start_time?> i <a href="https://stigasportsarena.eskilstuna.se/" title="Volvo CE Arena" target="_blank">
    Volvo CE Arena (före detta Stiga Sports Arena)</a> i Eskilstuna, Arenatorget 1. <strong>T&auml;vlingen h&aring;lls i A-hallen (A1) och uppv&auml;rmning i C-hallen.</strong>
    <a href="https://kartor.eniro.se/?c=59.379165,16.508234&z=14&q=%22stiga%20sports%20arena%22;232568161;geo" target="_blank">Eniro-l&auml;nk</a>&nbsp;/
    <a href="https://maps.app.goo.gl/S7UyQWvBJpGppB1Z9" target="_blank">Google-l&auml;nk</a>&nbsp;till Volvo CE Arena i Eskilstuna.<br/>
        <!-- -->
    L&auml;nk till <strong>planeringen per tatami (matta): </strong><a href="pdf/Tidsplanering.pdf" title="Planering per tatami" target="_blank">h&auml;r</a></p>
    <h2>Anm&auml;lning av t&auml;vlande</h2>
    <p>Anm&auml;lan g&ouml;rs h&auml;r p&aring; <strong>t&auml;vlingssajten</strong> av klubbledare/coach genom att f&ouml;rst skapa ett klubbkonto 
        via l&auml;nken &quot;Nytt konto&quot;, logga in och d&auml;refter g&ouml;ra anm&auml;lningar via l&auml;nken 
        &quot;Anm&auml;lan&quot;. Ni kan g&ouml;ra f&ouml;r&auml;ndringar av era anm&auml;lningar &auml;nda fram till sista 
        anm&auml;lningsdagen. OBS! En coach per fem t&auml;vlande. Ange namn p&aring; coacher i anm&auml;lan. <br />
    <h2>Sista anm&auml;lningsdag</h2>
    <p>Anm&auml;lan kan g&ouml;ras online av klubbledare/coach under rubriken &quot;Anm&auml;lan&quot; ovan, fram till 
        <strong><?php echo $comp_end_reg_date?></strong>. Ni kan INTE g&ouml;ra n&aring;gon efteranm&auml;lan!</p>
    <h2>T&auml;vlingsstegar</h2>
    <p><?php if ($comp_raffled === 1) { echo 'Nu &auml;r lottningen avklarad och du kan sj&auml;lv skriva ut t&auml;vlingsstegar via l&auml;nken Lottning ovan'; } else { echo 'N&auml;r lottningen &auml;r avklarad kan du sj&auml;lv skriva ut t&auml;vlingsstegar h&auml;r fr&aring;n t&auml;vlingssajten'; } ?>, direkt fr&aring;n sidan eller som pdf! <br><strong>Inga stegar delas ut p&aring; plats!</strong></p>
    <h2>Startavgift och inbetalning</h2>
    <p>Avgiften &auml;r 300 kr per individuell klass och 600 kr per lagklass. Anv&auml;nd bankgironummer 176-6526 eller Swish till 123 26 70 180 f&ouml;r inbetalning av t&auml;vlingsavgiften! Obs! <strong>Den ska vara inbetald senast p&aring; sista anm&auml;lningsdagen och vara gemensam fr&aring;n respektive klubb!</strong> Under l&auml;nken "Rapporter" hittar du, som inloggad, er kostnad.</p>
    <h2>Registrering, strykningar och inv&auml;gning</h2>
    <p>Klubben registreras p&aring; t&auml;vlingsdagen av <strong>en</strong> lagledare eller coach vid p&aring;visad plats i t&auml;vlingshallen. Coach/lagledare f&aring;r h&auml;r ta emot tidsplanering. Eventuella strykningar meddelar ni vid registrering eller direkt p&aring; respektive matta. Obs! De t&auml;vlande m&aring;ste inte sj&auml;lva vara p&aring; plats vid registreringen! Inv&auml;gning och registrering sker p&aring; t&auml;vlingsdagen 08.30-09.30 samt 12.00- 12.30.</p>
    <h2>Entr&eacute; 50 kr</h2>
    <p>F&ouml;r &aring;sk&aring;dare (icke t&auml;vlande eller deras coacher) kostar det 50 kr i entr&eacute;. F&ouml;rskolebarn kommer in gratis!</p>
    <h2>Regler mm.</h2>
    <ul>
      <li>WKF t&auml;vlingsregler samt karatesektionens regeltill&auml;gg f&ouml;r barn och ungdom (7 &ndash; 13 &aring;r) - <a href="http://www.swekarate.se/tavling/reglerdokument/" target=_blank>l&auml;nk</a></li>
      <li>Obligatorisk coachlicens g&auml;ller f&ouml;r samtliga som ska coacha under t&auml;vlingen - <a href="https://www.karatesweden.se/tavla-i-karate/for-coacher/coachlicens/" target=_blank>Coachlicens</a></li>
      <li>Alla evenemang i Svenska Karatef&ouml;rbundets regi skall vara trygga f&ouml;r alla medlemmar och deltagare samt helt fria fr&aring;n alla former av psykiska och fysiska kr&auml;nkningar, verbala trakasserier och maktmissbruk. Om n&aring;gon skulle beh&ouml;va rapportera en h&auml;ndelse eller f&aring; ytterligare information ang&aring;ende F&ouml;rbundets skyddshanteringssystem v.g. kontakta Ove Viggedal, f&ouml;rbundets huvudskyddsombud i dessa fr&aring;gor. Maila ditt &auml;rende till <a href="mailto:safety@swekarate.se." title="Skyddad adress">safety@swekarate.se.</a></li>
      <li>Kadetter och juniorer ska utf&ouml;ra olika kata i varje omg&aring;ng</li>
      <li>Inga krav p&aring; olika kata f&ouml;r barnklasserna</li>
      <li>T&auml;vlingskort g&auml;ller f&ouml;r kadetter och juniorer (fr&aring;n 14 &aring;r) f&ouml;r b&aring;de kata och kumite.  L&auml;nk till Karatef&ouml;rbundets sida: <a href="https://www.karatesweden.se/tavla-i-karate/tavlingslicens/" target=_blank>l&auml;nk</a></li>
      <li>Kadetter och juniorer: &aring;terkval och Round Robin (alla m&ouml;ter alla) vid f&auml;rre &auml;n fyra i klassen</li>
      <li>Duo kata (tv&aring; t&auml;vlande samtidigt) upp till 13 &aring;r</li>
      <li>Vi f&ouml;rbeh&aring;ller oss r&auml;tten att &auml;ndra t&auml;vlingsklasserna vid f&ouml;r f&aring; deltagare</li>
    </ul>
    <h2>Boende i Eskilstuna</h2>
    <p>Kontakta turistbyr&aring;n f&ouml;r information om boende: <a href="https://visiteskilstuna.se" target="_blank">Turistbyr&aring;n i Eskilstuna</a>!</p>
</div>
</div>
<?php 
$stmt_rsCurrentComp->closeCursor();
include_once("includes/footer.php");
?>
</body>
</html>
<?php ob_end_flush();?>
