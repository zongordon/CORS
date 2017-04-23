<?php 
//Added header.php and news_sponsors_nav.php as includes.
ob_start();

if (!isset($_SESSION)) {
  session_start();
}
require_once('Connections/DBconnection.php');
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsCompetition = "SELECT comp_raffled FROM competition WHERE comp_current = 1";
$rsCompetition = mysql_query($query_rsCompetition, $DBconnection) or die(mysql_error());
$row_rsCompetition = mysql_fetch_assoc($rsCompetition);

$pagetitle="Tuna Karate Cup";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup inställd, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes Several other code functions
//include_once('includes/functions.php');
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
    <h1>V&auml;lkomna till Tuna Karate Cup 2013</h1>
    <p>En t&auml;vling &ouml;ppen f&ouml;r samtliga karatestilar.</p>
    <p>Tuna Karate Cup &auml;r en barn- &amp; ungdomst&auml;vling, &ouml;ppen f&ouml;r deltagare mellan 7-17 &aring;r, som anordnas av Eskilstuna Karateklubb.    </p>
    <p>Medtag giltigt t&auml;vlingskort (fr&aring;n 14 &aring;r) d&aring; de inte finns att inf&ouml;rskaffa p&aring; plats!</p>
    <p>L&auml;nk till <a href="pdf/Inbjudan.pdf" target="_blank">inbjudan</a>, som pdf!</p>
  </div>
  <div class="story">
    <h2>Tid och plats</h2>
    <p>L&ouml;rdagen den 7 september 10.00 i Sporthallen i Eskilstuna, Hamngatan 17. <!-- L&auml;nk till planeringen per tatami (matta): <a href="pdf/Tidsplanering  3 tatami.pdf" title="Planering per tatami" target="_blank">h&auml;r</a><br /> -->
    Eniro-l&auml;nk till Sporthallen i Eskilstuna:&nbsp;<a href="http://kartor.eniro.se/query?&amp;mop=aq&amp;mapstate=7;16.509827987716445;59.37381173727085;s;16.497700272667146;59.37842577860646;16.52193483897792;59.36921500703069;877;629&amp;mapcomp=;;;Hamngatan;17;;63220;ESKILSTUNA;;;;;16.509827987716445;59.37381173727085;0;0;;ESKILSTUNA;maps_address.111176.11;0&amp;what=map_adr&amp;searchInMap=1&amp;geo_area=hamngatan 17, eskilstuna&amp;stq=0&amp;pis=0" target="_blank">h&auml;r</a><br />
    <strong>H&auml;r hittar du prelimin&auml;r planering per matta</strong>: <a href="pdf/Tidsplanering.pdf" target="_blank">l&auml;nk</a></p>
    <h2>Anm&auml;lning av t&auml;vlande</h2>
    <p>Anm&auml;lan g&ouml;rs h&auml;r <strong>online</strong> av klubbledare/coach genom att f&ouml;rst skapa ett klubbkonto via l&auml;nken &quot;Nytt konto&quot;, logga in och d&auml;refter g&ouml;ra anm&auml;lningar via l&auml;nken &quot;Anm&auml;lan&quot;. Ni kan g&ouml;ra f&ouml;r&auml;ndringar av era anm&auml;lningar &auml;nda fram till sista anm&auml;lningsdagen. OBS! En coach per fem t&auml;vlande. Ange namn p&aring; coacher i anm&auml;lan. <br />
    Var ni med vid Tuna Karate Cup 2010, s&aring; finns redan de t&auml;vlande ni la upp d&aring; kopplade till ert konto och ni beh&ouml;ver bara v&auml;lja t&auml;vlingsklasser och sedan &auml;r anm&auml;lan klar!</p>
    <h2>Sista anm&auml;lningsdag</h2>
    <p>Anm&auml;lan kan g&ouml;ras online av klubbledare/coach under rubriken &quot;Anm&auml;lan&quot; ovan, fram till <strong>4 september 2013</strong>. Ni kan INTE g&ouml;ra n&aring;gon efteranm&auml;lan!</p>
    <h2>T&auml;vlingsstegar</h2>
    <p>T&auml;vlingsstegar skriver du sj&auml;lv ut h&auml;r fr&aring;n t&auml;vlingssajten, via l&auml;nken t&auml;vlingsklasser ovan<?php if ($row_rsCompetition['comp_raffled'] == 1) { echo " eller som pdf <a href='pdf/Stegar.pdf' target='_blank'>h&auml;r</a>";} ?>! <strong>Inga stegar delas ut p&aring; plats!</strong></p>
    <h2>Startavgift och inbetalning</h2>
    <p>Avgiften &auml;r 200 kr per klass 7-13 &aring;r och 250 kr 14-17 &aring;r. Anv&auml;nd bankgironummer 176-6526 f&ouml;r inbetalning av t&auml;vlingsavgiften! Obs! <strong>Den ska vara inbetald senast p&aring; sista anm&auml;lningsdagen och vara gemensam fr&aring;n respektive klubb!</strong> Under l&auml;nken "Rapporter" hittar du, som inloggad, er kostnad.</p>
    <h2>Registrering, strykningar och inv&auml;gning</h2>
    <p>Klubben registreras p&aring; t&auml;vlingsdagen av <strong>en</strong> lagledare eller coach vid p&aring;visad plats i t&auml;vlingshallen. Coach/lagledare f&aring;r h&auml;r ta emot coachbrickor och tidsplanering. Eventuella strykningar meddelar ni vid registrering eller direkt p&aring; respektive matta. Obs! De t&auml;vlande m&aring;ste inte sj&auml;lva vara p&aring; plats vid registreringen! Inv&auml;gning och registrering sker p&aring; t&auml;vlingsdagen 08.30-09.30 samt 12.00- 12.30.</p>
    <h2>Entr&eacute; 50 kr</h2>
    <p>F&ouml;r &aring;sk&aring;dare (icke t&auml;vlande eller deras coacher) kostar det 50 kr i entr&eacute;. F&ouml;rskolebarn kommer in gratis!</p>
    <h2>Domare</h2>
    <p>F&ouml;ljande domare kommer att d&ouml;ma under Tuna Karate Cup 2013:</p>
    <ul>
      <li>Peter Sahlberg (chefsdomare)</li>
      <li>Emilio Merayo</li>
      <li>Kristoffer Jonsson</li>
      <li>Enrico Vatteroni</li>
      <li>Bengt Jansson</li>
      <li>Marcus Edgren</li>
      <li>Johan Ossa</li>
      <li>Rainer Hurra</li>
      <li>Kaveh Ghadakchian</li>
      <li>Biran Taheri</li>
      <li>Safet Kecap</li>
      <li>Lotta H&ouml;gstr&ouml;m</li>
      <li>Marcos Checa</li>
      <li>Ken Bokelius</li>
      <li>Nabil Chakir</li>
      <li>Matts Andersson</li>
      <li>Olov F&auml;st</li>
      <li>Ky Buon Tang</li> 
      <li>Andreas Fritsch</li> 
    </ul>
    <h2>Regler mm.</h2>
    <ul>
        <li>WKF <a href="http://iof2.idrottonline.se/ImageVaultFiles/id_30960/cf_78/Regler_Svenska_version_8.PDF" target=_blank>t&auml;vlingsregler</a> samt karatesektionens regeltill&auml;gg f&ouml;r <a href ="http://iof2.idrottonline.se/ImageVaultFiles/id_36146/cf_78/Barntill-gg_version_2013-03-08.PDF">barn och ungdom</a> (7 &ndash; 13 &aring;r)</li>
      <li>Kadetter och juniorer har inga krav p&aring; shitei kata, men ska utf&ouml;ra olika kata i varje omg&aring;ng</li>
      <li>Inga krav p&aring; olika kata f&ouml;r barnklasserna</li>
      <li>T&auml;vlingskort g&auml;ller f&ouml;r kadetter och juniorer (fr&aring;n 14 &aring;r) f&ouml;r b&aring;de kata och kumite</li>
      <li>&Aring;terkval g&auml;ller f&ouml;r kadetter och juniorer</li>
      <li>Vi f&ouml;rbeh&aring;ller oss r&auml;tten att &auml;ndra t&auml;vlingsklasserna vid f&ouml;r f&aring; deltagare</li>
    </ul>
    <h2>Boende i Eskilstuna</h2>
    <p>Kontakta turistbyr&aring;n f&ouml;r information om boende: <a href="http://www.eskilstuna.se/sv/Uppleva-och-gora/Turistbyra/" target="_blank">Turistbyr&aring;n i Eskilstuna</a>!</p>
</div>
</div>
<?php include_once("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>