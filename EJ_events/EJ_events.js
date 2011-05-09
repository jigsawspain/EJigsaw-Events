/*
*** EJigsaw Events Admin
**
*** By Jigsaw Spain - www.jigsawspain.com
**
*** JS/Ajax Functions - File Build 0.1
*/

/* Setup AJAX Functionality */
function ajaxRequest(){
	var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"]
	if (window.ActiveXObject){
		for (var i=0; i<activexmodes.length; i++){
			try{
				return new ActiveXObject(activexmodes[i])
			}
			catch(e){
			}
		}
	}
	else if (window.XMLHttpRequest)
		return new XMLHttpRequest()
	else
		return false
}


/**************************
** AJAX Filter Functions **
**************************/


function updateFilter(key)
{
	var form = document.getElementById('search_form');
	if (form.anydate.checked != 1)
	{
		var date = form.date.value;
	} else
	{
		var date = 0;
	}
	var text = form.search_text.value;
	var cat = form.category.value;
	var poster = form.poster.value;
	var limit = form.limit.value;
	if (form.hidden.checked != 1)
	{
		var hidden = 1;
	} else
	{
		var hidden = 0;
	}
	var ajax = ajaxRequest();
	ajax.onreadystatechange = function()
	{
		if (ajax.readyState==4 && ajax.status==200)
		{
			var response = ajax.responseText.split(":::");
			document.getElementById("result_count").innerHTML = response[1];
			document.getElementById("search_results").innerHTML = response[0];
		}
	}
	ajax.open("POST","modules/EJ_events/searchfilter.php",true);
	ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.send("date="+date+"&text="+text+"&cat="+cat+"&poster="+poster+"&hidden="+hidden+"&limit="+limit+"&key="+key);
}

function uncheckAny()
{
	var form = document.getElementById('search_form');
	form.anydate.checked = false;
}


/**********************
** General Functions **
***********************/


// Empty checker
function empty(mixed_var)
{
    var key;
    if (mixed_var === "" || mixed_var === 0 || mixed_var === "0" || mixed_var === null || mixed_var === false || typeof mixed_var === 'undefined') {
        return true;
    }
    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }
    return false;
}

// DIV slider
var sliderIntervalId = 0;
var sliding = false;
var slideSpeed = 10;

function Slide(obj, start, finish)
{
	slideStart = start;
	slideStop = finish;
   if(sliding)
      return;
   sliding = true;
   if(parseInt(obj.style.height) == slideStop)
	{
      sliderIntervalId = setInterval('SlideUpRun(\''+obj.id+'\')', 30);
	}
   else
	{
		obj.style.height = slideStart + 'px';
      sliderIntervalId = setInterval('SlideDownRun(\''+obj.id+'\')', 30);
	}
}

function SlideUpRun(id)
{
   slider = document.getElementById(id);
   if(parseInt(slider.style.height) <= slideStart)
   {
      sliding = false;
      slider.style.height = slideStart + 'px';
      clearInterval(sliderIntervalId);
   }
   else
   {
      height = parseInt(slider.style.height);
		height -= slideSpeed;
		slider.style.height = height + 'px';
      if(parseInt(slider.style.height) < slideStart)
         slider.style.height = slideStart + 'px';
   }
}

function SlideDownRun(id)
{
	slider = document.getElementById(id);
   if(parseInt(slider.style.height) >= slideStop)
   {
      sliding = false;
      slider.style.height = slideStop + 'px';
      clearInterval(sliderIntervalId);
   }
   else
   {
		height = parseInt(slider.style.height);
		height += slideSpeed;
		slider.style.height = height + 'px';
      if(parseInt(slider.style.height) > slideStop)
         slider.style.height = slideStop + 'px';
   }
}

// Save Event
function saveEvent(key, id)
{
	document.getElementById('event_message').innerHTML = '';
	message = "";
	form = document.add_form;
	if (empty(form.title.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Title' cannot be empty!</p>";
	}
	if (form.cat.value == 'NONE')
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select 'Category'!</p>";
	}
	if (form.poster.value == 'NONE')
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Please select 'Posted By'!</p>";
	}
	if (empty(form.time.value) || form.time.value.substr(2,1)!=":")
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">Format for 'Time' is incorrect!</p>";
	}
	if (empty(form.location1.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Location' line 1 cannot be empty!</p>";
	}
	if (empty(form.contact.value))
	{
		message = message + "<p class=\"EJ_user_error\" style=\"text-align: left;\">'Contact Email' cannot be empty!</p>";
	}
	if (message !="")
	{
		document.getElementById('event_message').innerHTML = message;
	} else
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="OK") {
					form.title.value = "";
					form.desc.value = "";
					form.cat.options[0].selected = true;
					form.poster.options[0].selected = true;
					form.hidden.options[0].selected = true;
					form.time.value = "";
					form.location1.value = "";
					form.location2.value = "";
					form.location3.value = "";
					form.location4.value = "";
					form.location5.value = "";
					form.contact.value = "";
					form.image.value = "";
					document.getElementById('eventimage').src = 'modules/EJ_events/images/noimage.png';
					document.location="?module=EJ_events&action=search";
				} else {
					document.getElementById('event_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_events/saveevent.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		title = form.title.value;
		desc = form.desc.value;
		cat = form.cat.value;
		date = form.date.value;
		poster = form.poster.value;
		hidden = form.hidden.value;
		time = form.time.value;
		location1 = form.location1.value;
		location2 = form.location2.value;
		location3 = form.location3.value;
		location4 = form.location4.value;
		location5 = form.location5.value;
		contact = form.contact.value;
		image = form.image.value;
		if (id)
		{
			sendid = "&id="+id;
		} else
		{
			sendid = "";
		}
		ajax.send("title="+escape(title)+"&desc="+escape(desc)+"&cat="+cat+"&date="+date+"&poster="+poster+"&time="+time+"&location1="+location1+"&location2="+location2+"&location3="+location3+"&location4="+location4+"&location5="+location5+"&contact="+contact+"&hidden="+hidden+"&image="+image+sendid+"&key="+key);
	}
}

