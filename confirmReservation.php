<?php
/*

Radford Reservation System
Author: Andrew Melton

Filename: confirmReservation.php

Purpose:
This page is sent to each admin when a new reservation needs confirmation.
It will check to make sure the current user is logged in as an admin,
if not it will redirect to a login page. If the user is an admin it will
display information on the reservation provided by the GET or POST
variable 'resid'. POST is only used if the user had to log in. The admin
user is able to provide a comment in the admin comment field and to
confirm or deny the reservation. Upon confirmation, an email is sent to
the user who created the reservation.

Known Bugs/Fixes:

Bug #1:
Reservation with 'checked-in' status will display as denied:
This is just because of the if statement selecting what to display.
All that needs to be done is a case added in for 'checked-in'

Fix: Added in if statement

Bug #2:
When trying to view a reservation that was deleted, it will show up
as a blank reservation.

Fix: Unfixed.

Todo:

#1
This cluster-fuck of code could probably use a little cleaning and
reorganization...

*/

session_start();

//error_reporting(E_ALL);
//ini_set('display_errors', '2');

require './lib/constants.php';
require 'functions.php';

$resid = 0;
$page =	 "";
$errormessage = "";
$message = "";

if(isset($_POST['page']) && $_POST['page'] == "login"){

	$id = $_POST['id'];
	$password = $_POST['pass'];
	$resid = $_POST['resid'];

	$userq = processLogin($id, $password);

	$error = 0;
	
	if(getConfigVar("use_ldap")){
		if(mysql_num_rows($userq)==0){
			
			$error = RES_ERROR_LOGIN_USER_PASS;
			$errormessage = "Incorrect user name or password<br><br>";
			
		}
	}else{
		if(mysql_num_rows($userq)==0){
	
			$error = RES_ERROR_LOGIN_NO_USER;
			$errormessage = "No such user id<br><br>";
	
		}else{
	
			$row = mysql_fetch_assoc($userq);
	
			if($row['password'] != encrypt($password)){
	
				$error = RES_ERROR_LOGIN_USER_PASS;
				$errormessage = "Incorrect user name or password<br><br>";
	
			}
	
		}
	}

	if($error == 0){

		$user = mysql_fetch_assoc($userq);

		setSessionVariable('user_id', $user['user_id']);

		setSessionVariable('user_level', $user['user_level']);

	}

}
else if(isset($_POST['page']) && $_POST['page'] == "confirm"){

	$resid = $_POST['resid'];
	$admincomment = $_POST['admincomment'];
	$status = $_POST['status'];

	confirmReservation($resid, $admincomment, $status);

	$message = "Reservation Updated!";

}

