<?php
session_start();

require_once('classes/MysqlConnector.class.php');
require_once('functions.php');

if(isset($_SESSION['s_username'])) 
{
function thumb($sourcefile, $targetfile, $size) 
{
	$size = $size;   // Max størrelse (pixler)
	$source_id = imagecreatefromjpeg($sourcefile);
	$source_x = imagesx($source_id);
	$source_y = imagesy($source_id);
	$delta = $size/max($source_x, $source_y);
	$dest_x = round($source_x*$delta);
	$dest_y = round($source_y*$delta);
	$target_id=imagecreatetruecolor($dest_x, $dest_y);
	imagecopyresampled($target_id,$source_id,0,0,0,0, $dest_x,$dest_y, $source_x,$source_y);
	imagejpeg($target_id,$targetfile,100);
}
function real($sourcefile, $targetfile, $isize) 
{
	$size = $isize;   // Max størrelse horizontal (pixler)
	$source_id = imagecreatefromjpeg($sourcefile);
	$source_x = imagesx($source_id);
	$source_y = imagesy($source_id);
							
	if($source_x > $size)
	{
		$multiplier = $size / $source_x;
		$dest_x = round($source_x*$multiplier);
		$dest_y = round($source_y*$multiplier);
	} else {
		$dest_x = $source_x;
		$dest_y = $source_y;
	}
	$target_id=imagecreatetruecolor($dest_x, $dest_y);
	imagecopyresampled($target_id,$source_id,0,0,0,0, $dest_x,$dest_y, $source_x,$source_y);
	imagejpeg($target_id,$targetfile,100);
}

if(isset($_POST['Submit'])) 
{
	$konfiguration["upload_bibliotek"] = "img/gallery/";
		
	$aFile = $_FILES['file'];
	$allowed_filetypes = array('image/jpeg','image/jpg','image/jpe','image/pjpeg');
	if(in_array($aFile['type'], $allowed_filetypes))
	{
		if(filesize($aFile['tmp_name']) <= 2000000) // 1 mB
		{			
			/* Hvor flytter vi fra og til */
			$fra = $_FILES["file"]["tmp_name"];
			$time = time();
							
			$time = time();
			$til = $konfiguration["upload_bibliotek"] . "/" . $time . ".jpg";
			$thumb = $konfiguration["upload_bibliotek"] . "/"  . $time . "_small.jpg";
			$big = $konfiguration["upload_bibliotek"] . "/"  . $time . "_big.jpg";
			thumb($fra, $thumb, 100); // Kald funktion
			real($fra,$til,500);
			real($fra,$big,1000);

			$db = new MysqlConnector();			
			$db->execute_query("INSERT INTO bmx_gallery (id, url, uploader, note) VALUES ('NULL','$time','$_SESSION[s_username]','spot".quote_smart($_GET['id'])."')");
			#$db->execute_query("UPDATE bmx_user_gallery SET last_modified = '$time' WHERE id = '".quote_smart($_POST['galleri'])."'");							
			}
			else 
			{
				$fejl= "Dit billede er større end den tilladte grænse på 2 MB!";
			}
		}
		else 
		{
			$fejl = "Forkert filtype, dit billede var af typen $aFile[type], men skal være jpeg.";
		}
	}
}
if(isset($fejl))
{
header("Location: index.php?site=spot&id=$_GET[id]&error=$fejl");
}
else 
{
header("Location: index.php?site=spot&id=$_GET[id]");
}
?>