function clear_message(id)
{
	document.getElementById(id).innerHTML = "";
}

// Delete Event
function deleteEvent(eventid,key)
{
	if (confirm('Are you sure you want to delete this event?'))
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					updateFilter(key);
					document.getElementById('event_message').innerHTML='<p style="font-weight: bold; color: #090;">Event Deleted Successfully!</p>';
					document.getElementById('event_message').style.height = '20px';
					setTimeout("Slide(document.getElementById('event_message'), 0, 20)", 4000);
					setTimeout("clear_message('event_message')", 3990);
				} else {
					document.getElementById('event_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_events/deleteevent.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("id="+eventid+"&key="+key);
	}
}

// Add Category
function addCat(key)
{
	document.getElementById('new_cat_message').innerHTML="";
	message="";
	form = document.getElementById('new_cat_form');
	if (empty(form.new_name.value))
	{
		message = message + "<p class=\"EJ_user_error\">'Category Name' cannot be empty!</p>";
	}
	if (message=="")
	{
		catname = form.new_name.value;
		subcatof = form.new_sub.value;
		catdesc = form.new_desc.value;
		if (form.catid.value != "")
			catid = "&id="+form.catid.value;
		else
			catid = "";
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_events&action=cats';
				} else {
					document.getElementById('new_cat_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_events/addcat.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("catName="+escape(catname)+"&subCatOf="+subcatof+"&catDesc="+escape(catdesc)+catid+"&key="+key);
	} else
	{
		document.getElementById('new_cat_message').innerHTML=message;
	}
}

// Delete Category
function deleteCat(catid,key)
{
	if (confirm('Are you sure you want to delete this category?'))
	{
		ajax = ajaxRequest();
		ajax.onreadystatechange = function() {
			if (ajax.readyState==4 && ajax.status==200) {
				text=ajax.responseText;
				if (text=="") {
					document.location = '?module=EJ_events&action=cats';
				} else {
					document.getElementById('event_message').innerHTML=text;
				}
			}
		}
		ajax.open("POST","modules/EJ_events/deletecat.php",true);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		ajax.send("id="+catid+"&key="+key);
	}
}

// Image Picker
function changepic() {
    if ( !document.getElementById("picoverlay") ) {
        pic = document.createElement("div");
        pic.style.cssText = "position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); /*filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#CC000000, endColorstr=#CC000000); -ms-filter: \"progid:DXImageTransform.Microsoft.gradient(startColorstr=#CC000000, endColorstr=#CC000000)\";*/";
        pic.setAttribute('id',"picoverlay");
        pic.onclick = destroy;
        document.body.appendChild(pic);
        picframe = document.createElement("div");
        picframe.style.cssText = "margin: 200px auto 0 auto; height: 300px; width: 500px; background-color: #69C; border: #69C 2px solid;";
        picframe.setAttribute('id',"picinternal");
        picframe.innerHTML = "<div style=\"text-align:right; height: 15px; cursor:pointer;\">CLOSE</div><iframe src =\"modules/EJ_events/changepic.php\" style=\"border:0; height: 285px; width: 500px;\"><p>Your browser does not support iframes.</p></iframe>";
        document.getElementById("picoverlay").appendChild(picframe);
    }
}

function destroy() {
		destroydiv = document.getElementById("picoverlay");
		document.body.removeChild(destroydiv);
}

function sendimage() {
    document.getElementById('message').innerHTML="";
    if(document.getElementById('imagefind').value!=""){
        ajax = ajaxRequest();
        ajax.onreadystatechange = function() {
            if (ajax.readyState==4 && ajax.status==200) {
                text=ajax.responseText;
                if (text=="OK") {
                    document.imageform.submit();
                } else {
                    if (confirm(document.getElementById('imagefind').value.substr(12)+" already exists! Do you want to replace it?")){
                        document.imageform.submit();
                    } else {
                        document.getElementById('message').innerHTML=text;
                    }
                }
            }
        }
        ajax.open("POST","checkimage.php",true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        filename = document.getElementById('imagefind').value;
        ajax.send("img="+filename);
    } else {
        document.getElementById('message').innerHTML="You must select a file to upload!";
    }
}

function updateimage(img) {
	parent.document.getElementById('eventimage').src = "modules/EJ_events/images/"+img;
	if (img != 'noimage.png')
	{
		parent.document.getElementById('image').value = img;
	} else
	{
		parent.document.getElementById('image').value = '';
	}
}

function selectImage(img)
{
	document.imageform.imagefind.value = img;
	document.imageform.submit();
}