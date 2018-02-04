<?php
//Moved meta description and keywords to header.php

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$colname_rsAccount = "-1";
if (isset($_GET['account_id'])) {
  $colname_rsAccount = $_GET['account_id'];
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsAccount = sprintf("SELECT * FROM account WHERE account_id = %s", GetSQLValueString($colname_rsAccount, "int"));
$rsAccount = mysql_query($query_rsAccount, $DBconnection) or die(mysql_error());
$row_rsAccount = mysql_fetch_assoc($rsAccount);
$totalRows_rsAccount = mysql_num_rows($rsAccount);

$query_rsActiveComp = "SELECT comp_id, comp_name FROM competition WHERE comp_current = 1 ORDER BY comp_start_date ASC";
$rsActiveComp = mysql_query($query_rsActiveComp, $DBconnection) or die(mysql_error());
$row_rsActiveComp = mysql_fetch_assoc($rsActiveComp);
$totalRows_rsActiveComp = mysql_num_rows($rsActiveComp);

$colname_rsActiveCompetitions = "-1";
if (isset($_GET['1'])) {
  $colname_rsActiveCompetitions = $_GET['1'];
}
$pagetitle="Lista konton - admin";
// Includes Several code functions
include_once('includes/functions.php');
//Includes Restrict access code function
include_once('includes/restrict_access.php');
// Includes HTML Head
include_once('includes/header.php');
//Include top navigation links, News and sponsor sections
include_once("includes/news_sponsors_nav.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">      
    <div class="feature">
<h3>Informationen om kontot</h3>
      <p>Detta &auml;r informationen som har sparats om kontot.</p>
      <form id="account" name="account" method="post">
        <table width="400" border="0">
          <tr>
            <td>Klubbens namn</td>
            <td><?php echo $row_rsAccount['club_name']; ?></td>
          </tr>
          <tr>
            <td>Kontaktperson</td>
            <td valign="top"><?php echo $row_rsAccount['contact_name']; ?></td>
          </tr>
          <tr>
            <td>E-post</td>
            <td valign="top"><?php echo $row_rsAccount['contact_email']; ?></td>
          </tr>
          <tr>
            <td>Telefon</td>
            <td><?php echo $row_rsAccount['contact_phone']; ?></td>
          </tr>
          <tr>
            <td>Anv&auml;ndarnamn</td>
            <td><?php echo $row_rsAccount['user_name']; ?></td>
          </tr>
          <tr>
            <td>Administrat&ouml;rskonto</td>
            <td><label>
<input <?php if (!(strcmp($row_rsAccount['access_level'],1))) {echo "checked=\"checked\"";} ?> name="access_level" type="checkbox" id="access_level" value="1" />
            </label></td>
          </tr>
          <tr>
            <td>Aktivt konto</td>
            <td><label>
              <input <?php if (!(strcmp($row_rsAccount['active'],1))) {echo "checked=\"checked\"";} ?> name="active" type="checkbox" id="active3" value="1" />
            </label></td>
          </tr>
          <tr>
            <td>Bekr&auml;ftat konto</td>
            <td><label>
<input <?php if (!(strcmp($row_rsAccount['confirmed'],1))) {echo "checked=\"checked\"";} ?> name="confirmed" type="checkbox" id="confirmed" value="1" />
            </label></td>
          </tr>
        </table>
    </form>
  </div>
  <div class="story">
    <h3><a href="AccountUpdate.php">Om du inte &auml;r n&ouml;jd, s&aring; g&aring; tillbaka h&auml;r!</a></h3>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsAccount);
mysql_free_result($rsActiveComp); 
?>