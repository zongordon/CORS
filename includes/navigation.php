<?php
//Added link to Rep_Summary_Admin.php

global $_SESSION;
//Logged in Super Admin
if (isset($_SESSION['MM_AccountId']) && isset($_SESSION['MM_Level']) && $_SESSION['MM_Level'] == "1") { 
?>
<a href="AccountsList.php">Konton</a>|<a href="AccountInsert.php">Nytt konto</a>|<a href="MessagesHandle.php">Hantera nyheter</a>|<a href="CompetitionList.php">T&auml;vlingar</a>|<a href="CompetitionInsert.php">Ny t&auml;vling</a>|<a href="ClassInsert.php">Ny t&auml;vlingsklass</a>|<a href="RegsHandleAll.php">Hantera anm&auml;lningar</a>|<a href="Raffle.php">Lottning</a>|<a href="LogsList.php">Loggar</a>|<br><a href="Rep_Summary.php">Publika rapporter</a>|<a href="Rep_Summary_Admin.php">Admin-rapporter</a>|<a href="Logout.php">Logga ut</a>
<?php 
}
//Logged in registered user
if (isset($_SESSION['MM_AccountId']) && isset($_SESSION['MM_Level']) && $_SESSION['MM_Level'] == "0") { 
?>
<a href="AccountList_reg.php">Ditt konto</a>|<a href="RegInsert_reg.php" target="_self">Anm&auml;lan</a>|<a href="Rep_Summary.php">Rapporter</a>|<a href="Logout.php">Logga ut</a>
<?php    
}		

//Not logged in user
if (isset($_SESSION['MM_Level']) == "") {     
?>
<a href="AccountInsert.php" target="_self">Nytt konto</a>|<a href="Login.php">Logga in</a>
<?php    
}
?>