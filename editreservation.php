<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: editreservation.php

Associated PageIDs:
"editreseravtion"

Purpose:
This page displays information of a specific reservation.
It determines which reservation to display through either a GET or POST
variable "resid".

Known Bugs/Fixes:

None

*/

if(isset($_GET['resid'])){
	$resid = $_GET['resid'];
}
else if(isset($_POST['resid'])){
	$resid = $_POST['resid'];
	updateReservation($resid, $_POST['startdate'], $_POST['enddate']);
}

/*

Get the information of this reseravtion, the user, and the equipment involved.

*/

$reservation = mysql_fetch_assoc(getReservationByID($resid));
$user = mysql_fetch_assoc(getUserByID($reservation['user_id']));
$equipment = mysql_fetch_assoc(getEquipmentByID($reservation['equip_id']));


if($reservation['mod_status'] == RES_STATUS_CONFIRMED)
$status = "<font color=\"#005500\">Current Status: Confirmed</font>";
else if($reservation['mod_status'] == RES_STATUS_CHECKED_OUT)
$status = "<font color=\"#005500\">Current Status: Checked-Out</font>";
else if($reservation['mod_status'] == RES_STATUS_CHECKED_IN)
$status = "<font color=\"#005500\">Current Status: Checked-In</font>";
else if($reservation['mod_status'] == RES_STATUS_PENDING)
$status = "Current Status: Pending";
else
$status = "<font color=\"#FF0000\">Current Status: Denied</font>";

$checkin = "";

/*
 If the logged in user is an admin, display the "check-in button"
*/

if(issetSessionVariable('user_level') && getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN){

	$checkin = "<tr><form action=\"./index.php?pageid=viewreservation\" method=\"POST\"><th colspan=4><input type=\"hidden\" value=\"".$resid."\" name=\"resid\"><input type=\"submit\" value=\"Checkin\"></th></form></tr>";

}

$page = "";

$page = $page . "
	<center><h3>Reseravation Info</h3></center>
	<form action=\"./index.php?pageid=editreservation\" method=\"POST\">
	<input type=\"hidden\" name=\"resid\" value=\"".$resid."\">
	<table class=\"editreservation\">
		<tr>
			
			<td colspan=4 class=\"header\">User Information</td>
			
		</tr>
		<tr>
			
			<td class=\"centeredcellbold\">Name</th>
			<td class=\"centeredcell\"><a href=\"./index.php?pageid=edituser&user=".$user['user_id']."\">".$user['name']."</a></td>
			<td class=\"centeredcellbold\">Warnings</th>
			<td class=\"centeredcell\"><a href=\"./index.php?pageid=viewwarnings&user_id=".$user['user_id']."\">".mysql_num_rows(getActiveWarningsForUser($user['user_id']))."(".mysql_num_rows(getWarningsForUser($user['user_id'])).")</a></td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"header\">Equipment Information</td>
			
		</tr>
		<tr>
			
			<td colspan=2 class=\"centeredcellbold\">Name</td>
			<td colspan=2 class=\"centeredcell\">".$equipment['name']."</td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"header\">Reservation Information</td>
		
		</tr>
		<tr>
		
			<td class=\"centeredcellbold\">Start Date</td>
			<td class=\"centeredcell\"><script language=\"JavaScript\" id=\"jscal1x\">
						var cal1x = new CalendarPopup(\"testdiv1\");
					</script>
					<input type=\"text\" name=\"startdate\" size=\"20\" id=\"startdate\" value=\"".$reservation['start_date']."\" onClick=\"cal1x.select(document.forms[0].startdate,'anchor1x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor1x\" id=\"anchor1x\">a</a></th>
			<td class=\"centeredcellbold\">End Date</td>
			<td class=\"centeredcell\"><script language=\"JavaScript\" id=\"jscal1x\">
						var cal1x = new CalendarPopup(\"testdiv1\");
					</script>
					<input type=\"text\" name=\"enddate\" size=\"20\" id=\"enddate\" value=\"".$reservation['end_date']."\" onClick=\"cal1x.select(document.forms[0].enddate,'anchor2x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor2x\" id=\"anchor2x\">a</a></th>
			
		</tr>
		<tr>
			<td colspan=4 class=\"centeredcellbold\">".$status."</td>
		</tr>
		<tr>
			
			<td colspan=4 class=\"centeredcellbold\">User Comment</td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"centeredcell\">&nbsp;".$reservation['user_comment']."</td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"centeredcellbold\">Admin Comment</th>
			
		</tr>
		<tr>
		
			<td colspan=4 class=\"centeredcell\">&nbsp;".$reservation['admin_comment']."</td>
			
		</tr>
		<tr>
			<td class=\"centeredcell\" colspan=4><input type=\"submit\" value=\"Save\"></td>
		</tr>
	
	</table></form>
<DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;\"></DIV>
";

echo $page;

?>
