<?php
	/**
	 * Backend file to manage rating
	 * 
	 */
	session_start();
	
	require_once('classes/MysqlConnector.class.php');
	require_once('functions.php');

	if(is_numeric($_GET['idName'])) 
	{
		$db = new MysqlConnector();
		$db->execute_query("SELECT voteCount,voteUsers,voteRating FROM bmx_spot_guide WHERE id = ".quote_smart($_GET['idName']));
		if($db->numRows() > 0)
		{
			$row = $db->fetchRow(3);
			
			$votedUsers = split(",",$row['voteUsers']);
			if(in_array(findIdToName($_SESSION['s_username']),$votedUsers))
			{
				echo $row['voteRating'];
			}
			else 
			{
				$voteCount = $row['voteCount']+$_GET['id'];
				
				if(empty($row['voteUsers']))
				{
					$voteUsers = findIdToName($_SESSION[s_username]);
				}
				else 
				{
					$voteUsers = $row['voteUsers'].",".findIdToName($_SESSION[s_username]);	
				}
				
				$newRating = $voteCount/(count($votedUsers)+1);
				
				$db->execute_query("UPDATE bmx_spot_guide SET voteUsers = '$voteUsers' WHERE id = ".quote_smart($_GET['idName']));
				$db->execute_query("UPDATE bmx_spot_guide SET voteCount = '$voteCount' WHERE id = ".quote_smart($_GET['idName']));
				$db->execute_query("UPDATE bmx_spot_guide SET voteRating = '$newRating' WHERE id = ".quote_smart($_GET['idName']));

				echo "$newRating";
			}
		}
		else 
		{
			echo"Fejl!";
		}
	}
	else 
	{
		echo "Fejl!";
	}
?>