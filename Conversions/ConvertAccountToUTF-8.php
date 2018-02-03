<?php
//Adapted code to PHP 7 (PDO) and added minor error handling. 

ob_start();
//Catch anything wrong with query
try {
// Select data for conversion
require('../Connections/DBconnection.php');           
$query_rsSelect = "SELECT account_id, user_name, user_password, contact_name, club_name FROM account";
$stmt_rsSelect = $DBconnection->query($query_rsSelect);
}   catch(PDOException $ex) {
        echo "An Error occured with queryX: ".$ex->getMessage();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<?php
// Includes Several code functions
include_once('../includes/functions.php');
echo '<table><thead><tr><td>Id</td>
<td>Old user_name</td>
<td>Encoding</td>
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
while($row = $row_rsSelect = $stmt_rsSelect->fetch(PDO::FETCH_ASSOC)) {
$ID = $row["account_id"];

$user_name = $row["user_name"];
$user_password = $row["user_password"];
$contact_name = $row["contact_name"];
$club_name = $row["club_name"];
$encoding = mb_detect_encoding($club_name); 
$newuser_name = encodeToUtf8($user_name);
$newuser_password = encodeToUtf8($user_password);
$newcontact_name = encodeToUtf8($contact_name);
$newclub_name = encodeToUtf8($club_name);
echo "
<tr>
<td>$ID</td>
<td>$user_name</td>
<td>$encoding</td>
<td>$newuser_name</td>
<td>$user_password</td>
<td>$newuser_password</td>
<td>$contact_name</td>
<td>$newcontact_name</td>
<td>$club_name</td>
<td>$newclub_name</td>
</tr>
";

//UPDATE with parameters  
$query = "UPDATE account SET user_name = :user_name, user_password = :user_password, contact_name = :contact_name, club_name = :club_name WHERE account_id = :account_id";
$stmt = $DBconnection->prepare($query);                                  
$stmt->bindValue(':user_name', $newuser_name, PDO::PARAM_STR);       
$stmt->bindValue(':user_password', $newuser_password, PDO::PARAM_STR);    
$stmt->bindValue(':contact_name', $newcontact_name, PDO::PARAM_STR);
$stmt->bindValue(':club_name', $newclub_name, PDO::PARAM_STR); 
$stmt->bindValue(':account_id', $ID, PDO::PARAM_INT);   
$stmt->execute(); 

}
echo '</tbody>
</table>'
?>
</html>
<?php ob_end_flush();?>