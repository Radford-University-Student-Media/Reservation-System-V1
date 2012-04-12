<?php

/*

Radford Reservation System
Author: Andrew Melton
LoC: 5810 (8-19-2011)

Filename: index.php
Purpose:
This is the main "window" page that most everything is shown through.
It determines the page to display through a GET variable "pageid."
A session variable "user_level" is also checked to determine whether
or not the admin navigation links should be displayed. Finally, the
base HTML is displayed. There is an IF statement in the header that
will display the JavaScript code for the popup calendar on the pages
that need it.

Known Bugs/Fixes:

Bug #1: See "Bug 1 Fix"
When using the address without www:
(ie. "ramielrowe.com/res" as opposed to "www.ramielrowe.com/res")
Logging in from non-www address will get redirected to a www addr.
Thus, the session will get started with a different id.

Fix: Just pass the session id after logging in. GET variable named
"sesid"

*/

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

if(!file_exists("config.php") || file_exists("setup.php")){

	die("<html><head><meta http-equiv=\"refresh\" content=\"0;url=setup.php\"></head></html>");

}

/*
 Bug 1 Fix
*/
if(isset($_GET['sesid'])){

	session_id($_GET['sesid']);

}

session_start();

require 'functions.php';

if( getConfigVar("maint_mode") ){


	die("<html><body>System in Maintenance Mode</body></html>");

}

initMySQL();


if( getConfigVar("db_error_logging") ){

	set_error_handler("db_error_logger");

}

$pageid = "home";

if(isset($_GET['pageid'])){

	$pageid = $_GET['pageid'];

}

if($pageid == "logout"){

	session_unset();
	$pageid = "login";

}

$navi = "";
$admin = "";

/*

Check to see if there is a user logged in. If so, check their "user_level."
The standard navigation links will always be displayed, if the user is an
admin the admin links will also be displayed.

*/

if(issetSessionVariable('user_level')){

	$userlevel = getSessionVariable('user_level');
	if($userlevel == RES_USERLEVEL_NOLOGIN){

		$navi = $navi . "<tr><td class=\"navi\">
				<a href=\"./index.php?pageid=logout\" class=\"navi\">Logout</a>
			</td></tr>";

	}
	if($userlevel > RES_USERLEVEL_NOLOGIN){

		$navi = $navi . "<tr><td class=\"navi\">
				<a href=\"./index.php?pageid=home\" class=\"navi\">Home</a> - 
				<a href=\"./index.php?pageid=ourequip\" class=\"navi\">Our Equipment</a> - 
				<a href=\"./index.php?pageid=myaccount\" class=\"navi\">My Account</a> - 
				<a href=\"./index.php?pageid=policies\" class=\"navi\">Our Policies</a> - 
				<a href=\"./index.php?pageid=logout\" class=\"navi\">Logout</a>
			</td></tr>";

	}
	if($userlevel == getConfigVar("moderator_rank")){

		$navi = $navi . "<tr>
			<td class=\"adminnaviouter\">
				<table cellpadding=0 cellspacing=0 border=0 class=\"adminnavi\">
					<tr>
						<td class=\"adminnaviinner\">
							&nbsp;&nbsp<a href=\"./index.php?pageid=browseres\" class=\"navi\">Browse Reservations</a>&nbsp;&nbsp
						</td>
					</tr>
				</table>
			</td>";

	}
	if($userlevel >= getConfigVar("admin_rank")){

		$navi = $navi . "<tr>
		
			<td class=\"adminnaviouter\">
				<table cellpadding=0 cellspacing=0 border=0 class=\"adminnavi\">
					<tr>
						<td class=\"adminnaviinner\">
							&nbsp;&nbsp;<a href=\"./index.php?pageid=manageusers\" class=\"navi\">Users</a> - 
							<a href=\"./index.php?pageid=manageequip\" class=\"navi\">Equipment</a> - 
							<a href=\"./index.php?pageid=browseres\" class=\"navi\">Browse Reservations</a> - 
							<a href=\"./index.php?pageid=makeres\" class=\"navi\">Make Reservation</a> - 
							<a href=\"./index.php?pageid=manageblackouts\" class=\"navi\">Blackouts</a> - 
							<a href=\"./index.php?pageid=messages\" class=\"navi\">Messages</a>&nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</td>
			
		</tr>";

	}

}else{

	$pageid = "login";

}

