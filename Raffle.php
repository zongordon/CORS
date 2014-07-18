<?php 
//Added form to set if the competition is raffled or not which will affect if links to elimination ladders are shown or not

ob_start();
//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

$pagetitle="Hantera lottning";
$pagedescription="Tuna Karate Cup som arrangeras av Eskilstuna Karateklubb i Eskilstuna Sporthall.";
$pagekeywords="tuna karate cup, Hantera lottning, karate, eskilstuna, sporthallen, wado, självförsvar, kampsport, budo, karateklubb, sverige, idrott, sport, kamp";
// Includes HTML Head, and several other code functions
include_once('includes/functions.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateDrawing")) {
  $updateSQL = sprintf("UPDATE clubregistration SET club_startorder=%s WHERE club_reg_id=%s",
                       GetSQLValueString($_POST['club_startorder'], "int"),
                       GetSQLValueString($_POST['club_reg_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
}
if ((isset($_POST["MM_RaffleDone"])) && ($_POST["MM_RaffleDone"] == "RaffleDone")) {
  $updateSQL = sprintf("UPDATE competition SET comp_raffled=%s WHERE comp_id=%s",
                       GetSQLValueString($_POST['comp_raffled'], "int"),
                       GetSQLValueString($_POST['comp_id'], "int"));

  mysql_select_db($database_DBconnection, $DBconnection);
  $Result1 = mysql_query($updateSQL, $DBconnection) or die(mysql_error());
}
mysql_select_db($database_DBconnection, $DBconnection);
$query_rsClubRegs = "SELECT comp_id, comp_raffled, club_reg_id, club_name, club_startorder FROM clubregistration INNER JOIN account USING (account_id) INNER JOIN competition USING (comp_id) WHERE comp_current = 1 ORDER BY club_startorder, club_name";
$rsClubRegs = mysql_query($query_rsClubRegs, $DBconnection) or die(mysql_error());
$row_rsClubRegs = mysql_fetch_assoc($rsClubRegs);
$totalRows_rsClubRegs = mysql_num_rows($rsClubRegs);
?>
<!-- Include top navigation links, News and sponsor sections -->
<?php include("includes/header.php");?> 
<!-- start page -->
<div id="pageName"><h1><?php echo $pagetitle?></h1></div>
<!-- Include different navigation links depending on authority  -->
<div id="localNav"><?php include("includes/navigation.php"); ?></div>
<div id="content">    
    <div class="feature">
<h3>Hantera lottningen av klubbarnas inb&ouml;rdes startordning i Tuna Karate Cup</h3>
<p>&Auml;ndra status n&auml;r lottningen &auml;r klar och klicka p&aring; Spara!</p>
    <table border="0">
      <tr>
          <td><strong>Status p&aring; lottning</strong></td>
          <td>&nbsp;</td>
      </tr>
  <form id="RaffleDone" name="RaffleDone" method="POST" action="<?php echo $editFormAction; ?>">
      <tr>
        <td><label>
            <select name="comp_raffled" type="int" id="comp_raffled" ">
            <option value="0" <?php if (!(strcmp("0", $row_rsClubRegs['comp_raffled']))) {echo "selected=\"selected\"";} ?>>Lottning inte klar</option>
            <option value="1" <?php if (!(strcmp("1", $row_rsClubRegs['comp_raffled']))) {echo "selected=\"selected\"";} ?>>Lottning klar</option>
          </label></td>
        <td><input type="submit" name="Spara" id="Spara" value="Spara" />
          <input name="comp_id" type="hidden" id="comp_id" value="<?php echo $row_rsClubRegs['comp_id']; ?>" /></td>
      </tr>
    <input type="hidden" name="MM_RaffleDone" value="RaffleDone"/>
  </form>
    </table>
<p>V&auml;lj startordning f&ouml;r  klubben och klicka p&aring; Spara!</p>
    <table width="300" border="0">
      <tr>
        <td><strong>Klubb</strong></td>
        <td><strong>Lottning</strong></td>
        <td>&nbsp;</td>
        </tr>
<?php do { ?>
  <form id="UpdateDrawing" name="UpdateDrawing" method="POST" action="<?php echo $editFormAction; ?>">
      <tr>
        <td><label>
          <input type="text" name="club_name" id="club_name" value="<?php echo $row_rsClubRegs['club_name']; ?>" size="55"/>
          </label></td>
        <td><label>
          <input name="club_startorder" type="text" id="club_startorder" value="<?php echo $row_rsClubRegs['club_startorder']; ?>" size="2" maxlength="2" />
          </label></td>
        <td><input type="submit" name="Spara" id="Spara" value="Spara" />
          <input name="club_reg_id" type="hidden" id="club_reg_id" value="<?php echo $row_rsClubRegs['club_reg_id']; ?>" /></td>
        </tr>
    <input type="hidden" name="MM_update" value="UpdateDrawing"/>
  </form>
  <?php } while ($row_rsClubRegs = mysql_fetch_assoc($rsClubRegs)); ?>
      </table>  
<p>&nbsp;</p>
  </div>
  <div class="story">
    <h3>&nbsp;</h3>
    <p>&nbsp;</p>
</div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php
mysql_free_result($rsClubRegs);
ob_end_flush();?>