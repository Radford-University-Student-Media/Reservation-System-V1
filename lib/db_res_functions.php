<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: /lib/db_res_functions.php

Purpose:
This file contains all functions related to reservations in the database

Known Bugs/Fixes:

None

*/

/*

This is the function used when a standard user creates a reservation.
It will check to make sure the due date is on a weekday, if it isn't
it will loop, adding 1 day, until it gets to a weekday. After it saves
the reservation to the DB it will send an email to all admins.

*/

function createReservation($userid, $equipid, $startdate, $length, $usercomment, $admincomment, $modstatus){

	$userid = makeStringSafe($userid);
	$equipid = makeStringSafe($equipid);
	$startdate = makeStringSafe($startdate);
	$length = makeStringSafe($length);
	$usercomment = makeStringSafe($usercomment);
	$admincomment = makeStringSafe($admincomment);
	$modstatus = makeStringSafe($modstatus);

	$start_Date = new DateTime(''.$startdate.' 00:00:00');
	$start_Date->modify("+".$length." day");
	//$interval = new DateInterval("P".$length."D");
	//$start_Date->add($interval);
	$enddate = $start_Date->format("Y-m-d");
	$tempdate = new DateTime(''.$enddate.' 00:00:00');

	while($tempdate->format("D") == "Sat" || $tempdate->format("D") == "Sun"){

		//$tempdate->add(new DateInterval("P1D"));
		$tempdate->modify("+1 day");
		$length = $length+1;

	}

	$enddate = $tempdate->format("Y-m-d");

	doQuery("INSERT INTO ".getDBPrefix()."_reservations SET user_id = '".$userid."', equip_id = '".$equipid."', start_date = '".$startdate."', end_date = '".$enddate."', length = '".$length."', pickup_time = '', user_comment = '".$usercomment."', admin_comment = '".$admincomment."', mod_status = '".$modstatus."'");

	$res = mysql_fetch_assoc(doQuery("SELECT res_id FROM ".getDBPrefix()."_reservations ORDER BY res_id DESC LIMIT 1"));

	sendReservationNoticeToAdmins($res['res_id']);

	logCreateReservation($userid, $res['res_id']);

}

/*

This does that same as the standard createReservation except it saves
the reservation already confirmed and it will not email all admins.

*/

function createAdminReservation($adminid, $userid, $equipid, $startdate, $length, $usercomment, $admincomment, $modstatus){

	$adminid = makeStringSafe($adminid);
	$userid = makeStringSafe($userid);
	$equipid = makeStringSafe($equipid);
	$startdate = makeStringSafe($startdate);
	$length = makeStringSafe($length);
	$usercomment = makeStringSafe($usercomment);
	$admincomment = makeStringSafe($admincomment);
	$modstatus = makeStringSafe($modstatus);

	$start_Date = new DateTime(''.$startdate.' 00:00:00');
	$start_Date->modify("+".$length." day");
	//$interval = new DateInterval("P".$length."D");
	//$start_Date->add($interval);
	$enddate = $start_Date->format("Y-m-d");
	$tempdate = new DateTime(''.$enddate.' 00:00:00');

	while($tempdate->format("D") == "Sat" || $tempdate->format("D") == "Sun"){

		//$tempdate->add(new DateInterval("P1D"));
		$tempdate->modify("+1 day");
		$length = $length+1;

	}

	$enddate = $tempdate->format("Y-m-d");

	doQuery("INSERT INTO ".getDBPrefix()."_reservations SET user_id = '".$userid."', equip_id = '".$equipid."', start_date = '".$startdate."', end_date = '".$enddate."', length = '".$length."', pickup_time = '', user_comment = '".$usercomment."', admin_comment = '".$admincomment."', mod_status = '".$modstatus."'");

	$res = mysql_fetch_assoc(doQuery("SELECT res_id FROM ".getDBPrefix()."_reservations ORDER BY res_id DESC LIMIT 1"));

	logAdminCreateReservation($adminid ,$userid, $res['res_id']);

}

/*

This is used by the edit reservation page to change the dates of
a reservation. It updates the reservation with the resid provided
with the start and end dates provided.

TODO:
Allow for changing status ("confirmed/denied")
Add a log query

*/

function updateReservation($resid, $startdate, $enddate){

	$resid = makeStringSafe($resid);
	$startdate = makeStringSafe($startdate);
	$enddate = makeStringSafe($enddate);

	doQuery("UPDATE ".getDBPrefix()."_reservations SET start_date = '".$startdate."', end_date = '".$enddate."' WHERE res_id = ".$resid);

}

