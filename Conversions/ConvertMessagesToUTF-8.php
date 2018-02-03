<?php

ob_start();
//Catch anything wrong with query
try {
// Select data for conversion
require('../Connections/DBconnection.php');           
$query_rsSelect = "SELECT message_id, message_subject, message FROM messages";
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
<td>Old message_subject</td>
<td>New message_subject</td>
<td>Old message</td>
<td>New message</td>
</tr>
</thead>
<tbody>';
while($row = $row_rsSelect = $stmt_rsSelect->fetch(PDO::FETCH_ASSOC)) {
$ID = $row["message_id"];
$message_subject = $row["message_subject"];
$message = $row["message"];
$newmessage_subject = encodeToUtf8($row["message_subject"]);
$newmessage = encodeToUtf8($row["message"]);

echo "
<tr>
<td>$ID</td>
<td>$message_subject</td>
<td>$newmessage_subject</td>
<td>$message</td>
<td>$newmessage</td>
</tr>
";

 //UPDATE with parameters  
$query = "UPDATE messages SET message_subject = :message_subject, message = :message WHERE message_id = :message_id";
$stmt = $DBconnection->prepare($query);                                  
$stmt->bindValue(':message_subject', $message_subject, PDO::PARAM_STR);       
$stmt->bindValue(':message', $message, PDO::PARAM_STR);    
$stmt->bindValue(':message_id', $ID, PDO::PARAM_INT);   
$stmt->execute(); 
}
echo '</tbody>
</table>'
?>