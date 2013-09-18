<?php
echo"<p style=\"float:right; margin:0px; padding:0;\"><a href=\"index.php?site=spot&id=$_GET[id]\">Se spot</a> | <a href=\"index.php?site=spot&id=$_GET[id]&wiki=edit\"> Rediger</a></p>";

$db->execute_query("SELECT * FROM bmx_spot_submissions WHERE spotID = '".quote_smart($_GET['id'])."'");
if($db->numRows() > 0)
{
	while($row = $db->fetchRow(3))
	{
		echo "<div class=\"wikiChange\"><a href=\"javascript:expand('$row[id]')\">".date("g:i, j F Y", $row['timestamp']).", af ".findNameToId($row['writer'])." | <b>$row[submissiontitle]</b></a></div>
		<div class=\"hidden\" id=\"$row[id]\">".formatizeText($row['submission'])."</div>"; 	
	}
}
else 
{
	echo"Der er ingen &aelig;ndringer foretaget for dette spot.";
}
?>