/*

This is used by the reservation confirmation page the admins get sent.
It will update the reservation to be confirmed or denied and save the
admin comment. Finally, it sends the reservation's user an email.

*/

function confirmReservation($resid, $admincomment, $status){

	$resid = makeStringSafe($resid);
	$admincomment = makeStringSafe($admincomment);
	$status = makeStringSafe($status);

	doQuery("UPDATE ".getDBPrefix()."_reservations SET admin_comment = '".$admincomment."', mod_status = '".$status."' WHERE res_id = '".$resid."'");

	$res = mysql_fetch_assoc(getReservationByID($resid));

	$user = mysql_fetch_assoc(getUserByID($res['user_id']));

	sendReservationNoticeToUser($user['email'], $resid, $status, $admincomment);

	logAdminConfirmReservation(getSessionVariable('user_id'),$resid);

}

function updateReservationStatus($resid, $status){

	$resid = makeStringSafe($resid);
	$status = makeStringSafe($status);

	doQuery("UPDATE ".getDBPrefix()."_reservations SET mod_status = '".$status."' WHERE res_id = '".$resid."'");

}

/*

This is used by the admins and media leaders to confirm that a piece of equipment has been
checked-out.

*/

function checkOutReservation($res_id){

	$res_id = makeStringSafe($res_id);

	$mysqldate = getCurrentMySQLDate();

	doQuery("UPDATE ".getDBPrefix()."_reservations SET mod_status = '".RES_STATUS_CHECKED_OUT."', checked_out_by = '".getSessionVariable('user_id')."' ,check_out_date = '".$mysqldate."'  WHERE res_id = ".$res_id."");

	logAdminCheckOutReservation(getSessionVariable('user_id'),$res_id);

}
/*

This is used by the admins and media leaders to confirm that a piece of equipment has been
checked-in.

*/

function checkInReservation($res_id){

	$res_id = makeStringSafe($res_id);

	$mysqldate = getCurrentMySQLDate();

	doQuery("UPDATE ".getDBPrefix()."_reservations SET mod_status = '".RES_STATUS_CHECKED_IN."', checked_in_by = '".getSessionVariable('user_id')."' ,check_in_date = '".$mysqldate."'  WHERE res_id = ".$res_id."");

	logAdminCheckInReservation(getSessionVariable('user_id'),$res_id);

}

function deleteReservation($res_id){

	$res_id = makeStringSafe($res_id);

	doQuery("DELETE FROM ".getDBPrefix()."_reservations WHERE res_id = ".$res_id."");

	logAdminDeleteReservation(getSessionVariable('user_id'),$res_id);

}

function getReservationsByUserID($userid, $limit){

	$userid = makeStringSafe($userid);
	$limit = makeStringSafe($limit);

	return doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE user_id = '".$userid."' ORDER BY start_date DESC LIMIT ".$limit."");

}

function getReservationsByEquipID($equipid, $limit){

	$equipid = makeStringSafe($equipid);
	$limit = makeStringSafe($limit);

	return doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE equip_id = '".$equipid."' ORDER BY start_date ASC LIMIT ".$limit."");

}

function getReservationByID($resid){

	$resid = makeStringSafe($resid);

	return doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE res_id = '".$resid."'");

}

function getReservationsByDate($startdate, $enddate){

	$startdate = makeStringSafe($startdate);
	$enddate = makeStringSafe($enddate);

	return doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE (start_date BETWEEN '".$startdate."' AND '".$enddate."') OR (end_date BETWEEN '".$startdate."' AND '".$enddate."') ORDER BY start_date ASC");

}

function getReservationsByEquipIDandDate($equip,$startdate, $enddate){

	$equip = makeStringSafe($equip);
	$startdate = makeStringSafe($startdate);
	$enddate = makeStringSafe($enddate);

	return doQuery("SELECT * FROM ".getDBPrefix()."_reservations WHERE
	equip_id = '".$equip."' AND 
	(mod_status = '".RES_STATUS_PENDING."' OR mod_status = '".RES_STATUS_CONFIRMED."' OR mod_status = '".RES_STATUS_CHECKED_OUT."')
	AND ((start_date BETWEEN '".$startdate."' AND '".$enddate."') OR (end_date BETWEEN '".$startdate."' AND '".$enddate."'))");

}

?>
