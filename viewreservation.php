<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: viewreservation.php

Associated PageIDs:
"viewreservation"

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

	if(isset($_POST['action'])){

		$action = $_POST['action'];

		if($action == "checkout"){

			checkOutReservation($resid);

		}
		else if($action == "checkin"){

			checkInReservation($resid);

		}
		else if($action == "delete"){

			deleteReservation($resid);

		}
		else if($action == "update"){

			updateReservationStatus($resid, $_POST['status']);

		}

	}
}

/*

Get the information of this reseravtion, the user, and the equipment involved.

*/

$page = "";

if( ( isset($_POST['action']) && $_POST['action'] != "delete" ) || !isset($_POST['action']) ){

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

	$userinfo = "
		<tr>
			
			<td class=\"centeredcellbold\">Name</th>
			<td class=\"centeredcell\">".$user['name']."</td>
			<td class=\"centeredcellbold\">Warnings</th>
			<td class=\"centeredcell\">".mysql_num_rows(getActiveWarningsForUser($user['user_id']))."(".mysql_num_rows(getWarningsForUser($user['user_id'])).")</td>
			
		</tr>";

	$checkinCell = "&nbsp;-&nbsp;";

	if($reservation['mod_status'] == RES_STATUS_CONFIRMED){

		$checkinCell = "<input type=\"hidden\" value=\"checkout\" name=\"action\">
				<input type=\"hidden\" value=\"".$resid."\" name=\"resid\">
				<input type=\"submit\" value=\"Check Out\">";

	}else if($reservation['mod_status'] == RES_STATUS_CHECKED_OUT){

		$checkinCell = "<input type=\"hidden\" value=\"checkin\" name=\"action\">
				<input type=\"hidden\" value=\"".$resid."\" name=\"resid\">
				<input type=\"submit\" value=\"Check In\">";

	}

	/*
	 If the logged in user is an admin, display the "check-in button"
	*/

	if(issetSessionVariable('user_level') && getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN){

		$userinfo = "<tr>
			
			<td class=\"centeredcellbold\">Name</th>
			<td class=\"centeredcell\"><a href=\"./index.php?pageid=edituser&user=".$user['user_id']."\">".$user['name']."</a></td>
			<td class=\"centeredcellbold\">Warnings</th>
			<td class=\"centeredcell\"><a href=\"./index.php?pageid=viewwarnings&user_id=".$user['user_id']."\">".mysql_num_rows(getActiveWarningsForUser($user['user_id']))."(".mysql_num_rows(getWarningsForUser($user['user_id'])).")</a></td>
			
		</tr>";

		$checkin = "<tr>
					
					<form action=\"./index.php?pageid=viewreservation\" method=\"POST\">
					<td class=\"centeredcellbold\">
					".$checkinCell."
					</td>
					</form>
					<form action=\"./index.php?pageid=viewreservation\" method=\"POST\" onSubmit=\"return confirm('Are you sure you want to delete this reservation?')\">
					<td class=\"centeredcellbold\">
						<input type=\"hidden\" value=\"delete\" name=\"action\">
						<input type=\"hidden\" value=\"".$resid."\" name=\"resid\">
						<input type=\"submit\" value=\"Delete\">
					</td>
					</form>
					<form action=\"./index.php?pageid=viewreservation\" method=\"POST\">
					<td class=\"centeredcellbold\">
						<input type=\"hidden\" value=\"update\" name=\"action\">
						<input type=\"hidden\" value=\"".$resid."\" name=\"resid\">
						<select name=\"status\">
							<option value=1>Approve</option>
							<option value=2>Deny</option>
						</select>
					</td>
					<td class=\"centeredcellbold\">
						<input type=\"hidden\" value=\"update\" name=\"action\">
						<input type=\"hidden\" value=\"".$resid."\" name=\"resid\">
						<input type=\"submit\" value=\"Update\">
					</td>
					</form>
					
				</tr>";

	}
	else if(issetSessionVariable('user_level') && getSessionVariable('user_level') == RES_USERLEVEL_LEADER){

		if($checkinCell == "&nbsp;-&nbsp;"){

			$checkinCell = "No Available Action (Reservation Pending, Denied, or Checked-in)";

		}

		$checkin = "<tr><form action=\"./index.php?pageid=viewreservation\" method=\"POST\"><td class=\"centeredcellbold\" colspan=4>".$checkinCell."</td></form></tr>";

	}

	$page = $page . "
	<center><h3>Reseravation Info</h3></center>
	<table class=\"viewreservation\">
		<tr>
			
			<td colspan=4 class=\"header\">User Information</td>
			
		</tr>
		".$userinfo."
		<tr>
			
			<td colspan=4 class=\"header\">Equipment Information</td>
			
		</tr>
		<tr>
			
			<td colspan=2 class=\"centeredcellbold\">Name</th>
			<td colspan=2 class=\"centeredcell\"><a href=\"./index.php?pageid=moreinfo&equipid=".$equipment['equip_id']."\">".$equipment['name']."</a></td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"header\">Reservation Information</td>
		
		</tr>
		<tr>
		
			<td class=\"centeredcellbold\">Start Date</td>
			<td class=\"centeredcell\">".$reservation['start_date']."</td>
			<td class=\"centeredcellbold\">End Date</td>
			<td class=\"centeredcell\">".$reservation['end_date']."</td>
			
		</tr>
		<tr>
			<td colspan=4 class=\"centeredcellbold\">".$status."</td>
		</tr>
		<tr>
			
			<td colspan=4 class=\"centeredcellbold\">User Comment</td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"topaligncell\">&nbsp;".$reservation['user_comment']."</td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"centeredcellbold\">Admin Comment</td>
			
		</tr>
		<tr>
			
			<td colspan=4 class=\"topaligncell\">&nbsp;".$reservation['admin_comment']."</td>
			
		</tr>
		".$checkin."
	
	</table>

";

}
else if(isset($_POST['action']) && $_POST['action'] == "delete"){

	$page = $page . "<br><h3>Reservation Deleted</h3>";

}


echo $page;

?>
