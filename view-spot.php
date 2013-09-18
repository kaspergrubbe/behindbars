<?php
require_once("functions.php");

if(isset($_GET['error']))
{
	echo"<script language=\"javascript\">alert(\"$_GET[error]\");</script>";
}
	
if(isset($_GET['wiki']) && $_GET['wiki'] == 'edit' && isset($_SESSION['s_username']))
{
	require_once('wiki.edit.module.php');
}
elseif(isset($_GET['wiki']) && $_GET['wiki'] == 'history')
{
	require_once('wiki.history.module.php');
}
else 
{
	$db->execute_query("SELECT * FROM bmx_spot_guide WHERE id = '".quote_smart($_GET['id'])."' LIMIT 0,1");
	if($db->numRows() == 1)
	{
		$row = $db->fetchrow(3);
		$amt = explode(" ", findAmtToPostNr($row['postnummer']));
		echo"<p style=\"float:right; margin-top:-10px; padding:0;\"><a href=\"index.php?site=spot&id=$_GET[id]&wiki=edit\">Rediger</a> | <a href=\"index.php?site=spot&id=$_GET[id]&wiki=history\"> Historik</a></p>";
		echo"<h1 style=\"margin:0px;margin-top:10px;\">$row[navn]</h1><p class=\"spot_tekst\" style=\"padding:0px;margin:0px;\">";
		echo"<a href=\"index.php?site=list\">Danmark</a> | <a href=\"index.php?site=list&action=list&amt=$amt[0]\">".findAmtToPostNr($row['postnummer'])."</a> | <a href=\"index.php?site=list&action=list&action=list&post=$row[postnummer]\">".findCityToPostNr($row['postnummer'])."</a><br />";
		
	echo"Sidst redigeret af ".findNameToId($row['writer'])."<br /><br />";
	
		echo formatizeText($row['text'])."</p><br />";
	
		
		$ajaxRatingCounter  = new AjaxRatingCounter();
		$ajaxRatingCounter->addStars($row['voteRating'], $_GET['id']);
		echo $ajaxRatingCounter->displayStars();
		
		echo"<div style=\"background:#000000;\">";
		$id = quote_smart($_GET['id']);
		$db->execute_query("SELECT * FROM bmx_gallery WHERE note = 'spot$id'");
		if($db->numRows() >= 1)
		// If there are pics in the gallery, hooray! \o/
		{
			while($row_pics = $db->fetchrow(3)) {
				echo"<div class=\"spot_pic\"><img src=\"img/gallery/$row_pics[url].jpg\"></div>";
			}
		}
		if(isset($_SESSION['s_username']))
		{
		echo"
		<div style=\"color:#FFFFFF;padding-left:5px;\"><form enctype=\"multipart/form-data\" action=\"upload.php?id=$_GET[id]\" method=\"post\">
			Upload billede: <input size=\"60\" type=\"file\" class=\"stdInput\" name=\"file\" value=\"\"><input type=\"submit\" name=\"Submit\" value=\"Upload\" class=\"stdInput\"><br/>
		     </form>
		</div>";
		}
		echo"
		</div>";
	
		$db->execute_query("SELECT * FROM bmx_spot_guide_entries WHERE bmx_spot_id = '".quote_smart($_GET['id'])."'");
		if($db->numRows() >= 1)
		// Hvis der er kommentarer
		{
			$id = 1;
			while($row_news = $db->fetchrow(3)) {
				echo"
					<h3 class=\"comment_overskrift\" style=\"display:block;border-bottom:1px dotted #000000;padding:0px;margin:0px;\">#$id ". findNameToId($row_news['bmx_user_id'])." <p style=\"padding-left:30px;margin:0px;font-size:10px\">skrev d. ".formatizeDate($row_news['timestamp']).":</p></h3>
					<p class=\"comment_tekst\" style=\"margin:0px;margin-bottom:10px;\">$row_news[text]</p>
				";		
				$id++;
			}
		}
		if(isset($_POST['msg']))
		{
			$db->execute_query("INSERT INTO bmx_spot_guide_entries (id, bmx_spot_id, text, bmx_user_id, timestamp) VALUES ('NULL',".quote_smart($_GET['id']).",'".quote_smart($_POST['msg'])."',".findIdToName($_SESSION['s_username']).",".time().")");
		}
		elseif(isset($_SESSION['s_username']))
		{
			echo"
			<h2 style=\"margin:0px;border-bottom:1px solid #000000;\">Skriv kommentar</h2>
			<div class=\"spot_comment\"><form id=\"form1\" name=\"form1\" method=\"post\" action=\"$_SERVER[REQUEST_URI]\">
					Undg&aring; s&aring;vidt muligt debat, ret gerne faktuelle fejl og kom gerne med foreslag til &aelig;ndringer.<br/> Dit bidrag g&oslash;r siden bedre!<br/>
	  				<textarea name=\"msg\" id=\"stdTextArea\" cols=\"50\" rows=\"5\"></textarea><br /><input name=\"Submit\" id=\"stdButton\" type=\"submit\" value=\"Go\" />
			</form></div>";
		}
	
	}
	else 
	{
		echo"Dette spot findes ikke";
	}
}
?>
