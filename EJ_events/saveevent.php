<?php

function to_utf8( $string ) { 
    if ( preg_match('%^(?: 
      [\x09\x0A\x0D\x20-\x7E]            # ASCII 
    | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte 
    | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs 
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte 
    | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates 
    | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3 
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15 
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16 
)*$%xs', $string) ) { 
        return $string; 
    } else { 
        return iconv( 'CP1252', 'UTF-8', $string); 
    } 
} 

session_start();
if ($_SESSION['key'] != $_POST['key'] or $_POST['key']=="")
{
	echo "<p class=\"EJ_user_error\"><strong>AUTHORISATION ERROR</strong>: Unable to verify key!</p>";
} else
{
	$EJ_initPage ='ajax';
	require('../../init.inc.php');
	if (!isset($_POST['id']))
	{
	 $query = "INSERT INTO {$EJ_mysql->prefix}module_EJ_events SET EJ_eventTitle='".addslashes(urldecode($_POST['title']))."', EJ_eventText='".addslashes(str_replace(array("\n", "<br>", "<br/>", "£"),array("<br />","<br />","<br />", "&pound;"),to_utf8(urldecode($_POST['desc']))))."', EJ_eventCat = {$_POST['cat']}, EJ_eventDate = '".date("Y-m-d", strtotime($_POST['date']))."', EJ_eventImage='".$_POST['image']."', EJ_eventHidden = ".$_POST['hidden'].", EJ_eventPoster = '".$_POST['poster']."', EJ_eventTime = '".$_POST['time']."', EJ_eventLoc1 = '".$_POST['location1']."', EJ_eventLoc2 = '".$_POST['location2']."', EJ_eventLoc3 = '".$_POST['location3']."', EJ_eventLoc4 = '".$_POST['location4']."', EJ_eventLoc5 = '".$_POST['location5']."', EJ_eventContact = '".$_POST['contact']."'";
	} else
	{
		$query = "UPDATE {$EJ_mysql->prefix}module_EJ_events SET EJ_eventTitle='".addslashes(urldecode($_POST['title']))."', EJ_eventText='".addslashes(str_replace(array("\n", "<br>", "<br/>", "£"),array("<br />","<br />","<br />", "&pound;"),to_utf8(urldecode($_POST['desc']))))."', EJ_eventCat = ".$_POST['cat'].", EJ_eventDate = '".date("Y-m-d", strtotime($_POST['date']))."', EJ_eventImage='".$_POST['image']."', EJ_eventHidden = ".$_POST['hidden'].", EJ_eventPoster = '".$_POST['poster']."', EJ_eventTime = '".$_POST['time']."', EJ_eventLoc1 = '".$_POST['location1']."', EJ_eventLoc2 = '".$_POST['location2']."', EJ_eventLoc3 = '".$_POST['location3']."', EJ_eventLoc4 = '".$_POST['location4']."', EJ_eventLoc5 = '".$_POST['location5']."', EJ_eventContact = '".$_POST['contact']."' WHERE EJ_eventId = ".$_POST['id']."";
	}
	$EJ_mysql->query($query);
	echo "OK";
}
?>