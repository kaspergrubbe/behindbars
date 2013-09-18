<?php
header("Content-type: text/html; charset=iso-8859-1");
setlocale (LC_ALL,"da_DK");
error_reporting(E_ALL); // Debug purposes only

session_start();

require_once('classes/MysqlConnector.class.php');
require_once('classes/pdoMysql.class.php');
require_once('classes/AjaxRatingCounter.class.php');
require_once('functions.php');

$db = new MysqlConnector();

if(isset($_POST['username']) && isset($_POST['password']))
// Go login
{
	$username = quote_smart($_POST['username']);
	$password = sha1(md5($_POST['password']));

	$query = "SELECT id FROM bmx_user WHERE username='$username' AND password='$password'";
	$db->execute_query($query);
	if($db->numRows() == 1) {
		$_SESSION['s_username'] = $_POST['username'];
	}
	else
	{
		$fejllogin = 1;
	}
}
elseif(isset($_GET['logout']))
{
	$_SESSION = array();
	session_destroy();
	unset($_SESSION['s_username']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="verify-v1" content="/DkyM2u48KgYetIsDwDRFTPgpOFncDbg3Hj7Mmlq7Hg=" >
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BehindBars.dk - Serving the needs of the community!</title>

<link rel="icon" href="favicon.ico" type="image/x-icon" />

<? include('metatags.php'); ?>

<link rel="stylesheet" href="styles.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="js/mootools.Gz.js"></script>
<?
if(isset($_GET['site']) && $_GET['site'] == 'spot')
{
	echo"<script src=\"js/javascript.js.php?id=$_GET[id]\" type=\"text/javascript\"></script>";
}
?>

<?php

if(isset($_GET['site']) && $_GET['site'] == 'map')
{
	include('googleapi_VIEW.php');
}
?>
    <script type="text/javascript">
    function expand(superID)
    {
    	var textDiv = document.getElementById(superID);
    	
    	var divh = textDiv.offsetHeight;
    	var demo1effect = new Fx.Height(textDiv);
    	
    	if(divh == '250')
    	{
    		demo1effect.custom(250, 0);
    	}
    	else if(divh == '0')
    	{
    		demo1effect.custom(0, 250);
    	}
    	
    }
    function login()
    {
    	var loginDiv = document.getElementById('login');

    	var divh = loginDiv.offsetHeight;
    	var demo1effect = new Fx.Height(loginDiv);
    	if(divh == '60')
    	{
    		demo1effect.custom(60, 0);
    	}
    	else if(divh == '0')
    	{
    		demo1effect.custom(0, 60);
    	}
    }
    function biggermap()
    {
    	var mapDiv = document.getElementById('map');
    	mapDiv.className='mapdoFloat';
    	var demo1effect = new Fx.Height(mapDiv);

    	var divh = mapDiv.offsetHeight;
    	if(divh == '300')
    	{
    		demo1effect.custom(300, 600);
    	}
    	else if(divh == '600')
    	{
    		demo1effect.custom(600, 300);
    	}
    }
    function Navigate(sel) {
    	// On the spot site where you select "amt"
    	var i = sel.selectedIndex
    	if (i!=0) { top.location.href = sel.options[i].value }
    }
    </script>
</head>

<?php
if(isset($_GET['site']) && $_GET['site'] == 'map' OR isset($_GET['site']) && $_GET['site'] == 'createspot')
{
	echo"<body onload=\"load();\" onunload=\"GUnload()\">
";
} else {
	echo"<body>
";
}
?>
<div class="wrapper">
	<div class="topSqr">
		<div id="login" class="login">
			<form id="loginBox" method="post" action="<? $req = $_SERVER['REQUEST_URI']; $req = explode("/", $req); echo $req[1]; ?>">
			<ul>
		  	    <li><strong>Brugernavn:</strong> <input type="text" class="box" name="username" /></li>
				<li><strong>Kodeord:</strong> <input type="password" class="box" name="password" /></li>
				<li><input type="submit" class="knap" value="Log-ind" /><input type="button" class="opret" value="Opret bruger" onclick="window.location.href='index.php?site=create'" /></li>
			</ul>
			</form>
	    </div>
	</div>
	<div class="menu"><a href="index.php">Blog</a><a href="index.php?site=map">Vis spots</a><a href="index.php?site=list">List spots</a><a href="index.php?site=createspot">Opret spot</a>
	</div>
	<div class="content">
		<?php
		if(isset($_GET['site']) && $_GET['site'] == 'map')
		{
			echo"		<div id=\"map\" style=\"width: 600px; height: 300px\"></div>
			<a style=\"float:left;\" href=\"javascript:biggermap()\">St&oslash;rre/mindre kort</a>
			<a style=\"float:right;\" href=\"map.php\">Se kort i fuldsk&aelig;rm</a>
			";
		}
		elseif(isset($_GET['site']) && $_GET['site'] == 'list')
		{
			include('spot-list.php');
		}
		elseif(isset($_GET['site']) && $_GET['site'] == 'create')
		{
			include('create.php');
		}
		elseif(isset($_GET['site']) && $_GET['site'] == 'createspot')
		{
			include('createspot.php');
		}
		elseif(isset($_GET['site']) && $_GET['site'] == 'spot')
		{
			include('view-spot.php');
		} else {
			echo"<h2 style=\"margin:0px;\">Lidt om Behindbars</h2>BehindBars er en spotguide for cykel-interresserede mennesker, dog prim&aelig;rt for MTB (Mountain-bikes) og BMX (Bicycle MotoCross). Spotsne bliver tilf&oslash;jet en database s&aring; de kan vises p&aring; et interaktivt danmarkskort, med fuld beskrivelse og billeder.<br />Indholdet p&aring; siden er styret og tilf&oslash;jet af sidens brugere.<br/><br/><b>Ved at oprette en bruger f&aring;r du adgang til:</b><br/>- At oprette spots i spotguiden<br/>- At uploade billeder<br/>- Kommentere spots<br/>- Rate spots<br/><br/>
			<b>Version 1.1:</b><br/>
			- Ratings<br/>
			- Mulighed for at &aelig;ndre spots-beskrivelser.<br /><br />
			<b>Kommende opdateringer:</b><br/>
			- Forskellige typer spots (m. mulighed for at se det p&aring; kortet med forskellige ikoner), hvis nogen kender nogle til at tegne s&aring;dan noget, mail mig :)";
		}
		?>
	</div>
	<div style="display:block; width:600px; clear:both; overflow:hidden; height:0px;">Behindbars - En dansk spotguide</div>
<?php
/*
<div style="margin-top:10px; text-align:center;">
<script type="text/javascript"><!--
google_ad_client = "pub-1856016312892815";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text";
//2007-01-04: behindbars
google_ad_channel = "1960230913";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_text = "000000";
google_color_url = "7F7F7F";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
*/
?>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1137851-1";
urchinTracker();
</script>


<?php
if(isset($fejllogin))
{
echo"
<script type=\"text/javascript\">
	alert(\"Forkert brugernavn og/eller kode!\");
</script>
";
}
?>
</body>
</html>
