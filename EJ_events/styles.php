<?php
header("Content-Type: text/css");
require('../../config.inc.php');
?>
@charset "utf-8";

/*
*** EJigsaw Site Administration Suite
**
*** EJ_events Module
**
*** By Jigsaw Spain - www.jigsawspain.com
**
*** EJ_events Styles - File Build 0.1
*/

/*
* Common Tags
*/

h2.EJE
{
	border-top: #42769B 1px solid;
	border-bottom: #42769B 1px solid;
	color: #42769B;
	font-size: 1.1em;
	margin: 0;
	padding: 0 3px;
}

h2.EJE img
{
	border:0;
	height: 15px;
	margin-bottom: 0.2em;
	vertical-align: middle;
	width: 15px;
}


/*
* Classes and IDs
*/

#addLeft
{
	float: left;
	margin: 5px;
	padding: 5px;
	text-align: center;
	width: 200px;
}

#addLeft input
{
	width: 100%;
}

#addLeft img
{
	cursor: pointer;
	margin: 5px;
}

#addRight
{
	float: left;
	margin: 5px;
	padding: 5px;
	width: 736px;
}

.button
{
	cursor: pointer;
	display: inline-block;
	height: 100px;
	margin: 15px;
	overflow: hidden;
	text-indent: -1000px;
	width: 100px;
}

.button:hover
{
	background-position: 0 -100px;
}

.cat_result
{
	background-color: #FFF;
	border: #42769B 1px solid;
	height: 16px;
	line-height: 16px;
	margin: 10px;
	overflow: hidden;
	padding: 5px;
}

.cat_result img
{
	vertical-align: middle;
	margin-top: -0.2em;
}

#container
{
	color: #42769B;
	font-size: 0.9em;
}

.event_result
{
	background-color: #FFF;
	border: #42769B 1px solid;
	height: 16px;
	margin: 10px;
	overflow: hidden;
	padding: 5px;
}

.eventImage
{
	float: left;
	height: 120px;
	margin: 0 5px 5px 0;
	width: 120px;
}

.event_result p
{
	margin-bottom: 5px;
}

#search_form
{
	background-color: #FFF;
	border: #42769B 1px solid;
	margin: 10px;
	padding: 5px;
}

#event_message
{
	text-align: center;
}

/* Calendar Styles */

.back_calendar
{
	background: url(<?=$EJ_settings['instloc']?>modules/EJ_events/back_calendar.png) center 0 no-repeat;
	display: inline-block;
	height: 30px;
	margin-bottom: 10px;
	overflow: hidden;
	padding: 0;
	text-indent: -2000px;
	width: 200px;
}

.back_calendar:hover
{
	background: url(<?=$EJ_settings['instloc']?>modules/EJ_events/back_calendar.png) center -30px no-repeat;
}

.back_calendar:active
{
	background: url(<?=$EJ_settings['instloc']?>modules/EJ_events/back_calendar.png) center -60px no-repeat;
}

.date
{
	background: #42769B;
	color: #E3EEF7;
	font-weight: bold;
}

.day
{
	background: #FFF;
	border: #CCC 1px solid;
	float: left;
	height: 100px;
	margin: 0 -1px -1px 0;
	overflow: hidden;
	width: 100px;
}

.day:hover
{
	background: #E3EEF7;
	height: 110px;
	left: -5px;
	margin-right: -11px;
	margin-bottom: -11px;
	overflow: visible;
	position: relative;
	top: -5px;
	width: 110px;
}

.day_head
{
	background: #42769B;
	border: #CCC 1px solid;
	color: #FFF;
	float: left;
	font-weight: bold;
	height: 16px;
	margin: 0 -1px -1px 0;
	text-align: center;
	width: 100px;
}

#EJ_events_calendar
{
	float: left;
	margin: 5px 0 5px 5px;
	width: 707px;
}

#EJ_events_month
{
	background: #FFF;
	border: #CCC 1px solid;
	float: right;
	height: 537px;
	margin: 5px 5px 5px 0;
	width: 270px;
}

.event
{
	background: #42769B;
	color: #E3EEF7;
	cursor: pointer;
	display: inline-block;
	white-space: nowrap;
	width: 200px;
}

.event:hover
{
	background-color: #CCE0EE;
	color: #42769B;
}

.event_day
{
	border: #CCC 1px solid;
	margin: 2px;
}

.event_event
{
	border-top: #CCC 1px solid;
	padding: 3px;
}

.event_event a
{
	color: #42769B;
}

.event_event p
{
	margin: 0;
}

#events_head
{
	background: #E3EEF7;
	color: #42769B;
	font-weight: bold;
	text-align: center;
}

.event_item
{
	background: #42769B;
	color: #E3EEF7;
	display: inline-block;
	height: 16px;
	line-height: 16px;
	overflow: hidden;
	padding: 2px;
	z-index: 98;
}

.event_item:hover
{
	background-color: #CCE0EE;
	color: #42769B;
}

#event_left
{
	background: #FFF;
	border: #CCC 1px solid;
	float: left;
	margin:  5px 0 5px 5px;
	text-align: center;
	width: 250px;
}

#event_right
{
	background: #FFF;
	border: #CCC 1px solid;
	float: right;
	margin: 5px 5px 5px 0;
	width: 720px;
}

#event_right p
{
	margin: 5px;
}

#month_name
{
	background: #E3EEF7;
	border: #CCC 1px solid;
	color: #42769B;
	font-weight: bold;
	height: 16px;
	margin-bottom: -1px;
	text-align: center;
	width: 705px;
}

.today
{
	background: #E3EEF7;
}

/*.upcoming
{
	font-size: 0.8em;
	padding: 3px;
}*/

/*.upcoming a
{
	color: #009ACA;
}*/

/*.upcoming p
{
	margin: 0;
}*/

.we
{
	background: #DDD;
}

.Xday
{
	background: #999;
}