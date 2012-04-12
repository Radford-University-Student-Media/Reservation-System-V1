<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: home.php

Associated PageIDs:
"home" and none/default

Purpose:
This is the first page shown to a logged in user. It displays the current
pickup/drop off schedule along with the users recent reservations.

Known Bugs/Fixes:

None

*/

$messages = "";

$mesResult = getCurrentMessages();

if(mysql_num_rows($mesResult) > 0){

	$messages = "<h3>System Messages</h3>";

	while($row = mysql_fetch_assoc($mesResult)){

		$messages = $messages . "<div class=\"messageoutter\"><div class=\"priority".$row['priority']."message\">" . $row['body'] . "</div></div>";

	}

}

$equipment = "";

$resresult = getReservationsByUserID(getSessionVariable('user_id'),5);

while($row = mysql_fetch_assoc($resresult)){

	$equip = mysql_fetch_assoc(getEquipmentByID($row['equip_id']));

	$status = "unknown";

	if($row['mod_status'] == RES_STATUS_PENDING){

		$status = "Pending";

	}
	else if($row['mod_status'] == RES_STATUS_CONFIRMED){

		$status = "Approved";

	}
	else if($row['mod_status'] == RES_STATUS_DENIED){

		$status = "Denied";

	}
	else if($row['mod_status'] == RES_STATUS_CHECKED_IN){

		$status = "Checked-In";

	}
	else if($row['mod_status'] == RES_STATUS_CHECKED_OUT){
		$status = "Checked-Out";
	}

	$equipment = $equipment . "
		
	<tr>
			
		<td class=\"myequip".$status."\">".$equip['name']."</td>
		<td class=\"myequip".$status."\">".$status."</td>
		<td class=\"myequip".$status."\">".$row['start_date']."</td>
		<td class=\"myequip".$status."\">".$row['end_date']."</td>
		<td class=\"myequip".$status."\"><a href=\"./index.php?pageid=viewreservation&resid=".$row['res_id']."\">View</a></td>
			
	</tr>
		
	";

}

require 'hourscalendar.php';

$homepage = "
	<center>
$messages
$hourscalendar
		
		<h3>Your Equipment</h3>
		
		<table class=\"myequip\">
		
			<tr>
		
				<td class=\"header\">Equipment Name</td>
				<td class=\"header\">Status</td>
				<td class=\"header\">Check-out Date</td>
				<td class=\"header\">Due Date</td>
				<td class=\"header\">-</td>
			
			</tr>
			
			".$equipment."
	
		</table>
	
	</center>";

echo $homepage;

?>
