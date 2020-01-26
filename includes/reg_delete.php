<?php

if (filter_input(INPUT_GET,'reg_id') != "") {
    $reg_id = filter_input(INPUT_GET,'reg_id');
    //Catch anything wrong with query
    try {
    require('Connections/DBconnection.php');         
    $query = "DELETE FROM registration WHERE reg_id = :reg_id";
    $stmt_rsDelete = $DBconnection->prepare($query);
    $stmt_rsDelete->bindValue(':reg_id', $reg_id, PDO::PARAM_INT);   
    $stmt_rsDelete->execute();
    }   catch(PDOException $ex) {
            echo "An Error occured with queryX: ".$ex->getMessage();
        }
    if ($MM_authorizedUsers === "1") { 
        $deleteGoTo = "RegsHandleAll.php#registration_insert";
    }else{
        $deleteGoTo = "RegInsert_reg.php#registration_insert";
    }
    header(sprintf("Location: %s", $deleteGoTo));
//Kill statement
$stmt_rsDelete->closeCursor();
}
$pagetitle="Ta bort anm&auml;lan";
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
<h3>Det finns ingen anm&auml;lan att ta bort!</h3>
<p><a href="<?php if ($MM_authorizedUsers === "0") { echo 'RegInsert_reg';} else {echo 'RegsHandleAll';} ?>.php">Tillbaka till Registrera t&auml;vlande</a></p>
  </div>
</div>
<?php include("includes/footer.php");?>
</body>
</html>
<?php ob_end_flush();?>