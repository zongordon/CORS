<?php

global $editFormAction;

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Logglista - admin";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, logglista över login för administratörer, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

//Set initial sorting (ORDER BY) and change if new sort order is selected in dropdown list
$sorting = "comp_current DESC, comp_name, login_timestamp";
if (isset($_GET['sorting'])) {
  $sorting = $_GET['sorting'];
}
//Select all logins and related data for respective competition
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsLogins = "SELECT com.comp_id, com.comp_name, com.comp_current, a.club_name, a.contact_name, l.ip_address, l.login_timestamp FROM loginlog AS l INNER JOIN competition AS com USING (comp_id) INNER JOIN account AS a USING (account_id) ORDER BY $sorting";
$rsLogins = mysql_query($query_rsLogins, $DBconnection) or die(mysql_error());
$row_rsLogins = mysql_fetch_assoc($rsLogins);
$totalRows_rsLogins = mysql_num_rows($rsLogins);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<?php // Show if recordset empty
      if ($totalRows_rsLogins == 0) { ?>
<p>Det finns inga loggar att visa &auml;n!</p>
<?php // Show if recordset empty
      } 
// Show if recordset not empty 
if ($totalRows_rsLogins > 0) { ?> 
    <h3>Befintliga loggar</h3>
    <p>&Auml;ndra sorteringen genom att v&auml;lja i listan och klicka p&aring; sortera.</p>
<form action="<?php echo $editFormAction; ?>" method="GET" enctype="application/x-www-form-urlencoded" name="SelectSorting" id="SelectSorting">
  <table width="200" border="0">
    <tr>
      <td valign="middle">Sortering</td>
      <td><label>
        <select name="sorting" id="sorting">
      <option value="comp_current DESC, comp_name, login_timestamp">Aktuell t&auml;vling f&ouml;rst</option>
      <option value="comp_name, login_timestamp">T&auml;vlingsnamn</option>
</select>
      </label></td>
      <td><input type="submit" name="submit" id="submit" value="Sortera" /></td>
    </tr>
  </table>
</form>
    <table width="100%" border="1">
      <tr>
        <td><strong>T&auml;vling</strong></td>                  
        <td><strong>Klubbnamn</strong></td>
        <td><strong>Kontaktnamn</strong></td>
        <td><strong>IP-adress</strong></td>
        <td><strong>Login-tid</strong></td>
      </tr>
      <?php do { ?>
  <tr>
    <td><?php echo $row_rsLogins['comp_name']; ?></td>      
          <td><?php echo $row_rsLogins['club_name']; ?></td>
          <td><?php echo $row_rsLogins['contact_name']; ?></td>
          <td><?php echo $row_rsLogins['ip_address']; ?></td>
          <td><?php echo $row_rsLogins['login_timestamp']; ?></td>
  </tr>
  <?php } while ($row_rsLogins = mysql_fetch_assoc($rsLogins)); ?>
    </table>
<?php 
mysql_free_result($rsLogins);    
// Show if recordset not empty
}  
?>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>