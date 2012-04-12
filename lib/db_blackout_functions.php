<?php/*Radford Reservation SystemAuthor: Andrew MeltonFilename: /lib/db_blackout_functions.phpPurpose:This file contains all functions related to blackouts in the databaseKnown Bugs/Fixes:None*/function createBlackout($name, $comment, $start, $end, $user_level){	$name = makeStringSafe($name);	$comment = makeStringSafe($comment);	$start = makeStringSafe($start);	$end = makeStringSafe($end);	$user_level = makeStringSafe($user_level);	doQuery("INSERT INTO ".getDBPrefix()."_blackouts SET name = '".$name."', comments = '".$comment."', start_date = '".$start."', end_date = '".$end."', user_level = '".$user_level."'");}function updateBlackout($bid, $name, $comment, $start, $end, $user_level){	$pid = makeStringSafe($bid);	$name = makeStringSafe($name);	$comment = makeStringSafe($comment);	$start = makeStringSafe($start);	$end = makeStringSafe($end);	$user_level = makeStringSafe($user_level);	doQuery("UPDATE ".getDBPrefix()."_blackouts SET name = '".$name."', comments = '".$comment."', start_date = '".$start."', end_date = '".$end."', user_level = '".$user_level."' WHERE blackout_id = '".$bid."'");}function deleteBlackout($bid){	$bid = makeMySQLSage($bid);	doQuery("DELETE FROM ".getDBPrefix()."_blackouts WHERE blackout_id = ".$bid." limit 1");}/*This function is used to check whether or not the provided date rangeintersects or falls between a blackout.*/function isDateRangeBlackedOut($start, $end){	$result = doQuery("SELECT * FROM ".getDBPrefix()."_blackouts WHERE (start_date <= '".$start."' and end_date >= '".$start."') OR (start_date <= '".$end."' and end_date >= '".$end."')");	if(mysql_num_rows($result)>0){		return true;	}else{		return false;	}}function getBlackouts(){	return doQuery("SELECT * FROM ".getDBPrefix()."_blackouts");}function getBlackoutByID($bid){	return doQuery("SELECT * FROM ".getDBPrefix()."_blackouts WHERE blackout_id = ".$bid." limit 1");}?>