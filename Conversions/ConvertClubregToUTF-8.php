<?php
//Create DB connection
require_once('Connections/DBconnection.php');

$sql = ' SELECT `club_reg_id`, `coach_names` FROM `clubregistration` ';
mysql_select_db($database_DBconnection, $DBconnection);
$Result1 = mysql_query($sql, $DBconnection) or die(mysql_error());
echo '<table><thead><tr><td>Id</td>
<td>Old coach_names</td>
<td>New coach_names</td>
</tr>
</thead>
<tbody>';
while($row = mysql_fetch_array($Result1)){
$ID = $row["club_reg_id"];
$coach_names = stripslashes($row["coach_names"]);
$newcoach_names = utf8_encode($coach_names);
echo "
<tr>
<td>$ID</td>
<td>$coach_names</td>
<td>$newcoach_names</td>
</tr>
";
$sql2 = "UPDATE `clubregistration` SET `coach_names` = '".addslashes($newcoach_names)."' WHERE `club_reg_id` = $ID ";
  mysql_select_db($database_DBconnection, $DBconnection);
  $Result2 = mysql_query($sql2, $DBconnection) or die(mysql_error());
}
echo '</tbody>
</table>'
?>