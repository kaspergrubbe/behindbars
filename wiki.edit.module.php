<?php
echo"<p style=\"float:right; margin:0; padding:0;\"><a href=\"index.php?site=spot&id=$_GET[id]\">Se spot</a> | <a href=\"index.php?site=spot&id=$_GET[id]&wiki=history\"> Historik</a></p>";

if(isset($_POST['navn']) AND isset($_POST['postnummer']) AND isset($_POST['text']))
{
	$navn = $_POST['navn'];
	$db->execute_query("UPDATE bmx_spot_guide SET navn = '".quote_smart($navn)."' WHERE id='".quote_smart($_GET['id'])."'");
	$db->execute_query("UPDATE bmx_spot_guide SET text='".quote_smart($_POST['text'])."' WHERE id='".quote_smart($_GET['id'])."'");
	$db->execute_query("UPDATE bmx_spot_guide SET postnummer='".quote_smart($_POST['postnummer'])."' WHERE id='".quote_smart($_GET['id'])."'");
	$db->execute_query("UPDATE bmx_spot_guide SET writer='".findIdToName($_SESSION['s_username'])."' WHERE id='".quote_smart($_GET['id'])."'");
	
	$time = time();
	$db->execute_query("INSERT INTO bmx_spot_submissions (id,spotID,writer,submissiontitle,submission,timestamp) VALUES ('NULL','".quote_smart($_GET['id'])."','".findIdToName($_SESSION['s_username'])."','".quote_smart($navn)."','".quote_smart($_POST['text'])."','$time')");
	
	echo"Du har nu &aelig;ndret den.";
} 
else 
{
	$db->execute_query("SELECT * FROM bmx_spot_guide WHERE id = '".quote_smart($_GET['id'])."' LIMIT 0,1");
	if($db->numRows() == 1)
	{
		$row = $db->fetchrow(3);
	?>
	<div class="createForm">
		<form id="create" method="post" action="index.php?site=spot&id=<?=$_GET['id']?>&wiki=edit">
			<ul>
				<li><p>Spot-navn:</p> <input class="felt" type="text" name="navn" value="<?=$row['navn'];?>"  /><small>Titlen som de andre brugere vil se.</small></li>
				<li><p>Postnummer:</p> <input class="felt" type="text" name="postnummer" value="<?=$row['postnummer'];?>" /><small>Postnummeret som spottet befinder sig i (Cirka)</small></li>
				<li><p>Tekst om spottet:</p>Husk at inkludere alt hvad der kunne v&aelig;re relevant.<br/>
	
				Fx.: Adresse,  &aring;bningstider osv. <textarea name="text" cols="50" rows="10"><?=$row['text'];?></textarea>
				</li>
			</ul>
			<li><input class="opretNow" type="submit" name="Submit" value="&AElig;ndre!" /></li>
		</form>
	</div>
<?
	}
	else 
	{
		echo"Ingen spots med det ID.";
	}
}
?>