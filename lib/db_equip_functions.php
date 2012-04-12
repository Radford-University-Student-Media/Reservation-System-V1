<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: /lib/db_equip_functions.php

Purpose:
This file contains all functions related to equipment in the database

Known Bugs/Fixes:

None

*/

function getAllEquipment(){

	return doQuery("SELECT equip_id, name, type, min_user_level, max_length, checkoutfrom FROM ".getDBPrefix()."_equipment ORDER BY equip_id ASC");


}

function deleteEquipmentByID($equip_id){

	doQuery("DELETE FROM ".getDBPrefix()."_equipment WHERE equip_id = ".$equip_id."");

}

function getEquipmentByID($equipid){

	$equipid = makeStringSafe($equipid);

	return doQuery("SELECT * FROM ".getDBPrefix()."_equipment WHERE equip_id = '".$equipid."'");

}

/*

This will query the database and find out whether or not on the specific date
a piece of equipment will actually be at the lab.

*/

function isEquipmentOut($equipid, $date){

	$equipid = makeStringSafe($equipid);
	$date = makeStringSafe($date);

	$result = doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE equip_id = '".$equipid."' AND (mod_status = '".RES_STATUS_CONFIRMED."' OR mod_status = '".RES_STATUS_PENDING."') AND ('".$date."' >= start_date and '".$date."' < end_date)");

	if(mysql_num_rows($result)>0){

		return true;

	}else{

		return false;

	}

}

/*

This will query the database and find out if a piece of equipment is
reserved within a 3 day range of the date.

*/

function isEquipmentReserved($equipid, $date){

	$equipid = makeStringSafe($equipid);
	$date = makeStringSafe($date);

	$start_Date = new DateTime($date);
	$start_Date->modify("+3 day");
	//$interval = new DateInterval("P3D");
	//$start_Date->add($interval);

	$result = doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE equip_id = '".$equipid."' AND (mod_status = '".RES_STATUS_CONFIRMED."' or mod_status = '".RES_STATUS_PENDING."') AND (start_date BETWEEN '".$date."' and '".$start_Date->format("Y-m-d")."')");

	if(mysql_num_rows($result)>0){

		return true;

	}else{

		return false;

	}

}

?>