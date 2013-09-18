<?php
require_once("classes/MysqlConnector.class.php");
function formatizeDate ($timestamp) 
{
	// Should be edited to return stuff instead of echoing.
        $time = time();

        $dag = date("j",$time);
        $maaned = date("n",$time);
        $aar = date("Y",$time);
        $idag_time = mktime(0, 0, 0, $maaned, $dag, $aar);

        $igaar = $idag_time - 86400;

        //TJEK OM $timestamp er idag
        if($timestamp > $idag_time) 
        {
                $return = "I dag, kl. ". date("H:i",$timestamp) ."";
        }
        //TJEK OM $timestamp var igår
        elseif($timestamp < $idag_time AND $timestamp > $igaar) 
        {
                $return = "I g&aring;r, kl. ". date("H:i",$timestamp) ."";
        }
        else 
        {
                $return = "".strftime("%d. %B %G",$timestamp)."";
        }
        return $return;
}
function findNameToId ($id) 
{
	$db = new MysqlConnector();
	$db->execute_query("SELECT username FROM bmx_user WHERE id = '$id'");
	if($db->numRows() <= 0) 
	{
		return "Slettet Bruger";
	}
	else
	{
		$row = $db->fetchrow(3);
		return $row['username'];
	}
}
function findIdToName ($username) 
{
	$db = new MysqlConnector();
	$db->execute_query("SELECT id FROM bmx_user WHERE username = '$username'");
	if($db->numRows() <= 0) 
	{
		return "Slettet Bruger";
	}
	else
	{
		$row = $db->fetchrow(3);
		return $row['id'];
	}
}
function quote_smart($value)
{
    // Stripslashes
    if (get_magic_quotes_gpc()) 
    {
        $value = stripslashes($value);
    }
    // Quote if not integer
    if (!is_numeric($value)) 
    {
        $value = mysql_real_escape_string($value);
    }
    return $value;
    
    // Make a safe query
	//	query = sprintf("SELECT * FROM hygg_user_core WHERE username=%s AND password=%s",
	//	quote_smart($_GET['username']),
	//	quote_smart($_GET['password']));
}
function findCityToPostNr ($postNr) 
{
	$db = new MysqlConnector();
	$db->execute_query("SELECT bynavn FROM bmx_postnr WHERE postnr = '$postNr'");
	if($db->numRows() <= 0) 
	{
		return "Ukendt";
	}
	else
	{
		$row = $db->fetchrow(3);
		return $row['bynavn'];
	}
}
function findAmtToPostNr ($postNr) 
{
	$db = new MysqlConnector();
	$db->execute_query("SELECT amt FROM bmx_postnr WHERE postnr = '$postNr'");
	if($db->numRows() <= 0) 
	{
		return "Ukendt";
	}
	else 
	{
		$row = $db->fetchrow(3);
		return $row['amt'];
	}
}
function findCategoryToId ($category_id) 
{
	$db = new MysqlConnector();
	$db->execute_query("SELECT category_name FROM bmx_forum_category WHERE id = '$category_id'");
	if($db->numRows() <= 0) 
	{
		return "Ukendt";
	}
	else 
	{
		$row = $db->fetchrow(3);
		return $row['category_name'];
	}
}
function findHowtoCategoryToId ($howto_category_id) 
{
	$db = new MysqlConnector();
	$db->execute_query("SELECT category_name FROM bmx_howto_category WHERE id = '$howto_category_id'");
	if($db->numRows() <= 0) 
	{
		return "Ukendt";
	}
	else 
	{
		$row = $db->fetchrow(3);
		return $row['category_name'];
	}
}
function formatizeText ($inputText)
{
	$outputText = str_replace("\n","<br>", $inputText);
	$outputText = str_replace("<3","<img src=\"images/smileys/heartpump.gif\" alt=\"\">", $outputText);
	$outputText = strip_tags($outputText,'<b> <i> <br> <img> <a>');
	$outputText = str_replace("::)","<img src=\"images/smileys/taenker.gif\">", $outputText);
	
	return $outputText;
}
function textArea ($name)
{
	$html = "Tekst: <br><textarea name=\"$name\">Skriv din tekst her!</textarea>";
	return $html;
}
?>
