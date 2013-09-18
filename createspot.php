<?php
if(isset($_SESSION['s_username']))
{
	include('googleapi_POINTOUT.php');
	require_once('classes/createUser.class.php');


	if(isset($_POST['navn']) && isset($_POST['postnummer']) && isset($_POST['text']) && isset($_POST['coordinates']))
	{
		try {
			$db = new MysqlConnector();
			$time = time();
			$amt = explode(" ", findAmtToPostNr(quote_smart($_POST['postnummer'])));
			$db->execute_query("INSERT INTO bmx_spot_guide (id,navn,text,writer,timestamp,postnummer,amt,coordinates) VALUES ('NULL','".quote_smart($_POST['navn'])."','".quote_smart($_POST['text'])."','".findIdToName($_SESSION['s_username'])."','$time','".quote_smart($_POST['postnummer'])."','$amt[0]','".quote_smart($_POST['coordinates'])."')");
			echo"<div class=\"createSuccess\">Spottet er oprettet s&aring; andre kan se det nu.</div>";
			exit;
		} catch (Exception $e) {
			$fejl = $e->getMessage();
		}
	}

?>
<?php
if(isset($fejl))
{
	echo"<div class=\"createError\">Fejl: $fejl</div>";
}
?>
<div class="createForm">
			<form id="create" method="post" action="index.php?site=createspot">
				<ul>
					<li><p>Spot-navn:</p> <input class="felt" type="text" name="navn" <?if(isset($_POST['navn'])) { echo"value=\"$_POST[navn]\""; } ?> /><small>Titlen som de andre brugere vil se.</small></li>
					<li><p>Postnummer:</p> <input class="felt" type="text" name="postnummer" <?if(isset($_POST['postnummer'])) { echo"value=\"$_POST[postnummer]\""; } ?> /><small>Postnummeret som spottet befinder sig i (Cirka)</small></li>
					<li><p>Tekst om spottet:</p>Husk at inkludere alt hvad der kunne v&aelig;re relevant.<br/>
					Fx.: Adresse,  &aring;bningstider osv. <textarea name="text" cols="50" rows="10"> 
<?if(isset($_POST['password2'])) { echo"$_POST[text]"; } ?></textarea></li>
				<li><p>Marker spottet p&aring; kortet</p>Tryk p&aring; kortet og marker spottet med den r&oslash;de 
pin.<br/> Zoom gerne helt ind og marker for bedste resultater for alle.</li>
				</ul>
				<div id="map" style="overflow: hidden; width: 450px; height: 300px;"></div>
				<input type="hidden" name="coordinates" id="coordinates" />
					<li><input class="opretNow" type="submit" name="Submit" value="Opret!" /></li>
			</form>
		</div>
<?php
} else {
	echo"Du er ikke logget ind og kan derfor ikke oprette et spot.";
}
?>
