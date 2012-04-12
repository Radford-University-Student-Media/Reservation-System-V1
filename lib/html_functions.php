<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: /lib/html_functions.php

Purpose:
This file contains all functions for generating html.

Known Bugs/Fixes:

None

*/

function getEquipmentTypesDropDown($name, $size){

	return getEquipmentTypesDropDownSelected($name, $size, null);

}

function getEquipmentTypesDropDownSelected($name, $size, $selectedvalue){

	$types = getConfigVar("equipment_types");

	$options = "";

	foreach($types as $type){

		if($selectedvalue == $type){
			$options = $options . "<option value=\"".$type."\" selected=\"selected\">".$type."</option>";
		}else{
			$options = $options . "<option value=\"".$type."\">".$type."</option>";
		}

	}

	$dropdown = "<select name=\"".$name."\" size=\"".$size."\">".$options."</select>";

	return $dropdown;

}

function getUserLevelList(){

	return "".RES_USERLEVEL_NOLOGIN.": ".RES_USERLEVEL_STRING_NOLOGIN
	.", ".RES_USERLEVEL_USER.": ".RES_USERLEVEL_STRING_USER
	.", ".RES_USERLEVEL_LEADER.": ".RES_USERLEVEL_STRING_LEADER
	.", ".RES_USERLEVEL_PROFESSOR.": ".RES_USERLEVEL_STRING_PROFESSOR
	.", ".RES_USERLEVEL_ADMIN.": ".RES_USERLEVEL_STRING_ADMIN;

}

function getUserLevelDropDown($name){

	return "<select name=\"".$name."\">
				<option value=\"".RES_USERLEVEL_NOLOGIN."\">".RES_USERLEVEL_STRING_NOLOGIN."</option>
				<option value=\"".RES_USERLEVEL_USER."\">".RES_USERLEVEL_STRING_USER."</option>
				<option value=\"".RES_USERLEVEL_LEADER."\">".RES_USERLEVEL_STRING_LEADER."</option>
				<option value=\"".RES_USERLEVEL_PROFESSOR."\">".RES_USERLEVEL_STRING_PROFESSOR."</option>
				<option value=\"".RES_USERLEVEL_ADMIN."\">".RES_USERLEVEL_STRING_ADMIN."</option>
			</select>";

}

function getUserLevelDropDownSelected($name, $selected){

	$selectedText = array();

	for($i = 0; $i <= RES_USERLEVEL_ADMIN; $i++){

		$selectedText[$i] = "";

	}

	$selectedText[$selected] = "selected=\"selected\"";

	return "<select name=\"".$name."\">
				<option value=\"".RES_USERLEVEL_NOLOGIN."\" ".$selectedText[RES_USERLEVEL_NOLOGIN].">".RES_USERLEVEL_STRING_NOLOGIN."</option>
				<option value=\"".RES_USERLEVEL_USER."\" ".$selectedText[RES_USERLEVEL_USER].">".RES_USERLEVEL_STRING_USER."</option>
				<option value=\"".RES_USERLEVEL_LEADER."\" ".$selectedText[RES_USERLEVEL_LEADER].">".RES_USERLEVEL_STRING_LEADER."</option>
				<option value=\"".RES_USERLEVEL_PROFESSOR."\" ".$selectedText[RES_USERLEVEL_PROFESSOR].">".RES_USERLEVEL_STRING_PROFESSOR."</option>
				<option value=\"".RES_USERLEVEL_ADMIN."\" ".$selectedText[RES_USERLEVEL_ADMIN].">".RES_USERLEVEL_STRING_ADMIN."</option>
			</select>";

}

?>