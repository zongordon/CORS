<?php
//Create DB connection
require_once('Connections/DBconnection.php');

$sql = ' SELECT `account_id`, `user_name`, `user_password`, `contact_name`, `club_name` FROM `account` ';
mysql_select_db($database_DBconnection, $DBconnection);
$Result1 = mysql_query($sql, $DBconnection) or die(mysql_error());
echo '<table><thead><tr><td>Id</td>
<td>Old user_name</td>
<td>New user_name</td>
<td>Old user_password</td>
<td>New user_password</td>
<td>Old contact_name</td>
<td>New contact_name</td>
<td>Old club_name</td>
<td>New club_name</td>
</tr>
</thead>
<tbody>';
while($row = mysql_fetch_array($Result1)){
$ID = $row["account_id"];

$user_name = stripslashes($row["user_name"]);
$newuser_name = utf8_encode($user_name);
$user_password = stripslashes($row["user_password"]);
$newuser_password = utf8_encode($user_password);
$contact_name = stripslashes($row["contact_name"]);
$newcontact_name = utf8_encode($contact_name);
$club_name = stripslashes($row["club_name"]);
$newclub_name = utf8_encode($club_name);
echo "
<tr>
<td>$ID</td>
<td>$user_name</td>
<td>$newuser_name</td>
<td>$user_password</td>
<td>$newuser_password</td>
<td>$contact_name</td>
<td>$newcontact_name</td>
<td>$club_name</td>
<td>$newclub_name</td>
</tr>
";
$sql2 = "UPDATE `account` SET `user_name` = '".addslashes($newuser_name)."',`user_password` = '".addslashes($newuser_password)."',`contact_name` = '".addslashes($newcontact_name)."',`club_name` = '".addslashes($newclub_name)."' WHERE `account_id` = $ID ";
  mysql_select_db($database_DBconnection, $DBconnection);
  $Result2 = mysql_query($sql2, $DBconnection) or die(mysql_error());
}
echo '</tbody>
</table>'
?>