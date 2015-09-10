<?PHP

include('../config.php');
session_start();
if(isset($_POST)){
	//print_r($_POST);

if($_POST["name"]=="admin" && $_POST["password"]=="admin@123"){

	if (!isset($_SESSION['enterkey'])){
    	$_SESSION['enterkey'] = 'sessionenter'; //assign the timestamp to the session variable
	}

}
}

if(!isset($_SESSION['enterkey'])){
	echo "not login";
	exit;
}


# DATABASE CONFIGURATION
$global['dbhost'] = $servername;
$global['dbname'] = $dbname;
$global['dbuser'] = $username;
$global['dbpass'] = $dbpassword;



# COLORS CONFIGURATION
$global['header_color'] = "ffffff"; # header color
$global['header_selected_color'] = "eeeeee"; # selected header color
$global['line_color'] = "dddddd";
$global['row_color1'] = "ffffff"; # row color
$global['row_color2'] = "f7f7f7"; # row color
$global['row_color_selected'] = "FBFBC4"; # row color
$global['row_color_mouseover'] = "F5F5BA";
$global['page_box_color'] = "f7f7f7";
$global['page_box_border'] = "000080";


# IMAGES
$global['url_images'] = "images";


# LOAD DATA ENGINE

require "data_engine.php";
$output = data_engine_select("applicant", "id|txnid|username|applicant_name|gname|presentadd|presentstate|permanentadd|permanentstate|postapplying|imagelocation|email|mobile|dob|gender|cast|nationality|reg_date", "1", "10|1", "", "2|2", "", "", "", "");

# Must be loaded before any print so it can print and exit for AJAX functions (to avoid headers printing)



# HEADER
print <<<END
<html>
<head>
<style type="text/css">

.header					{ font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 18px; color : #000000; font-weight:bold; }
a.header				{ color: #0000aa; text-decoration: none; }
a.header:hover			{ color: #0000aa; text-decoration: underline; }

.sub_header				{ font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 13px; color : #000000; }
a.sub_header			{ color: #0000aa; text-decoration: none; }
a.sub_header:hover		{ color: #0000aa; text-decoration: underline; }

.search					{ font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 12px; color : #000000; }
a.search				{ color: #0000aa; text-decoration: none; }
a.search:hover			{ color: #0000aa; text-decoration: underline; }

.field					{ font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 13px; color : #000000; }
a.field					{ color: #0000aa; text-decoration: none; }
a.field:hover			{ color: #0000aa; text-decoration: underline; }

.small					{ font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 10px; color : #000000; }
a.small					{ color: #0000aa; text-decoration: none; }
a.small:hover			{ color: #0000aa; text-decoration: underline; }

.small_white			{ font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 10px; color : #ffffff; }
a.small_white			{ color: #ffffff; text-decoration: none; }
a.small_white:hover		{ color: #ffffff; text-decoration: underline; }

input 		{ color: #000000; background: #f7f7f7; border: 1px solid #000080 }
textarea	{ color: #000000; background: #f7f7f7; border: 1px solid #000080 }
select		{ color: #000000; background: #f7f7f7; border: 1px solid #000080 }
.submit 	{ color: #000000; background: #f7f7f7; border: 1px solid #000080 }

.showimg	{ height: 30px; }

</style>

<script type="text/javascript" src="ajax_queue.js"></script>

<script type="text/javascript">
function SimpleAJAXCallback(in_text, obj) {
	document.getElementById(obj).innerHTML = in_text;
	setStatus ("","showimg");
	setStatus ("","showimg2");
}

function checkAll(checkname, exby) {
var bgcolor = '$global[row_colora1]';
  for (i = 0; i < checkname.length; i++) {
  checkname[i].checked = exby.checked? true:false
  var cell = document.getElementById('row' + i);
	if (bgcolor == '$global[row_color2]') {
		var bgcolor = '$global[row_color1]';
	} else {
		var bgcolor = '$global[row_color2]';
	}
	if (checkname[i].checked) {
		cell.style.background = '#$global[row_color_selected]';
	} else {
		cell.style.background = '#' + bgcolor;
	}
  }
}

function checktoggle(box,theId,color) {
if(document.getElementById) {
  var cell = document.getElementById(theId);
  var box = document.getElementById(box);
  if(box.checked) {
    cell.style.background = '#$global[row_color_selected]';
  } else {
  cell.style.background = '#' + color;
  }
}
}

function checktoggle_over(box,theId,color) {
if(document.getElementById) {
  var cell = document.getElementById(theId);
  var box = document.getElementById(box);
  cell.style.background = '#' + color;
}
}

//Function to set a loading status.
function setStatus (theStatus, theObj){
	obj = document.getElementById(theObj);

	if (obj) {

	if (theStatus == 1){
		obj.innerHTML = "<img src=\"$global[url_images]/loading.gif\" alt=\"Loading\" vspace=4 hspace=4>";
	} else {
		obj.innerHTML = "";
	}

	}
}


function doneloading(theframe,thefile){
	var theloc = ""
	theframe.processajax ("showimg",theloc);
}

var qsParm = new Array();

function qs(serverPage) {

	var query = serverPage;
	var parms = query.split('&');

	for (var i=0; i<parms.length; i++) {

		var pos = parms[i].indexOf('=');

		if (pos > 0) {

			var key = parms[i].substring(0,pos);
			var val = parms[i].substring(pos+1);
			qsParm[key] = val;

		}
	}
}
</script>
</head><body>
END;




# PRINT DATA ENGINE

print $output;



# FOOTER
print<<<END
</body></html>
END;


?>