/*

Just determine which file gets loaded for each pageid.
Home is the default.

*/

if($pageid == "login"){

	$page = "./login.php";

}
else if($pageid == "home"){

	$page = "./home.php";

}
else if($pageid == "ourequip"){

	$page = "./ourequip.php";

}
else if($pageid == "viewreservation"){

	$page = "./viewreservation.php";

}
else if($pageid == "myaccount" || $pageid == "savepassword" || $pageid == "saveemail"){

	$page = "./myaccount.php";

}
else if($pageid == "moreinfo"){

	$page = "./moreinfo.php";

}
else if($pageid == "finishaddequip"){

	$page = "./admin/newequip.php";

}
else if($pageid == "saveequip"){

	$page = "./admin/editequip.php";

}
else if($pageid == "newuser" || $pageid == "finishnewuser"){

	$page = "./newuser.php";

}
else if($pageid == "makeres" || $pageid == "finishmakeres"){

	$page = "./makereservation.php";

}
else if($pageid == "reservation" || $pageid == "finishres"){

	$page = "./reservation.php";

}
else if($pageid == "browseres"){

	$page = "./browsereservations.php";

}
else if($pageid == "editreservation"){

	$page = "./editreservation.php";

}
else if($pageid == "manageusers" || $pageid == "edituser" || $pageid == "adminsavepassword" || $pageid == "adminsaveemail" || $pageid == "adminsavenotes" || $pageid == "adminsavelevel"){

	$page = "./users.php";

}
else if($pageid == "manageequip"){

	$page = "./admin/equipment.php";

}
else if($pageid == "manageblackouts" || $pageid == "newblackout" || $pageid == "createblackout" || $pageid == "editblackout" || $pageid == "saveblackout"){

	$page = "./adminblackout.php";

}
else if($pageid == "messages" || $pageid == "newmessage" || $pageid == "createmessage" || $pageid == "editmessage" || $pageid == "savemessage" || $pageid == "deletemessage"){

	$page = "./admin/messages.php";

}
else if($pageid == "warnuser" || $pageid == "submitwarning" || $pageid == "viewwarnings" || $pageid == "editwarning" || $pageid == "savewarning"){

	$page = "./warn.php";

}
else if($pageid == "viewmywarnings" || $pageid == "viewmywarning"){

	$page = "./mywarnings.php";

}
else if($pageid == "policies"){

	$page = "./policies.php";

}
else{

	$page = "./home.php";
	$pageid = "home";

}

?>

<html>

<head>

<LINK REL=StyleSheet HREF="./style.css" TYPE="text/css">
<title><?php echo getConfigVar("pageheader"); ?></title>




	<?php
	
		/*
		
			These are the pageids for each page that requires a popup calendar.
		
		*/
		if($pageid == "reservation" || $pageid == "finishres" || $pageid == "makeres" || $pageid == "finishmakeres"	|| 
		$pageid == "browseres" || $pageid == "editreservation" || $pageid == "newblackout" || $pageid == "editblackout" || 
		$pageid == "saveblackout" || $pageid == "newmessage" || $pageid == "editmessage"){
		
			echo "<script src=\"./CalendarPopup.js\"></script><script src=\"./AnchorPosition.js\"></script>
			<script src=\"./PopupWindow.js\"></script>
			<script src=\"./date.js\"></script>
			<SCRIPT LANGUAGE=\"JavaScript\">document.write(getCalendarStyles());</SCRIPT>";
		
		}
	
	?>

</head>





<?php

	if($page == "./login.php"){
		
		echo "<body onload=\"document.loginform.id.focus()\">";
	
	}else{
	
		echo "<body>";
	
	}

?>

	<center><img src="./<?php echo getConfigVar("banner"); ?>"></center>
	<table class="main">
		
		<?php echo $navi; ?>
		
		<?php echo $admin; ?>
		
		<tr>
		
			<td class="content">
			
				
				<?php require $page; ?>
			
			<br></td>
		
		</tr>
	
	</table>

</body>

</html>


<?php closeMySQL(); ?>
