<?php
session_start();
?>


var http = createRequestObject();
var displayRating = '';
var currentId = '';

function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else{
        ro = new XMLHttpRequest();
    }
    return ro;    
}

function handleResponse() {
	if(http.readyState == 4){
        var response = http.responseText;
	
		if (response == 'ERROR'){
			//alert("Sorry... Failed to update rating.");
	        document.getElementById(currentId+'_showrating').innerHTML = 'Oops.. Der opstod en fejl!';
		}
		else {
		    document.getElementById(currentId+'_showrating').innerHTML = 'Du har nu stemt, tak for din stemme!';
		}
       
        displayRating = response.substr(0, 4);
        //document.getElementById(currentId+'_showrating').innerHTML = 'Rating: '+displayRating;
        totalRating = Math.ceil(response);   
        var obj = document.getElementById(currentId+'_'+totalRating);
        changeover(obj, totalRating);
		displayStars(rating, currentId);
    }
}
function updateRating(obj, rating) {
	var id = obj.title;
	var fullId = obj.id;
	var idName = fullId.substr(0, fullId.indexOf('_'));
	var totalRating = rating;
	currentId = idName;
	
    http.open('get', 'ajax.php?id='+id+'&idName='+idName);
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function changeover(obj, rating) {
	
	var imageName = obj.src;
	var id = obj.title;
	var index = imageName.lastIndexOf('/');
	var filename = imageName.substring(index+1);
	var fullId = obj.id;
	var idName = fullId.substr(0, fullId.indexOf('_'));
	var totalRating = rating;

	for(i=0; i<id; i++) {
		var num = i+1;
		
		if (num%2 == 0) {
			document.getElementById(idName+'_'+num).src = 'img/ajaxvote/right_marked.png';			
		}
		else {
			document.getElementById(idName+'_'+num).src = 'img/ajaxvote/left_marked.png';
		}
	}

}

function changeout(obj, rating) {

	var imageName = obj.src;
	var id = obj.title;
	var index = imageName.lastIndexOf('/');
	var filename = imageName.substring(index+2);
	var fullId = obj.id;
	var idName = fullId.substr(0, fullId.indexOf('_'));
	var totalRating = rating;
	
	for(i=0; i<id; i++) {
		var num = i+1;
		
		if (num%2 == 0) {
			if(i < totalRating) {
				document.getElementById(idName+'_'+num).src = 'img/ajaxvote/right_voted.png';			
			}
			else {
				document.getElementById(idName+'_'+num).src = 'img/ajaxvote/right_unmark.png';			
			}
		}
		else {
			if(i < totalRating) {
				document.getElementById(idName+'_'+num).src = 'img/ajaxvote/left_voted.png';			
			}
			else {
				document.getElementById(idName+'_'+num).src = 'img/ajaxvote/left_unmark.png';			
			}
		}
	}
}

function displayStars(rating, idName) {
	<?php
	require_once('../classes/MysqlConnector.class.php');
	require_once('../functions.php');
	
	$db = new mysqlconnector();
	$db->execute_query("SELECT voteUsers FROM bmx_spot_guide WHERE id = '".quote_smart($_GET['id'])."'");
	$row = $db->fetchRow(3);
	
	$users = explode(",",$row['voteUsers']);
	$user = findIdToName($_SESSION['s_username']);
	?>
	//document.write('<center>');
	
	for(i=0; i < 10; i++ ) {
		if(i%2 ==0) {
			if(i < rating) {
				document.write('<img src="img/ajaxvote/left_voted.png" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" <?php if(isset($_SESSION['s_username']) AND !in_array($user,$users)) { ?> onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+rating+')" <?php } ?> />');
			}
			else {
				document.write('<img src="img/ajaxvote/left_unmark.png" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" <?php if(isset($_SESSION['s_username']) AND !in_array($user,$users)) { ?> onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+rating+')" <?php } ?> />');
			}
		}
		else {
			if(i < rating) {
				document.write('<img src="img/ajaxvote/right_voted.png" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" <?php if(isset($_SESSION['s_username']) AND !in_array($user,$users)) { ?> onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+rating+')" <?php } ?> />');
			}
			else {
				document.write('<img src="img/ajaxvote/right_unmark.png" id="'+idName+'_'+(i+1)+'" title="'+(i+1)+'" <?php if(isset($_SESSION['s_username']) AND !in_array($user,$users)) { ?> onmouseout="changeout(this, '+rating+')" onmouseover="changeover(this, '+rating+')" onclick="updateRating(this, '+rating+')" <?php } ?> />');
			}
		}
	}

	if (displayRating == '') {
		document.write('<br /><div class="ratingText" id="'+idName+'_showrating" >'+displayRating+'</div>');
	}
	else {
		document.write('<br /><div class="ratingText" id="'+idName+'_showrating" >'+totalRating+'</div>');
	}


}