<?php
//Removed kill DB as it's included in footer.php

//Access level top administrator
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

//Set initial sorting (ORDER BY) and change if new sort order is selected in dropdown list
$sorting = "comp_current DESC, login_timestamp DESC";
if (filter_input(INPUT_GET,'sorting')) {
  $sorting = filter_input(INPUT_GET,'sorting');
}
//Catch anything wrong with query
try {
//Select all logins and related data for respective competition    
require('Connections/DBconnection.php');           
$query = "SELECT com.comp_id, com.comp_name, com.comp_current, a.club_name, a.contact_name, l.ip_address, l.login_timestamp FROM loginlog AS l INNER JOIN competition AS com USING (comp_id) INNER JOIN account AS a USING (account_id) ORDER BY $sorting";
$stmt_rsLogins = $DBconnection->query($query);
$totalRows_rsLogins = $stmt_rsLogins->rowCount();
}   
catch(PDOException $ex) {
    echo "An Error occured with queryX: ".$ex->getMessage();
}   

$pagetitle="Logglista - admin";
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
      <option value="comp_current DESC, login_timestamp DESC"<?php if (!(strcmp($sorting, "comp_current DESC, login_timestamp DESC"))) {echo "selected=\"selected\"";} ?>>Aktuell t&auml;vling f&ouml;rst</option>
      <option value="comp_name, login_timestamp DESC"<?php if (!(strcmp($sorting, "comp_name, login_timestamp DESC"))) {echo "selected=\"selected\"";} ?>>T&auml;vlingsnamn</option>            
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
<?php while($row_rsLogins = $stmt_rsLogins->fetch(PDO::FETCH_ASSOC)) { ?>
  <tr>
    <td><?php echo $row_rsLogins['comp_name']; ?></td>      
          <td><?php echo $row_rsLogins['club_name']; ?></td>
          <td><?php echo $row_rsLogins['contact_name']; ?></td>
          <td><?php echo $row_rsLogins['ip_address']; ?></td>
          <td><?php echo $row_rsLogins['login_timestamp']; ?></td>
  </tr>
<?php } ?>
    </table>
<?php 
// Show if recordset not empty
}  
?>
  </div>
</div>
<?php 
//Kill statement
$stmt_rsLogins->closeCursor();
include("includes/footer.php");
?>
</body>
</html>