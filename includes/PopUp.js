// Added function for confirmation before deletion for account, competition and message
// JavaScript Document
function MM_openBrWindow(theURL,winName,features, myWidth, myHeight, isCenter) { //v3.0

  if(window.screen)if(isCenter)if(isCenter=="true"){
    var myLeft = (screen.width-myWidth)/2;
    var myTop = (screen.height-myHeight)/3;
	var IsScrollbars = "yes";
    features+=(features!='')?',':'';
    features+=',left='+myLeft+',top='+myTop+', scrollbars='+IsScrollbars;
  }

  window.open(theURL,winName,features+((features!='')?',':'')+'width='+myWidth+',height='+myHeight);
  
}

function deleteClass(id) {
  if (confirm('Ta bort klassen genom att klicka "OK"')) {
    window.location='ClassDelete.php?class_id='+id; 
  }
  return false;
}

function deleteCompetition(id) {
  if (confirm('Ta bort genom att klicka "OK"')) {
    window.location='CompetitionDelete.php?comp_id='+id; 
  }
  return false;
}

function deleteAccount(id) {
  if (confirm('Ta bort kontot genom att klicka "OK"')) {
    window.location='AccountDelete.php?account_id='+id; 
  }
  return false;
}

function deleteMessage(id) {
  if (confirm('Ta bort meddelandet/nyheten genom att klicka "OK"')) {
    window.location='MessageDelete.php?message_id='+id; 
  }
  return false;
}

