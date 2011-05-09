<?php 
session_start();
if ($_POST['key'] != $_SESSION['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
	echo "<p>{$_SESSION['key']}::{$_POST['key']}</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	$query="SELECT SQL_CALC_FOUND_ROWS * FROM {$EJ_mysql->prefix}module_EJ_events WHERE EJ_eventId != 0";
	if ($_POST['date']!=0)
	{
		$date = date('Y-m-d', strtotime($_POST['date']));
		$query .= " AND EJ_eventDate = '$date'";
	}
	if (!empty($_POST['text']))
	{
		$query .= " AND (EJ_eventTitle LIKE '%{$_POST['text']}%' OR EJ_eventText LIKE '%{$_POST['text']}%')";
	}
	if ($_POST['cat']!=0)
	{
		$query .= " AND EJ_eventCat = ".$_POST['cat'];
	}
	if ($_POST['poster']!="0")
	{
		$query .= " AND EJ_eventPoster = '".$_POST['poster']."'";
	}
	if ($_POST['hidden']==1)
	{
		$query .= " AND EJ_eventHidden = 0";
	}
	$query .= " LIMIT ".$_POST['limit'];
	$EJ_mysql->query($query);
	if ($EJ_mysql->numRows() == 0)
	{
		echo '<div class="event_result" style="text-align: center;"><p><strong>No Results Found! Please try a broader search.</strong></p></div>';
	} else
	{
		while ($result = $EJ_mysql->getRow())
		{
			if (empty($result['EJ_eventImage']) or !file_exists("images/".$result['EJ_eventImage']))
			{
				$img = "noimage.png";
			} else
			{
				$img = $result['EJ_eventImage'];
			}
			echo '
				<div class="event_result" id="'.$result['EJ_eventId'].'">
					<div style="float: right;"><img src="modules/EJ_events/recycle.png" alt="delete" title="Delete Event" style="cursor: pointer;" onclick="deleteEvent(\''.$result['EJ_eventId'].'\', \''.$_SESSION['key'].'\')" /> <a href="?module=EJ_events&action=editevent&eventid='.$result['EJ_eventId'].'"><img src="modules/EJ_events/edit.png" alt="edit" title="Edit Event" style="cursor: pointer;" /></a> <img src="modules/EJ_events/blue_down.png" alt="show/hide details" title="Show/Hide Details" style="cursor: pointer;" onclick="Slide(this.parentNode.parentNode, 16, 150)" /></div>
					<p><strong>'.date("d/m/Y", strtotime($result['EJ_eventDate'])).' - '.$result['EJ_eventTitle'].'</strong> posted by: '.$result['EJ_eventPoster'].'</p>
					<p><img class="eventImage" src="modules/EJ_events/images/'.$img.'" alt="'.$event['EJ_eventTitle'].'" />'.$result['EJ_eventText'].'</p>
				</div>';
		}
	}
	$EJ_mysql->query("SELECT FOUND_ROWS() as results");
	$rows = $EJ_mysql->getRow();
	echo ":::".$rows['results'];
}
?>