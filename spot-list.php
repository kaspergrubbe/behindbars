<?php
$pagesize = 15; // Antal rows pr. side
$db_tmp = new MysqlConnector();

//echo"Navn, By, Amt, Rating, Dato Tilføjet";

if(!isset($_GET['id']))
{	
	
	if(isset($_GET['post'])) 
	{
		$where = "WHERE postnummer = '".quote_smart($_GET['post'])."'";
		$over = @findCityToPostNr(quote_smart($_GET['post']));
	}
	elseif(isset($_GET['amt'])) 
	{
		$where = "WHERE amt = '".quote_smart($_GET['amt'])."'";
		$over = urldecode($_GET['amt'])." amt";
	}
	else 
	{
		$where = "";
		$over = "Alle spots";
	}
	
	if(!isset($_GET["sort"]))
	{
		$query = "SELECT id, navn, voteRating, postnummer, timestamp FROM bmx_spot_guide $where ORDER BY (voteRating+0) DESC";
	}
	elseif($_GET["sort"] == 'rating')
	{
		$query = "SELECT id, navn, voteRating, postnummer, timestamp FROM bmx_spot_guide $where ORDER BY (voteRating+0) DESC";
	}
	
	echo"
		<h1 class=\"spot_overskrift\">$over</h1>
	";
	echo"	<select name=\"NavSelect\" onChange=\"Navigate(this)\">";
			echo"
			<option value=\"#\">V&aelig;lg amt:</option>
			<option value=\"index.php?site=list\">Alle</option>
";
			$db = new MysqlConnector();
			$db->execute_query("SELECT DISTINCT amt FROM bmx_postnr");
			while($row_amt = $db->fetchrow(3))
			{
				$db_tmp = new MysqlConnector();
				$amt = explode(" ", $row_amt['amt']);
				$db_tmp->execute_query("SELECT id FROM bmx_spot_guide WHERE amt = '$amt[0]'");
				if(isset($_GET['amt']) && $amt['0'] == $_GET['amt'])
				{
					$add = 'selected';
				}
				else 
				{
					$add = '';
				}
				echo"			<option value=\"index.php?site=list&amt=$amt[0]\" $add>$row_amt[amt] (".$db_tmp->NumRows().")</option>
";
			}
			echo"		</select>
";

	echo"		<table width=\"600\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  			<tr>
			    <td width=\"130\" class=\"upper\">Navn</td>
			    <td width=\"151\" class=\"upper\">By</td>
			    <td width=\"153\" class=\"upper\">Amt</td>
			    <td width=\"57\" class=\"upper\">Rating</td>
			    <td width=\"109\" class=\"upper\">Tilf&oslash;jet</td>
			</tr>";

	$db->execute_query("$query");
	//$from = (isset($_GET["from"]) && is_numeric($_GET["from"]) && $_GET["from"] < $db->numRows()) ? $_GET["from"] : 0;
	if($db->numRows() > 0) 
	{
		//$db_tmp->execute_query("$query limit $from, $pagesize");
		while($row_spots = $db->fetchrow(3)) {
			$amt = explode(" ", findAmtToPostNr("$row_spots[postnummer]"));
			echo"
			<tr>
				<td width=\"130\"><a href=\"index.php?site=spot&id=$row_spots[id]\">$row_spots[navn]</a></td>
				<td width=\"130\"><a href=\"index.php?site=list&post=$row_spots[postnummer]\">".findCityToPostNr("$row_spots[postnummer]")."</a></td>
				<td width=\"153\"><a href=\"index.php?site=list&amt=$amt[0]\">$amt[0] amt</a></td>
				<td width=\"57\">".round("$row_spots[voteRating]",2)."</td>
				<td width=\"109\">".formatizeDate($row_spots['timestamp'])."</td>
			</tr>
";
		}
	}
	else
	{
echo"			<tr>
				<td colspan=\"5\">Der er ingen spots her.</td>
			</tr>";
	}
	echo"
		</table>
";
}
else 
{
	echo"Ingen spots tilføDet."
;
}
?>
