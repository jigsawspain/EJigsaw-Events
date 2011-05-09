<?php

/*
*** EJigsaw Events Admin Module
**
*** By Jigsaw Spain - www.jigsawspain.com
*/

if (!class_exists("EJ_events"))
{
class EJ_events
{
	public $version = "0.3";
	public $creator = "Jigsaw Spain";
	public $name = "EJigsaw Events";
	private $EJ_mysql;
	private $vars;
	private $moduleloc;
	private $settings;
	
	function EJ_events ($EJ_mysql, $_vars, $_settings)
	{
		$this->EJ_mysql = $EJ_mysql;
		$this->vars = $_vars;
		$this->moduleloc = "modules/EJ_events/";
		$this->settings = $_settings;
	}
	
	function install()
	{
		echo "
			<p class=\"EJ_instText\">
			&gt; EJ Events Install Procedure
			</p>";
		// Check for / create table
		echo "
			<p class=\"EJ_instText\">
			&gt; Checking and Creating Tables...
			</p>";
		$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_events%'");
		if ($this->EJ_mysql->numRows() == 2)
		{
			echo "
			<p class=\"EJ_instText\">
				&gt;&gt; EJ_events tables already found!<br/>
				&gt;&gt; Checking default settings
			</p>";
		} else
		{
			// Main Events Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_events (
				EJ_eventId INT(11) NOT NULL AUTO_INCREMENT ,
				EJ_eventDate DATE NOT NULL ,
				EJ_eventTitle VARCHAR(100) NOT NULL ,
				EJ_eventText TEXT NOT NULL ,
				EJ_eventImage VARCHAR(50) ,
				EJ_eventHidden TINYINT(1) NOT NULL DEFAULT 1 ,
				EJ_eventPoster VARCHAR(20) NOT NULL ,
				EJ_eventCat INT(6) NOT NULL ,
				EJ_eventLoc1 VARCHAR(100) NOT NULL ,
				EJ_eventLoc2 VARCHAR(100) ,
				EJ_eventLoc3 VARCHAR(100) ,
				EJ_eventLoc4 VARCHAR(100) ,
				EJ_eventLoc5 VARCHAR(100) ,
				EJ_eventTime VARCHAR(5) NOT NULL ,
				EJ_eventContact VARCHAR(150) NOT NULL ,
				EJ_eventHits INT(6) NOT NULL ,
				PRIMARY KEY (EJ_eventId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_events'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Event Settings Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_events_settings (
				setting VARCHAR(20) NOT NULL ,
				value VARCHAR(100) NOT NULL ,
				PRIMARY KEY (setting)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_events_settings'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Event Categories Table
			$this->EJ_mysql->query("CREATE TABLE IF NOT EXISTS {$this->EJ_mysql->prefix}module_EJ_events_cats (
				catId INT(6) NOT NULL AUTO_INCREMENT,
				subCatOf INT(6) ,
				catName VARCHAR(30) NOT NULL ,
				catDesc TEXT ,
				PRIMARY KEY (catId)
				)");
			$this->EJ_mysql->query("SHOW TABLES LIKE '{$this->EJ_mysql->prefix}module_EJ_events_cats'");
			if ($this->EJ_mysql->numRows()!=1) return false;
			// Create initial event
			$this->EJ_mysql->query("SELECT catId FROM {$this->EJ_mysql->prefix}module_EJ_events_cats");
			if ($this->EJ_mysql->numRows()==0)
			{
				echo "
				<p class=\"EJ_instText\">
				&gt; Creating initital event...
				</p>";
				$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_events SET EJ_eventDate = DATE(NOW()), EJ_eventTitle = 'EJ Events Installed Successfully!', EJ_eventText = 'This event has been added by the EJ Events setup procedure to demonstrate how your events will display on your site.<br /><br />Please edit or delete this event when you are happy with your setup.<br /><br />EJ Events - By Jigsaw Spain - <a href=\"http://www.jigsawspain.com\" target=\"_blank\">http://www.jigsawspain.com</a>', EJ_eventHidden = 0, EJ_eventPoster = 'admin', EJ_eventCat = 0, EJ_eventImage='noimage.png', EJ_eventLoc1 = 'No Address Provided', EJ_eventTime = '12:00', EJ_eventContact = 'admin@jigsawspain.com'");
			}
			// Create initial categories
			$this->EJ_mysql->query("SELECT catId FROM {$this->EJ_mysql->prefix}module_EJ_events_cats");
			if ($this->EJ_mysql->numRows()==0)
			{
				echo "
				<p class=\"EJ_instText\">
				&gt; Creating initital event category...
				</p>";
				$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_events_cats SET subCatOf = NULL, catname = 'Default Category', catDesc = 'This is the default category set up by EJ_events'");
			}
		}
		// Check for / set up user permissions
		echo "
				<p class=\"EJ_instText\">
				&gt; Checking user permissions...
				</p>";
		$this->EJ_mysql->query("SHOW COLUMNS FROM {$this->EJ_mysql->prefix}users LIKE 'perm_EJ_events'");
		if ($this->EJ_mysql->numRows()==0)
		{
			$this->EJ_mysql->query("ALTER TABLE {$this->EJ_mysql->prefix}users ADD perm_EJ_events TINYINT(1) NOT NULL DEFAULT 0");
		}
		$this->EJ_mysql->query("UPDATE {$this->EJ_mysql->prefix}users SET perm_EJ_events = 1 WHERE userid = 'admin'");
		// Check / create initial settings
		echo "
			<p class=\"EJ_instText\">
			&gt; Creating initital settings...
			</p>";
		$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}module_EJ_events_settings (setting, value) VALUES
			('small_width', '250px') ,
			('small_height', '350px') ,
			('small_articles', '3') ,
			('small_word_count', '25') ,
			('small_show_images', '1') ,
			('large_word_count', '30')
			ON DUPLICATE KEY UPDATE setting = setting");
		// Update module registry
		echo "
			<p class=\"EJ_instText\">
			&gt; Updating Module Registry...
			</p>";
		$this->EJ_mysql->query("INSERT INTO {$this->EJ_mysql->prefix}modules (moduleid, version, name, creator) VALUES
			('".get_class()."', '{$this->version}', '{$this->name}', '{$this->creator}')
			ON DUPLICATE KEY UPDATE moduleid = moduleid");
		echo "
			<p class=\"EJ_instText\">
			&gt; Install Successful!
			</p>";
		return true;
	}
	
	function update()
	{
		echo "
			<p class=\"EJ_instText\">
			&gt; EJ Events Update Procedure
			</p>";
		switch ($this->vars['oldversion'])
		{
			case '0.1':
				echo "
			<p class=\"EJ_instText\">
			&gt; Updating Database Tables
			</p>";
				$this->EJ_mysql->query("ALTER TABLE {$this->EJ_mysql->prefix}module_EJ_events
					ADD COLUMN EJ_eventLoc1 VARCHAR(100) NOT NULL,
					ADD COLUMN EJ_eventLoc2 VARCHAR(100),
					ADD COLUMN EJ_eventLoc3 VARCHAR(100),
					ADD COLUMN EJ_eventLoc4 VARCHAR(100),
					ADD COLUMN EJ_eventLoc5 VARCHAR(100),
					ADD COLUMN EJ_eventTime VARCHAR(5) NOT NULL,
					ADD COLUMN EJ_eventContact VARCHAR(150) NOT NULL,
					ADD COLUMN EJ_eventHits INT(6) NOT NULL DEFAULT 0
				");
			break;
			case '0.2':
				echo "
			<p class=\"EJ_instText\">
			&gt; Updating Database Tables
			</p>";
				$this->EJ_mysql->query("ALTER TABLE {$this->EJ_mysql->prefix}module_EJ_events
					ADD COLUMN EJ_eventLoc1 VARCHAR(100) NOT NULL,
					ADD COLUMN EJ_eventLoc2 VARCHAR(100),
					ADD COLUMN EJ_eventLoc3 VARCHAR(100),
					ADD COLUMN EJ_eventLoc4 VARCHAR(100),
					ADD COLUMN EJ_eventLoc5 VARCHAR(100),
					ADD COLUMN EJ_eventTime VARCHAR(5) NOT NULL,
					ADD COLUMN EJ_eventContact VARCHAR(150) NOT NULL,
					ADD COLUMN EJ_eventHits INT(6) NOT NULL DEFAULT 0
				");
			break;
		}
		echo "
			<p class=\"EJ_instText\">
			&gt; Updating Module Registry...
			</p>";
		$this->EJ_mysql->query("UPDATE {$this->EJ_mysql->prefix}modules SET version = '{$this->version}' WHERE moduleid = '".get_class($this)."'");
		echo "
			<p class=\"EJ_instText\">
			&gt; Update to Version {$this->version} Successful!
			</p>";
	}
	
	function uninstall()
	{
		echo "
			<p class=\"EJ_instText\">
			&gt; Checking and Removing Tables...
			</p>";
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_events");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_events_settings");
		$this->EJ_mysql->query("DROP TABLE IF EXISTS {$this->EJ_mysql->prefix}module_EJ_events_cats");
		echo "
			<p class=\"EJ_instText\">
			&gt; Removing User Permissions...
			</p>";
		$this->EJ_mysql->query("SHOW COLUMNS FROM {$this->EJ_mysql->prefix}users LIKE 'perm_EJ_events'");
		if ($this->EJ_mysql->numRows()!=0)
		{
			$this->EJ_mysql->query("ALTER TABLE {$this->EJ_mysql->prefix}users DROP perm_EJ_events");
		}
		echo "
			<p class=\"EJ_instText\">
			&gt; Updating Module Registry...
			</p>";
		$this->EJ_mysql->query("DELETE FROM {$this->EJ_mysql->prefix}modules WHERE moduleid = '".get_class()."'");
		echo "
			<p class=\"EJ_instText\">
			&gt; Uninstall Successful...
			</p>";
		return true;
	}
	
	function admin_page()
	{
		$content = "";
		$content .= '<a class="button" style="background-image: url('.$this->moduleloc.'add_icon.png)" href="./?module=EJ_events&action=addevent">Add Event</a><a class="button" style="background-image: url('.$this->moduleloc.'search_icon.png)" href="./?module=EJ_events&action=search">Event Search</a><a class="button" style="background-image: url('.$this->moduleloc.'cats_icon.png)" href="./?module=EJ_events&action=cats">Categories</a><a class="button" style="background-image: url('.$this->moduleloc.'preview_icon.png)" href="./?module=EJ_events&action=event_calendar&preview=true">Categories</a>';
		echo $content;
	}
	
	function search()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>';
		$content .= '<h2  class="EJE" class="EJE"><img src="'.$this->moduleloc.'search_icon_small.png" alt="Event Filter" /> Event Filter</h2>';
		$results = array();
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->EJ_mysql->prefix}module_EJ_events WHERE 1=1";
		$eventdate = time();
		$eventtext = "";
		$eventcat = "";
		$eventposter = "";
		$anycheck = ' checked="checked"';
		if (!empty($this->vars['search']))
		{
			$s1 = explode(":::",$this->vars['search']);
			foreach ($s1 as $s2)
			{
				$s2 = explode("::", $s2);
				$search[$s2[0]] = $s2[1];
			}
			foreach ($search as $key => $value)
			{
				$skip = 0;
				switch ($key)
				{
					case 'EJ_eventDate' :
						$anycheck = "";
						$eventdate = strtotime($value);
					break;
					case 'EJ_eventText' :
						$skip=1;
						$query .= " AND (EJ_eventText LIKE '%$value%' OR EJ_eventTitle LIKE '%$value%')";
						$eventtext = " value=\"$value\"";
					break;
					case 'EJ_eventCat' :
						$eventcat = $value;
					break;
					case 'EJ_eventPoster' :
						$eventposter = $value;
					break;
				}
				if (!is_numeric($value)) $value = "'".$value."'";
				if ($skip==0) $query .= " AND $key = $value";
			}
		}
		if (isset($this->vars['items']))
		{
			$query .= " LIMIT ".$this->vars['items'];
		} else
		{
			$query .= " LIMIT 10";
		}
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_events_cats ORDER BY catName");
		$categories = "";
		while ($cat = $this->EJ_mysql->getRow())
		{
			$selected = "";
			if ($eventcat == $cat['catId']) $selected = ' selected="selected"';
			$categories .= "<option value=\"{$cat['catId']}\"$selected>{$cat['catName']}</option>\n						";
		}
		$this->EJ_mysql->query("SELECT EJ_eventPoster FROM {$this->EJ_mysql->prefix}module_EJ_events GROUP BY EJ_eventPoster ORDER BY EJ_eventPoster");
		$posters = "";
		while ($post = $this->EJ_mysql->getRow())
		{
			$selected = "";
			if ($eventposter == $post['EJ_eventPoster']) $selected = ' selected="selected"';
			$posters .= "<option value=\"{$post['EJ_eventPoster']}\"$selected>{$post['EJ_eventPoster']}</option>\n						";
		}
		ob_start();
		$this->EJ_mysql->query($query);
		$content .= ob_get_contents();
		ob_end_clean();
		$content .= '
			<script src="'.$this->moduleloc.'EJ_events.js" language="javascript" type="text/javascript"></script>
			<script src="'.$this->moduleloc.'calendar.js" language="javascript" type="text/javascript"></script>
			<form name="search_form" id="search_form" method="post" action="./?module=EJ_events&action=search&search=go">
				<div style="float:left;">
					<strong>Date</strong>: Any Date <input type="checkbox" name="anydate" id="anydate" value="true"'.$anycheck.' onchange="updateFilter(\''.$_SESSION['key'].'\')"/> <strong>OR</strong><script>DateInput(\'date\', true, \'DD-MON-YYYY\', \''.date("d-M-Y", $eventdate).'\' , \''.$_SESSION['key'].'\');</script>
				</div>
				<div style="float:left; margin-right: 10px;">
					<strong>Title/Text Search</strong>:<br/>
					<input type="text" name="search_text" id="search_text" onkeyup="updateFilter(\''.$_SESSION['key'].'\')"'.$eventtext.' />
				</div>
				<div style="float:left; margin-right: 10px;">
					<strong>Category</strong>:<br/>
					<select name="category" id="category" onchange="updateFilter(\''.$_SESSION['key'].'\')" />
						<option value="0">Any Category</option>
						'.$categories.'
					</select>
				</div>
				<div style="float:left; margin-right: 10px;">
					<strong>Posted By</strong>:<br/>
					<select name="poster" id="poster" onchange="updateFilter(\''.$_SESSION['key'].'\')" >
					<option value="0">Any Poster</option>
					'.$posters.'
					</select>
				</div>
				<div style="float:right;">
					<strong>Include Hidden</strong>:	<input type="checkbox" name="hidden" id="hidden" onchange="updateFilter(\''.$_SESSION['key'].'\')" checked="checked" /><br/>
					Show
					<select name="limit" id="limit"onchange="updateFilter(\''.$_SESSION['key'].'\')">
						<option value="10" selected="selected">10</option>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
					Results
				</div>
				<div style="clear:both;"></div>
			</form>
			';
		$result_count = $this->EJ_mysql->numRows();
		$content .= '<h2 class="EJE"><div style="float:right; margin-right: 5px;">Results Found: <span id="result_count">';
		$content2 = '</span></div><img src="'.$this->moduleloc.'search_icon_small.png" alt="Search Results" /> Search Results (click result to show/hide details)</h2>
			<div id="event_message"></div>
			<div id="search_results">';
		while ($event = $this->EJ_mysql->getRow())
		{
			$date = date("d/m/Y", strtotime($event['EJ_eventDate']));
			if (empty($event['EJ_eventImage']) or !file_exists($this->moduleloc."images/".$event['EJ_eventImage']))
			{
				$img = "noimage.png";
			} else
			{
				$img = $event['EJ_eventImage'];
			}
			$content2 .= "
				<div class=\"event_result\" id=\"{$event['EJ_eventId']}\">
					<div style=\"float: right;\"><img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Event\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteEvent('{$event['EJ_eventId']}', '{$_SESSION['key']}')\" /> <a href=\"?module=EJ_events&action=editevent&eventid={$event['EJ_eventId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Event\" style=\"cursor: pointer; border: 0;\" /></a> <img src=\"".$this->moduleloc."blue_down.png\" alt=\"show/hide details\" title=\"Show/Hide Details\"style=\"cursor: pointer;\" onclick=\"Slide(this.parentNode.parentNode, 16, 150)\" /></div>
					<p><strong>$date - {$event['EJ_eventTitle']}</strong> posted by: {$event['EJ_eventPoster']}</p>
					<p><img src=\"{$this->moduleloc}images/$img\" alt=\"{$event['EJ_eventTitle']}\" class=\"eventImage\" />{$event['EJ_eventText']}</p>
				</div>";
		}
		$content2 .= '
			</div>
';
		$this->EJ_mysql->query("SELECT FOUND_ROWS() as results");
		$result_count = $this->EJ_mysql->getRow();
		echo $content.$result_count['results'].$content2;
	}
	
	function addevent()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>
				<h2  class="EJE"><img src="'.$this->moduleloc.'add_icon_small.png" alt="Add Event" /> Add Event</h2>';
		$content .= '
				<script src="'.$this->moduleloc.'EJ_events.js" language="javascript" type="text/javascript"></script>
				<script src="'.$this->moduleloc.'addcalendar.js" language="javascript" type="text/javascript"></script>
				<div id="addEvent">
					<form name="add_form" id="add_form" action="?module=EJ_events&action=addevent" method="post">
						<div id="addLeft">
							Click Image To Change<br/>
							<img id="eventimage" src="'.$this->moduleloc.'images/noimage.png" alt="Add An Image" title="Click to Add an Image" onclick="changepic()" style="width:200px; height:200px;" /><br/>
							<input type="hidden" name="image" id="image" />
							<input type="button" name="save" id="save" value="Save Changes" onclick="saveEvent(\''.$_SESSION['key'].'\')"/><br/>
							<input type="button" name="cancel" id="cancel" value="Cancel Changes" onclick="document.location=\'?module=EJ_events&action=admin_page\'"/>
						</div>
						<div id="addRight">
							<strong>Event Title:</strong><br/><input type="text" name="title" id="title" maxlength="100" size="40" /><br/>
							<strong>Event Description:</strong><br/>
							<textarea name="desc" id="desc" rows="5" cols="40" /></textarea><br/>
							<strong>Category:</strong><br/>
							<select name="cat" id="cat">
								<option value="NONE" selected="selected">Please Select...</option>';
		$this->EJ_mysql->query("SELECT catId, subCatOf, catName, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_events_cats cats2 WHERE cats2.catId = cats1.subCatOf) AS parent FROM {$this->EJ_mysql->prefix}module_EJ_events_cats cats1 ORDER BY parent ASC, catName ASC");
		while ($cat = $this->EJ_mysql->getRow())
		{
			if (!empty($cat['parent']))
			{
			$content .= '
								<option value="'.$cat['catId'].'">'.$cat['parent'].'&gt;'.$cat['catName'].' ('.$cat['subCatOf'].'&gt;'.$cat['catId'].')</option>';
			} else
			{
				$content .= '
								<option value="'.$cat['catId'].'">'.$cat['catName'].' ('.$cat['catId'].')</option>';
			}
		}
		$content .= '
							</select><br/>
							<strong>Event Date:</strong><br/>
							<script>DateInput(\'date\', true, \'DD-MON-YYYY\', \''.date("d-M-Y").'\' , \''.$_SESSION['key'].'\');</script>
							<strong>Event Time: (hh:mm)</strong><br/>
							<input type="text" name="time" id="time" maxlength="5" size="5" /><br/>
							<strong>Location:</strong><br/>
							<input type="text" name="location1" id="location1" maxlength="100" size="40" /><br/>
							<input type="text" name="location2" id="location2" maxlength="100" size="40" /><br/>
							<input type="text" name="location3" id="location3" maxlength="100" size="40" /><br/>
							<input type="text" name="location4" id="location4" maxlength="100" size="40" /><br/>
							<input type="text" name="location5" id="location5" maxlength="100" size="40" /><br/>
							<strong>Contact Email:</strong><br/>
							<input type="text" name="contact" id="contact" maxlength="150" size="40" /><br/>
							<strong>Posted By:</strong><br/>
							<select name="poster" id="poster">
								<option value="NONE" selected="selected">Please Select...</option>';
		if ($_SESSION['usertype']==9)
		{
			$usertype = 10;
		} else
		{
			$usertype = $_SESSION['usertype'];
		}
		$this->EJ_mysql->query("SELECT userid FROM {$this->EJ_mysql->prefix}users WHERE type < ".$usertype."");
		while ($user = $this->EJ_mysql->getRow())
		{
			if ($user['userid']==$_SESSION['userid'])
			{
				$selected = " selected=\"selected\"";
			} else
			{
				$selected = "";
			}
			$content .= '
								<option value="'.$user['userid'].'"'.$selected.'>'.$user['userid'].'</option>';
		}
		$content .= '
							</select><br/>
							<strong>Visibility:</strong><br/>
							<select name="hidden" id="hidden">
								<option value="1" selected="selected">Hidden</option>
								<option value="0">Visible</option>
							</select>
							<div id="event_message"></div>
						</div>
						<div style="clear: left;"></div>
					</form>
				</div>';
		echo $content;
	}
	
	function editevent()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>
				<h2  class="EJE"><img src="'.$this->moduleloc.'edit.png" alt="Edit Event" /> Edit Event</h2>';
		$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_events WHERE EJ_eventId = ".$this->vars['eventid']);
		if ($this->EJ_mysql->numRows()!=1)
		{
			$content .= '
				<div class="EJ_user_error"><strong>ERROR</strong>: Event Id Not Found!<br/>Please try again.</div>';
		} else
		{
			$event = $this->EJ_mysql->getRow();
			if (empty($event['EJ_eventImage']) or !file_exists($this->moduleloc."images/".$event['EJ_eventImage'])) 
				$img = "noimage.png"; 
			else 
				$img = $event['EJ_eventImage'];
			$content .= '
				<script src="'.$this->moduleloc.'EJ_events.js" language="javascript" type="text/javascript"></script>
				<script src="'.$this->moduleloc.'addcalendar.js" language="javascript" type="text/javascript"></script>
				<div id="addEvent">
					<form name="add_form" id="add_form" action="?module=EJ_events&action=editevent" method="post">
						<div id="addLeft">
							Click Image To Change<br/>
							<img id="eventimage" src="'.$this->moduleloc.'images/'.$img.'" alt="Change Image" title="Click to Change Image" onclick="changepic()" style="width:200px; height:200px;" /><br/>
							<input type="hidden" name="image" id="image" value="'.$img.'" />
							<input type="button" name="save" id="save" value="Save Changes" onclick="saveEvent(\''.$_SESSION['key'].'\','.$this->vars['eventid'].')"/><br/>
							<input type="button" name="cancel" id="cancel" value="Cancel Changes" onclick="document.location=\'?module=EJ_events&action=admin_page\'"/>
						</div>
						<div id="addRight">
							<strong>Event Title:</strong><br/><input type="text" name="title" id="title" maxlength="100" size="40" value="'.$event['EJ_eventTitle'].'" /><br/>
							<strong>Event Description:</strong><br/>
							<textarea name="desc" id="desc" rows="5" cols="40" />'.str_replace(array("<br/>","<br />"), "\n", $event['EJ_eventText']).'</textarea><br/>
							<strong>Category:</strong><br/>
							<select name="cat" id="cat">
								<option value="NONE" selected="selected">Please Select...</option>';
			$this->EJ_mysql->query("SELECT catId, subCatOf, catName, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_events_cats cats2 WHERE cats2.catId = cats1.subCatOf) AS parent FROM {$this->EJ_mysql->prefix}module_EJ_events_cats cats1 ORDER BY parent ASC, catName ASC");
			while ($cat = $this->EJ_mysql->getRow())
			{
				if ($event['EJ_eventCat']==$cat['catId'])
				{
					$selected = " selected=\"selected\"";
				} else
				{
					$selected = "";
				}
				if (!empty($cat['parent']))
				{
				$content .= '
								<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['parent'].'&gt;'.$cat['catName'].' ('.$cat['subCatOf'].'&gt;'.$cat['catId'].')</option>';
				} else
				{
					$content .= '
								<option value="'.$cat['catId'].'"'.$selected.'>'.$cat['catName'].' ('.$cat['catId'].')</option>';
				}
			}
			$content .= '
							</select><br/>
							<strong>Event Date:</strong><br/>
							<script>DateInput(\'date\', true, \'DD-MON-YYYY\', \''.date("d-M-Y", strtotime($event['EJ_eventDate'])).'\' , \''.$_SESSION['key'].'\');</script>
							<strong>Event Time: (hh:mm)</strong><br/>
							<input type="text" name="time" id="time" maxlength="5" size="5" value="'.$event['EJ_eventTime'].'" /><br/>
							<strong>Location:</strong><br/>
							<input type="text" name="location1" id="location1" maxlength="100" size="40" value="'.$event['EJ_eventLoc1'].'" /><br/>
							<input type="text" name="location2" id="location2" maxlength="100" size="40" value="'.$event['EJ_eventLoc2'].'" /><br/>
							<input type="text" name="location3" id="location3" maxlength="100" size="40" value="'.$event['EJ_eventLoc3'].'" /><br/>
							<input type="text" name="location4" id="location4" maxlength="100" size="40" value="'.$event['EJ_eventLoc4'].'" /><br/>
							<input type="text" name="location5" id="location5" maxlength="100" size="40" value="'.$event['EJ_eventLoc5'].'" /><br/>
							<strong>Contact Email:</strong><br/>
							<input type="text" name="contact" id="contact" maxlength="150" size="40" value="'.$event['EJ_eventContact'].'" /><br/>
							<strong>Posted By:</strong><br/>
							<select name="poster" id="poster">
								<option value="NONE" selected="selected">Please Select...</option>';
			if ($_SESSION['usertype']==9)
			{
				$usertype = 10;
			} else
			{
				$usertype = $_SESSION['usertype'];
			}
			$this->EJ_mysql->query("SELECT userid FROM {$this->EJ_mysql->prefix}users WHERE type < ".$usertype."");
			while ($user = $this->EJ_mysql->getRow())
			{
				if ($user['userid']==$event['EJ_eventPoster'])
				{
					$selected = " selected=\"selected\"";
				} else
				{
					$selected = "";
				}
				$content .= '
								<option value="'.$user['userid'].'"'.$selected.'>'.$user['userid'].'</option>';
			}
			if ($event['EJ_eventHidden']==0)
			{
				$selectedvisible = " selected=\"selected\"";
				$selectedhidden = "";
			} else
			{
				$selectedvisible = "";
				$selectedhidden = " selected=\"selected\"";
			}
			$content .= '
							</select><br/>
							<strong>Visibility:</strong><br/>
							<select name="hidden" id="hidden">
								<option value="1"'.$selectedhidden.'>Hidden</option>
								<option value="0"'.$selectedvisible.'>Visible</option>
							</select>
							<div id="event_message"></div>
						</div>
						<div style="clear: left;"></div>
					</form>';
		}
		$content .= '
				</div>';
		echo $content;
	}
	
	function cats()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>
				<h2  class="EJE"><img src="'.$this->moduleloc.'cats_icon_small.png" alt="Categories" /> Categories</h2>
				<script src="'.$this->moduleloc.'EJ_events.js" language="javascript" type="text/javascript"></script>';
		$this->EJ_mysql->query("SELECT *,(SELECT COUNT(*) FROM {$this->EJ_mysql->prefix}module_EJ_events WHERE EJ_eventCat = catId) AS events FROM {$this->EJ_mysql->prefix}module_EJ_events_cats ORDER BY subCatOf ASC, catName ASC");
		while ($cat = $this->EJ_mysql->getRow())
		{
			$cats[$cat['catId']] = $cat;
		}
		foreach ($cats as $cat)
		{
			$count = 0;
			$content .= '<div id="event_message"></div>';
			if (empty($cat['subCatOf']))
			{
				$content .= "<div class=\"cat_result\" id=\"{$cat['catId']}\">";
				foreach ($cats as $subcat)
				{
					if ($subcat['subCatOf'] == $cat['catId'])
					{
						$count += $subcat['events'];
						$subcats[$subcat['catId']] = $subcat;
					}
				}
				$content .= "<div style=\"float: right;\">";
				if (count($subcats)==0)
				{
					$content .= "<img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Category\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteCat('{$cat['catId']}','{$_SESSION['key']}')\" /> ";
				}
				$content .= "<a href=\"?module=EJ_events&action=editcat&catid={$cat['catId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Category\" style=\"cursor: pointer; border: 0;\" /></a>";
				if (count($subcats)!=0)
				{
					$content .= " <img src=\"".$this->moduleloc."blue_down.png\" alt=\"show/hide details\" title=\"Show/Hide Details\"style=\"cursor: pointer;\" onclick=\"Slide(this.parentNode.parentNode, 16, ".((count($subcats)+2)*17).")\" />";
				}
				$content .= "</div>";
				if (count($subcats)==0)
				{
					$content .= "<img class=\"event_cat_img\"src=\"{$this->moduleloc}cat_no_sub.png\" alt=\"\" />";
				} else
				{
					$content .= "<img class=\"event_cat_img\" src=\"{$this->moduleloc}cat_with_sub.png\" alt=\"\" style=\"cursor: pointer;\" onclick=\"Slide(this.parentNode, 16, ".((count($subcats)+2)*16).")\" />";
				}
				$content .= " {$cat['catName']} ({$cat['events']}";
				if ($count != 0)
				{
					$content .= " + $count in Sub-Categories";
				}
				$content .= ")";
				if ($cat['events']!=0) $content .= " <a href=\"?module=EJ_events&action=search&search=EJ_eventCat::{$cat['catId']}\"><img src=\"".$this->moduleloc."search_icon_small.png\" alt=\"Find\" title=\"Show events in this category\" /></a>";
				$i = 1;
				if (count($subcats) != 0)
				{
					foreach($subcats as $subcat)
					{
						if ($i == count($subcats))
						{
							$content .= "<br/><img class=\"event_cat_img\" src=\"{$this->moduleloc}sub_last.png\" alt=\"\" />";
						} else
						{
							$content .= "<br/><img class=\"event_cat_img\" src=\"{$this->moduleloc}sub_middle.png\" alt=\"\" />";
						}
						$content .= " {$subcat['catName']} ({$subcat['events']})";
						if ($subcat['events']!=0) $content .= " <a href=\"?module=EJ_events&action=search&search=EJ_eventCat::{$subcat['catId']}\"><img src=\"".$this->moduleloc."search_icon_small.png\" alt=\"Find\" title=\"Show events in this category\" /></a>";
						$content .= " <a href=\"?module=EJ_events&action=editcat&catid={$subcat['catId']}\"><img src=\"".$this->moduleloc."edit.png\" alt=\"edit\" title=\"Edit Category\" style=\"cursor: pointer; border: 0;\" /></a>";
						if ($subcat['events']==0)
						{
							$content .= "<img src=\"".$this->moduleloc."recycle.png\" alt=\"delete\" title=\"Delete Category\" style=\"cursor: pointer; border: 0;\" onclick=\"deleteCat('{$subcat['catId']}','{$_SESSION['key']}')\" />";
						}
						$i++;
					}
				}
				$content .= "</div>";
				unset($subcats);
			}
		}
		$content .= "
		<div>
			<h2  class=\"EJE\"><img src=\"{$this->moduleloc}cats_icon_small.png\" alt=\"Categories\" /> Add New Category</h2>
			<form name=\"new_cat_form\" id=\"new_cat_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Category Name:</strong><br/>
					<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" />
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Sub-Category Of:</strong><br/>
					<select name=\"new_sub\" id=\"new_sub\">
						<option value=\"NONE\">None - Main Category</option>
						";
			foreach ($cats as $cat)
			{
				if (empty($cat['subCatOf'])) $content .= "<option value=\"{$cat['catId']}\">{$cat['catName']}</option>";
			}
			$content .= "
					</select>
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<strong>Description: (optional)</strong><br/>
					<textarea name=\"new_desc\" id=\"new_desc\" rows=\"3\" cols=\"40\" /></textarea>
				</div>
				<div style=\"float:left; margin-right: 5px;\">
					<input type=\"hidden\" name=\"catid\" id=\"catid\" value=\"\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Add Category\" onclick=\"addCat('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
				</div>
				<div style=\"clear:left;\" id=\"new_cat_message\"></div>
			</form>
		</div>";
		echo $content;
	}
	
	function editcat()
	{
		$content = '<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>
				<h2  class="EJE"><img src="'.$this->moduleloc.'edit.png" alt="Edit Category" /> Edit Category</h2>';
		$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_events_cats WHERE catId = ".$this->vars['catid']);
		if ($this->EJ_mysql->numRows()!=1)
		{
			$content .= '
				<div class="EJ_user_error"><strong>ERROR</strong>: Category Id Not Found!<br/>Please try again.</div>';
		} else
		{
			$cat = $this->EJ_mysql->getRow();
			$selected = "";
			if (empty($cat['subCatOf'])) $selected = ' selected="selected"';
			$content .= "
				<script src=\"{$this->moduleloc}EJ_events.js\" language=\"javascript\" type=\"text/javascript\"></script>
				<div>
					<form name=\"new_cat_form\" id=\"new_cat_form\" method=\"post\" action=\"#\" style=\"margin: 10px;\">
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Category Name:</strong><br/>
							<input type=\"text\" name=\"new_name\" id=\"new_name\" maxlength=\"30\" value=\"{$cat['catName']}\" />
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Sub-Category Of:</strong><br/>
							<select name=\"new_sub\" id=\"new_sub\">
								<option value=\"NONE\"$selected>None - Main Category</option>
								";
					$this->EJ_mysql->query("SELECT * FROM ".$this->EJ_mysql->prefix."module_EJ_events_cats WHERE (ISNULL(subCatOf) OR subCatOf = '') AND catId != ".$cat['catId']);
					while ($cat1 = $this->EJ_mysql->getRow())
					{
						$cats[$cat1['catId']] = $cat1;
					}
					foreach ($cats as $cat1)
					{
						$selected = "";
						if ($cat1['catId'] == $cat['subCatOf']) $selected= ' selected="selected"';
						$content .= "<option value=\"{$cat1['catId']}\"$selected>{$cat1['catName']}</option>";
					}
					$desc = nl2br($cat['catDesc']);
					$content .= "
							</select>
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<strong>Description: (optional)</strong><br/>
							<textarea name=\"new_desc\" id=\"new_desc\" rows=\"3\" cols=\"40\" />{$desc}</textarea>
						</div>
						<div style=\"float:left; margin-right: 5px;\">
							<input type=\"hidden\" name=\"catid\" id=\"catid\" value=\"{$cat['catId']}\"/><input type=\"button\" name=\"save\" id=\"save\" value=\"Save Changes\" onclick=\"addCat('{$_SESSION['key']}')\" style=\"margin-top: 15px; height: 52px; width: 150px;\" />
						</div>
						<div style=\"clear:left;\" id=\"new_cat_message\"></div>
					</form>";
		}
		$content .= '
				</div>';
		echo $content;
	}
	
	function event_calendar()
	{
		$preview = "";
		if ($this->vars['preview']=='true')
		{
			$content .= '
			<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>
			<h2  class="EJE"><img src="'.$this->moduleloc.'calendar.jpg"/> Calendar Preview</h2>';
			$preview = "&preview=true";
		}
		$dow = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		if (!isset($this->vars['month']))
		{
			$month = date('M Y');
			$lastmonth = date('M Y', strtotime($month." -1 month"));
			$nextmonth = date('M Y', strtotime($month." +1 month"));
			$days = date('t');
		}
		else
		{
			$month = date('M Y', strtotime($this->vars['month']));
			$lastmonth = date('M Y', strtotime($month." -1 month"));
			$nextmonth = date('M Y', strtotime($month." +1 month"));
			$days = date('t', strtotime($this->vars['month']));
		}
		$this->EJ_mysql->query("SELECT EJ_eventId, EJ_eventDate, EJ_eventTitle, EJ_eventText, EJ_eventTime FROM {$this->EJ_mysql->prefix}module_EJ_events WHERE EJ_eventDate >= '".date('Y-m-d', strtotime('01 '.$month))."' AND EJ_eventDate <= '".date("Y-m-d", strtotime($days.' '.$month))."' AND EJ_eventHidden = 0 ORDER BY EJ_eventDate ASC, EJ_eventTime ASC");
		while ($event = $this->EJ_mysql->getRow())
		{
			$events[number_format(date('d', strtotime($event['EJ_eventDate'])))][] = $event;
		}
		$content .= '
			<div id="EJ_events_month">
				<div id="events_head">Upcoming Events in '.$month.'</div>';
		$count=0;
		if (isset($events) and count($events!=0))
		{
			foreach ($events as $dayno => $day)
			{
				if (strtotime($dayno." ".$month) >= strtotime(date('Y-m-d')) and $count != 5)
				{
					if (date('d/m/Y', strtotime($dayno." ".$month)) == date('d/m/Y'))
					{
						$daydate = "Today";
					}
					else
					{
						$daydate = date('D d/m/Y', strtotime($dayno." ".$month));
					}
					$content .= '
					<div class="event_day">
						<div class="date"><strong>'.$daydate.'</strong></div>';
					foreach ($day as $event)
					{
						$content .= '
						<div class="event_event">
							<p><i>'.$event['EJ_eventTime'].'</i></p>
							<p><strong><a href="?module=EJ_events&action=show_event'.$preview.'&eventid='.$event['EJ_eventId'].'">'.$event['EJ_eventTitle'].'</a></strong></p>
							<p>'.substr($event['EJ_eventText'],0,50).'... <a href="?module=EJ_events&action=show_event'.$preview.'&eventid='.$event['EJ_eventId'].'">more</a></p>
						</div>';
					}
					$content .= '
					</div>';
					$count++;
				}
			}
		}
		if (!isset($events) or $count==0)
		{
			$content .= '<p style="font-weight: bold; text-align: center; margin-top: 10px;">No upcoming events found for this month</p>';
		}
		$content .= '
			</div>';
		$content .= '
			<div id="EJ_events_calendar">
				<div id="month_name"><a href="?module=EJ_events&action=event_calendar'.$preview.'&month='.$lastmonth.'">&lt;&lt; '.$lastmonth.'</a> '.$month.' <a href="?module=EJ_events&action=event_calendar'.$preview.'&month='.$nextmonth.'">'.$nextmonth.' &gt;&gt;</a></div>
				<div id="days">
					<div class="day_head">'.$dow[0].'</div><div class="day_head">'.$dow[1].'</div><div class="day_head">'.$dow[2].'</div><div class="day_head">'.$dow[3].'</div><div class="day_head">'.$dow[4].'</div><div class="day_head">'.$dow[5].'</div><div class="day_head">'.$dow[6].'</div>
					<div id="month">';
		for ($i=1; $i<=date('t', strtotime($month)); $i++)
		{
			if ($i==1)
			{
				switch (date('D', strtotime($i.$month)))
				{
					case $dow[0]:
						$extra = 0;
					break;
					case $dow[1]:
						$extra = 1;
					break;
					case $dow[2]:
						$extra = 2;
					break;
					case $dow[3]:
						$extra = 3;
					break;
					case $dow[4]:
						$extra = 4;
					break;
					case $dow[5]:
						$extra = 5;
					break;
					case $dow[6]:
						$extra = 6;
					break;
				}
				if ($extra != 0)
				{
					for ($v = 0; $v<$extra; $v++)
					{
						$content .= '
						<div class="day Xday">'.(date('t', strtotime($lastmonth))-($extra-($v+1))).'</div>';
					}
				}
			}
			if (date('D', strtotime($i.$month))=='Sat' or date('D', strtotime($i.$month))=='Sun')
				$we = " we";
			else
				$we = "";
			if (date('Y-m-d') == date('Y-m-d', strtotime($i." ".$month)))
				$today = " today";
			else
				$today = "";
			$eventtxt = "";
			if (isset($events[$i]))
			{
				foreach ($events[$i] as $event_item)
				{
					$eventtxt .= '<a class="event_item" href="?module=EJ_events&action=show_event'.$preview.'&eventid='.$event_item['EJ_eventId'].'">'.$event_item['EJ_eventTitle'].'</a>';
				}
			}
			$content .= '
						<div class="day'.$we.$today.'">'.$i.'<br/>';
			if (!empty($eventtxt))
			{
				$content .= '<div class="event">'.$eventtxt.'</div>';
			}
			$content .= '</div>';
			if ($i == date('t', strtotime($month)) and date('D', strtotime($i.$month))!=$dow[6])
			{
				switch (date('D', strtotime($i.$month)))
				{
					case $dow[0]:
						$extra = 6;
					break;
					case $dow[1]:
						$extra = 5;
					break;
					case $dow[2]:
						$extra = 4;
					break;
					case $dow[3]:
						$extra = 3;
					break;
					case $dow[4]:
						$extra = 2;
					break;
					case $dow[5]:
						$extra = 1;
					break;
				}
				for ($v = 0; $v<$extra; $v++)
				{
					$content .= '
						<div class="day Xday">'.($v+1).'</div>';
				}
			}
		}
		$content .= '
					</div>
				</div>
		';
		$content .= '
			</div>
			<div style="clear:both; height: 1px;"></div>';
		echo $content;
	}
	
	function show_event()
	{
		$preview = "";
		if ($this->vars['preview']=='true')
		{
			$content .= '
			<div style="text-align: center; margin-top: 3px;"><a href="?module=EJ_events&action=admin_page"><img src="'.$this->moduleloc.'back.png" alt="Back to Events" title="Back to Events" style="border:0;" /></a></div>
			<h2  class="EJE"><img src="'.$this->moduleloc.'calendar.jpg"/> Event Preview</h2>';
			$preview = "&preview=true";
		}
		if (!isset($this->vars['eventid']))
		{
			$content = "<p class=\"EJ_userError\"><strong>ERROR:</strong> Event Not Found! Please go back and try again.</p>";
		} else
		{
			$this->EJ_mysql->query("SELECT *, (SELECT catName FROM {$this->EJ_mysql->prefix}module_EJ_events_cats WHERE catId = EJ_eventCat) as category FROM {$this->EJ_mysql->prefix}module_EJ_events WHERE EJ_eventId = {$this->vars['eventid']}");
			if ($this->EJ_mysql->numRows() == 0)
			{
				$content = "<p class=\"EJ_userError\"><strong>ERROR:</strong> Event Not Found! Please go back and try again.</p>";
			} else
			{
				$event = $this->EJ_mysql->getRow();
				$this->EJ_mysql->query("UPDATE {$this->EJ_mysql->prefix}module_EJ_events SET EJ_eventHits = EJ_eventHits + 1 WHERE EJ_eventId = {$this->vars['eventid']}");
				$event['EJ_eventHits']++;
				$content .= '
				<div id="event_left">';
				if (!empty($event['EJ_eventImage']) and file_exists($_SERVER['DOCUMENT_ROOT'].$this->settings['instloc'].$this->moduleloc.'images/'.$event['EJ_eventImage']))
				{
					$content .= '
					<img src="'.$this->settings['instloc'].$this->moduleloc.'image.php'.$this->settings['instloc'].$this->moduleloc.'images/'.$event['EJ_eventImage'].'?width=250&amp;image='.$this->settings['instloc'].$this->moduleloc.'images/'.$event['EJ_eventImage'].'" alt="'.urlencode($event['EJ_eventTitle']).'" />';
				} else
				{
					$content .= '
					<img src="'.$this->settings['instloc'].$this->moduleloc.'image.php'.$this->settings['instloc'].$this->moduleloc.'images/noimage.png?width=250&amp;image='.$this->settings['instloc'].$this->moduleloc.'images/noimage.png" alt="'.urlencode($event['EJ_eventTitle']).'" />';
				}
				$content .= '<br/>
					<a class="back_calendar" href="?module=EJ_events&action=event_calendar'.$preview.'">&lt;&lt; Back to Calendar</a>';
				$content .= '
				</div>';
				$content .= '
				<div id="event_right">
					<h2  class="EJE"><span style="float:right;">Hits: '.$event['EJ_eventHits'].'</span>'.date('d/m/Y', strtotime($event['EJ_eventDate'])).' - '.$event['EJ_eventTitle'].'</h2>
					<p><strong>Event Category:</strong> '.$event['category'].'</p>
					<p>'.str_replace(array('£','%u2019'), array('&pound;',"'"), $event['EJ_eventText']).'</p>
					<p><strong>Event Starts:</strong> '.$event['EJ_eventTime'].' on '.date('D d M Y', strtotime($event['EJ_eventDate'])).'</p>
					<p><strong>Location:</strong> '.$event['EJ_eventLoc1'];
				if (!empty($event['EJ_eventLoc2']))
				{
					$content .= ', '.$event['EJ_eventLoc2'];
				}
				if (!empty($event['EJ_eventLoc3']))
				{
					$content .= ', '.$event['EJ_eventLoc3'];
				}
				if (!empty($event['EJ_eventLoc4']))
				{
					$content .= ', '.$event['EJ_eventLoc4'];
				}
				if (!empty($event['EJ_eventLoc5']))
				{
					$content .= ', '.$event['EJ_eventLoc5'];
				}
				$content .= '
					<p><strong>For Details Contact:</strong> <a href="mailto:'.$event['EJ_eventContact'].'">'.$event['EJ_eventContact'].'</a></p>';
				$content .='
					</p>';
				$content .= '
				</div>';
			}
		}
		$content .= '<div style="clear: both;"></div>';
		echo $content;
	}
	
	function show_upcoming()
	{
		$this->EJ_mysql->query("SELECT * FROM {$this->EJ_mysql->prefix}module_EJ_events WHERE EJ_eventDate > NOW() and EJ_eventHidden = 0 LIMIT 5");
		if ($this->EJ_mysql->numRows() == 0)
		{
			$content .= "<p>No Upcoming Events</p>";
		}
		else
		{
			while ($event = $this->EJ_mysql->getRow())
			{
				$content .= "
					<div>";
				$content .= '
						<div class="upcoming">
							<p><strong><a href="?module=EJ_events&action=show_event'.$preview.'&eventid='.$event['EJ_eventId'].'">'.$event['EJ_eventTitle'].'</a></strong></p>
							<p>'.date("D d M Y", strtotime($event['EJ_eventDate'])).' at '.$event['EJ_eventTime'].'</p>
							<p>'.substr($event['EJ_eventText'],0,50).'... <a href="?module=EJ_events&action=show_event'.$preview.'&eventid='.$event['EJ_eventId'].'">more</a></p>
						</div>';
				$content .= "
					</div>";
			}
		}
		echo $content;
	}
}
} else
{
	EJ_error(41, basename(__FILE__));
}

?>