if((isset($_GET['resid']) || isset($_POST['resid'])) && issetSessionVariable('user_level')){

	if(isset($_GET['resid']))
	$resid = $_GET['resid'];
	else if(isset($_POST['resid']))
	$resid = $_POST['resid'];

	if(getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN){

		$resresult = getReservationByID($resid);

		if(mysql_num_rows($resresult) > 0){

			$reservation = mysql_fetch_assoc($resresult);
			$user = mysql_fetch_assoc(getUserByID($reservation['user_id']));
			$equipment = mysql_fetch_assoc(getEquipmentByID($reservation['equip_id']));
				
			$accept = "";
			$deny = "";
				
			if($reservation['mod_status'] == RES_STATUS_CONFIRMED)
			$accept = "selected";
			else if($reservation['mod_status'] == RES_STATUS_DENIED)
			$deny = "selected";

			$status = "";

			if($reservation['mod_status'] == RES_STATUS_CONFIRMED)
			$status = "<font color=\"#005500\">Current Status: Confirmed</font>";
			else if($reservation['mod_status'] == RES_STATUS_PENDING)
			$status = "Current Status: Pending";
			else if($reservation['mod_status'] == RES_STATUS_CHECKED_OUT)
			$status = "<font color=\"#005500\">Current Status: Checked-Out</font>";
			else if($reservation['mod_status'] == RES_STATUS_CHECKED_IN)
			$status = "<font color=\"#005500\">Current Status: Checked-In</font>";
			else
			$status = "<font color=\"#FF0000\">Current Status: Denied</font>";

			$page = $page . "
			<center><h3>Reservation Confirmation/Update Form</h3>".$message."<br><br></center>
			<form action=\"./confirmReservation.php\" method=\"POST\"><table class=\"confirmreservation\">
			
				<tr>
				
					<td colspan=4 class=\"header\">User Information</td>
				
				</tr>
				<tr>
				
					<td colspan=2 class=\"centeredcellbold\">Name</td>
					<td colspan=2 class=\"centeredcell\"><a href=\"./userinfo.php?user_id=".$user['user_id']."\" target=\"_BLANK\">".$user['name']."</a></td>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"header\">Equipment Information</td>
				
				</tr>
				<tr>
				
					<td colspan=2 class=\"centeredcellbold\">Name</td>
					<td colspan=2 class=\"centeredcell\"><b>".$equipment['name']."</b></td>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"header\">Reseravtion Information</td>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"centeredcellbold\">".$status."</td>
				
				</tr>
				<tr>
				
					<td class=\"centeredcellbold\">Start Date</td>
					<td class=\"centeredcell\">".$reservation['start_date']."</td>
					<td class=\"centeredcellbold\">End Date</td>
					<td class=\"centeredcell\">".$reservation['end_date']."</td>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"centeredcellbold\">User Comment</td>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"topaligncell\">&nbsp;".$reservation['user_comment']."</td>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"centeredcellbold\">Admin Comment</th>
				
				</tr>
				<tr>
				
					<td colspan=4 class=\"centeredcell\"><textarea rows=5 cols=50 name=\"admincomment\">".$reservation['admin_comment']."</textarea></td>
				
				</tr>
				<tr>
				
					<td colspan=2 class=\"centeredcell\"><select name=\"status\"><option value=\"1\" ".$accept.">Approve</option><option value=\"2\" ".$deny.">Deny</option></select></td>
					<td colspan=2 class=\"centeredcell\"><input type=\"submit\" value=\"Update!\"><input type=\"hidden\" name=\"resid\" value=\"".$resid."\"><input type=\"hidden\" name=\"page\" value=\"confirm\"></td>
				
				</tr>
			
			</table></form>";

		}
		else{

			$page = $page . "<center><h3><font color=\"#FF0000\">Error: Couldn't find reservation. (This is probably because it was deleted)</font></h3></center>";

		}

	}else{

		$page = $page . "<center><h3><font color=\"#FF0000\">Error: You aren't authorized to view this page!</font></h3></center>";

	}

}
else if(isset($_POST['page']) && isset($_POST['resid'])){

	$page = $page . "<center><h3>Reservation Has Been Updated!</h3></center>";

}
else if(!isset($_POST['resid']) && !isset($_GET['resid'])){



}
else{

	if(isset($_GET['resid']))
	$resid = $_GET['resid'];
	else if(isset($_POST['resid']))
	$resid = $_POST['resid'];

	$page = $page . "<center><h3>You need to be logged in to view this page.</h3>
		<font color=\"#FF0000\">".$errormessage."</font></center>
		<form action=\"./confirmReservation.php\" method=\"POST\">
			<input type=\"hidden\" name=\"resid\" value=\"".$resid."\"><input type=\"hidden\" name=\"page\" value=\"login\">
			<table class=\"login\">
				<tr>
					<td colspan=2 class=\"header\">User Login</td>
				</tr>
				<tr>
					<td class=\"centeredcellbold\">Username</td>
					<td class=\"centeredcell\"><input type=\"text\" name=\"id\"></td>
				</tr>
				<tr>
					<td class=\"centeredcellbold\">Password</td>
					<td class=\"centeredcell\"><input type=\"password\" name=\"pass\"></td>
				</tr>
				<tr>
					<td colspan=2 class=\"centeredcellbold\"><input type=\"submit\" value=\"Login\"></td>
				</tr>
			</table>
		</form>";

}

?>

<html>

<head>

<LINK REL=StyleSheet HREF="./style.css" TYPE="text/css">

<title><?php if(issetSessionVariable('user_level') && getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN) echo "Reservation Confirmation Page"; ?>
</title>

</head>

<body>



<?php echo $page; ?>

</body>

</html>
