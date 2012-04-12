<?php

if(issetSessionVariable('user_level')){

	if(getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN){



	}
	else{

		echo "Error: You don't have permissions to access this page!";
		die("");

	}

}
else{

	echo "Error: You don't have permissions to access this page!";
	die("");

}

function saveEquipment($equipid, $name, $type , $serial, $maxlength, $minuserlevel, $checkoutfrom, $description){

	$equipid = makeStringSafe($equipid);
	$name = makeStringSafe($name);
	$type = makeStringSafe($type);
	$serial = makeStringSafe($serial);
	$maxlength = makeStringSafe($maxlength);
	$minuserlevel = makeStringSafe($minuserlevel);
	$checkoutfrom = makeStringSafe($checkoutfrom);
	$description = makeStringSafe($description);

	doQuery("UPDATE ".getDBPrefix()."_equipment SET name = '".$name."', type = '".$type."', serial = '".$serial."',
	max_length = '".$maxlength."', min_user_level = '".$minuserlevel."', checkoutfrom = '".$checkoutfrom."',
	description = '".$description."' WHERE equip_id = '".$equipid."'");

}

function addEquipment($name, $type, $serial, $description, $max, $picture, $minuserlevel, $checkoutfrom){

	$name = makeStringSafe($name);
	$type = makeStringSafe($type);
	$serial = makeStringSafe($serial);
	$description = makeStringSafe($description);
	$max = makeStringSafe($max);
	$picture = makeStringSafe($picture);
	$minuserlevel = makeStringSafe($minuserlevel);
	$checkoutfrom = makeStringSafe($checkoutfrom);

	doQuery("INSERT INTO ".getDBPrefix()."_equipment SET name = '".$name."', type = '".$type."', serial = '".$serial."', description = '".$description."', max_length = '".$max."', picture = '".$picture."', min_user_level = '".$minuserlevel."', checkoutfrom = '".$checkoutfrom."'");

	$equip = mysql_fetch_assoc(doQuery("SELECT equip_id FROM ".getDBPrefix()."_equipment ORDER BY equip_id DESC LIMIT 1"));

	logAddEquipment(getSessionVariable('user_id'), $equip['equip_id']);

}

function addMessage($userid, $startdate, $enddate, $priority, $body){

	$userid = makeStringSafe($userid);
	$startdate = makeStringSafe($startdate);
	$enddate = makeStringSafe($enddate);
	$priority = makeStringSafe($priority);
	$body = makeStringSafe($body);

	doQuery("INSERT INTO ".getDBPrefix()."_messages SET user_id = '".$userid."', start_date = '".$startdate."', end_date = '".$enddate."', priority = '".$priority."', body = '".$body."'");

}

function saveMessage($messageid, $userid, $startdate, $enddate, $priority, $body){

	$messageid = makeStringSafe($messageid);
	$userid = makeStringSafe($userid);
	$startdate = makeStringSafe($startdate);
	$enddate = makeStringSafe($enddate);
	$priority = makeStringSafe($priority);
	$body = makeStringSafe($body);

	doQuery("UPDATE ".getDBPrefix()."_messages SET user_id = '".$userid."', start_date = '".$startdate."', end_date = '".$enddate."', priority = '".$priority."', body = '".$body."' WHERE message_id = '".$messageid."'");

}

function deleteMessage($messageid){

	$messageid = makeStringSafe($messageid);

	doQuery("DELETE FROM ".getDBPrefix()."_messages WHERE message_id = '".$messageid."' LIMIT 1");

}

?>