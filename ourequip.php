<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: ourequip.php

Associated PageIDs:
"ourequip"

Purpose:
This page displays all available equipment for the current user to
reserve. It checks the minimum user level for the equipment against
the user's user level and will only display equipment the logged in
user can reserve.

Known Bugs/Fixes:

None

*/

$ourequipment = "";
$typelinks = "";

$equipArray = array();
$equipresult = getAllEquipment();

while($row = mysql_fetch_assoc($equipresult)){


	if(array_key_exists($row['type'], $equipArray)){

		array_push($equipArray[$row['type']], $row);

	}else{

		$equipArray[$row['type']] = array($row['type'] => $row);

	}

}

$equipKeys = array_keys($equipArray);

$i = 0;
foreach($equipKeys as $key){

	$typelinks = $typelinks . "<a href=\"#".$key."\">".$key."</a>";

	if($i+1 < count($equipKeys)){

		$typelinks = $typelinks . " - ";
		$i++;

	}

}

foreach($equipKeys as $key){

	$ourequipment = $ourequipment . "<h3>".$key."</h3><table class=\"ourequip\">
		
			<tr>
			
				<td width=\"40%\" class=\"header\" id=\"".$key."\">Equipment Name</th>
				<td width=\"15%\"  class=\"header\">--</th>
				<td width=\"25%\" class=\"header\">Status</th>
				<td width=\"20%\" class=\"header\">--</th>
				
			</tr>";

	foreach($equipArray[$key] as $row){

		$status = "-";

		/*
			The current piece of equipment is NOT at Calhoun
		*/
		if(isEquipmentOut($row['equip_id'], getCurrentMySQLDate())){
			$status = "Out";
		}

		/*
			The current piece of equipment will be out of Calhoun in a few days
		-For exact length see isEquipmentReserved() in functions.php
		*/
		else if(isEquipmentReserved($row['equip_id'], getCurrentMySQLDate())){
			$status = "Reserved";
		}

		else{
			$status = "Available";
		}

		/*
			Check logged in user's user level against the equipments min user level
		*/
		if(getSessionVariable('user_level') >= $row['min_user_level']){

			if($row['checkoutfrom'] == -1){

				$ourequipment = $ourequipment . "<tr><td class=\"centeredcell\">".$row['name']."</td><td class=\"centeredcell\"><a href=\"./index.php?pageid=moreinfo&equipid=".$row['equip_id']."\">More Info</a></td><td class=\"centeredcell\">".$status."</td><td class=\"centeredcell\"><a href=\"./index.php?pageid=reservation&equipid=".$row['equip_id']."\">Reserve</a></td></tr>";

			}else{
					
				$user = mysql_fetch_assoc(getUserByID($row['checkoutfrom']));
					
				$ourequipment = $ourequipment . "<tr><td class=\"centeredcell\">".$row['name']."</td><td class=\"centeredcell\"><a href=\"./index.php?pageid=moreinfo&equipid=".$row['equip_id']."\">More Info</a></td><td class=\"centeredcell\" colspan=2>Checkout from<br><a href=\"mailto:".$user['email']."\">".$user['name']."</a></td></tr>";
					
			}
				
		}


	}

	$ourequipment = $ourequipment . "</table>";

}

$page = "<center><h3>Our Equipment</h3></center>
		<center><b>".$typelinks."</b></center>
		".$ourequipment;

echo $page;

?>