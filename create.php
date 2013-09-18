<?php
if(!isset($_SESSION['s_username']))
{
	require_once('classes/createUser.class.php');
	$pdo = new pdoMysql();
	if(isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2']) && isset($_POST['email']))
	{
		if($_POST['password1'] != $_POST['password2'])
		{
			$fejl = 'De to kodeord er ikke ens';
		}
		else 
		{
			try {
				$go = new createUser($_POST['username'],$_POST['password1'],$_POST['email'],'1',$pdo);
				echo"<div class=\"createSuccess\">Du er nu oprettet, du kan nu logge ind.</div>";
				exit;
			} catch (Exception $e) {
				$fejl = $e->getMessage();
			}
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
			<form id="create" method="post" action="index.php?site=create">
				<ul>
					<li><p>Brugernavn:</p> <input class="felt" type="text" name="username" <?if(isset($_POST['username'])) { echo"value=\"$_POST[username]\""; } ?> /><small>Hvordan de andre p&aring; siden vil se dig.</small></li>
					<li><p>Kodeord:</p> <input class="felt" type="password" name="password1" <?if(isset($_POST['password1'])) { echo"value=\"$_POST[password1]\""; } ?> /><small>Skal v&aelig;re over 6. cifre.</small></li>
					<li><p>Kodeord igen:</p> <input class="felt" type="password" name="password2" <?if(isset($_POST['password2'])) { echo"value=\"$_POST[password2]\""; } ?> /></li>
					<li><p>E-mail:</p> <input class="felt" type="text" name="email" <?if(isset($_POST['email'])) { echo"value=\"$_POST[email]\""; } ?> /><small><b>SKAL</b> v&aelig;re korrekt.</small></li>
					<li><input class="opretNow" type="submit" name="Submit" value="Opret!" /></li>
				</ul>
			</form>
		</div>
<?php
} else {
	echo"Du er allerede logget ind og kan derfor ikke oprette en bruger.";
}
?>