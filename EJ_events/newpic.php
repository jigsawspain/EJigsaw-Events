<?php
if (!isset($_FILES['imagefind']) or empty($_FILES['imagefind']['name'])) {
	if (isset($_POST['save'])) {
		$message="ERROR: Upload error or no picture selected.";
	}
?>
<script src="EJ_events.js" language="javascript" type="text/javascript"></script>
<form name="imageform" id="imageform" method="post" enctype="multipart/form-data" action="newpic.php" style="font-size:12px;">
	<p><strong>Select a picture  to add</strong><br/>
	<div id="message" style="margin:0; padding:0;"><?=$message?></div>
		<input type="file" name="imagefind" id="imagefind"/>
		<br/>
		<input type="button" name="save" id="save" value="Add Picture" style="width:100%;" onclick="sendimage()"/>
	</p>
</form>
<?php
} else {
	require('simpleimage.inc');
	$target_path = "images/";
	$target_path = $target_path . basename( $_FILES['imagefind']['name']); 
	if(move_uploaded_file($_FILES['imagefind']['tmp_name'], $target_path)) {
		print("<script src=\"EJ_events.js\" language=\"javascript\" type=\"text/javascript\" onload=\"updateimage('".$_FILES['imagefind']['name']."')\"></script>");
		$image = new SimpleImage();
		$image->load($target_path);
		$width = $image->getWidth();
		if ($width>250) {
			$image->resizeToWidth(250);
			unlink($target_path);
			$image->save($target_path);
		}
		echo basename( $_FILES['imagefind']['name']) . " has been uploaded!";
	} else{
		echo "There was an error uploading the file, please <a href=\"newpic.php\">try again<//a>!";
	}
}
?>