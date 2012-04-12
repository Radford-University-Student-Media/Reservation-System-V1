<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: /lib/db_log_functions.php

Purpose:
This file contains all functions related to logs in the database

Known Bugs/Fixes:

None

*/

function logAddEquipment($userid, $equipid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "equipment", "Created Equipment ".$equipid);

}

function logAddUser($userid, $newuserid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "user", "Created User ".$newuserid);

}

function logChangeUserPassword($userid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "user", "Updated User Password");

}

function logChangeUserEmail($userid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "user", "Updated User Email");

}

function logChangeUserNotes($userid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "user", "Updated User Notes");

}

function logAdminCreateReservation($adminid, $userid, $reservationid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($adminid, "reservation", "Created Reservation ".$reservationid." for User ".$userid);

}

function logCreateReservation($userid, $reservationid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "reservation", "Created Reservation ".$reservationid);


}

function logAdminConfirmReservation($userid, $resid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "reservation", "Confirmed/Updated Reservation ".$resid);


}

function logAdminCheckOutReservation($userid, $resid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "reservation", "Checked-out Reservation ".$resid);


}

function logAdminCheckInReservation($userid, $resid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "reservation", "Checked-in Reservation ".$resid);


}

function logAdminDeleteReservation($userid, $resid){

	$mysqldate = getCurrentMySQLDateTime();

	addToLog($userid, "reservation", "User ".$userid." Deleted Reservation ".$resid);


}

function addToLog($userid, $action ,$description){

	$userid = makeStringSafe($userid);
	$action = makeStringSafe($action);
	$description = makeStringSafe($description);

	$mysqldate = getCurrentMySQLDateTime();
	$ip = getClientIP();
	$hostname = getClientHostname();

	doQuery("INSERT INTO ".getDBPrefix()."_log SET user_id = '".$userid."', action_type = '".$action."', action_description = '".$description."', date = '".$mysqldate."', ip = '".$ip."', hostname='".$hostname."'");

}

?>
