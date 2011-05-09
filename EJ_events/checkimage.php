<?php
	$split = explode("\\",$_POST['img']);
	foreach($split as $filename){}
	if (file_exists("images/".$filename)) {
		print("An image called ".$filename." already exists!");
	} else {
		print("OK");
	}
?>