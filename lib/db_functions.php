<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: /lib/db_functions.php

Purpose:
This file contains all general database functions, it also links
to the files containing all more specific database functions.

Known Bugs/Fixes:

None

*/

require_once 'db_user_functions.php';
require_once 'db_warning_functions.php';
require_once 'db_equip_functions.php';
require_once 'db_res_functions.php';
require_once 'db_blackout_functions.php';
require_once 'db_messages_functions.php';
require_once 'db_log_functions.php';

/*
 This number is used by the doQuery() function to count the number of queries.
It is displayed when there is an error with a query to help make debugging
easier.

*/
$numqs = 0;


/*

This is the connection used in the 'initMySQL()', 'doQuery()' and 'closeMySQL()'
functions. It is created by the 'initMySQL()' function and destroyed in the
'closeMySQL()' function.

*/

$link = null;


/*

Since every query needs this variable, a function is just easier.

*/

function getDBPrefix(){

	return getConfigVar("db_prefix");

}

function getCurrentMySQLDate(){

	return date('Y-m-d');

}

function getCurrentMySQLDateTime(){

	return date('Y-m-d H:i:s');

}

/*
 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!! This !MUST! be used on all variables going into a MySQL Query!        !!!!
!!!																		  !!!!
!!! This will safely exit any dangerous characters in the provded string. !!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

function makeStringSafe($string){

	return addslashes($string);

}

/*

This is the old safe string I used before performance testing. I left it in
simply because it would be good to use if you want to be absolutely sure
your string is safe.

*/

function makeMySQLSafe($string){

	global $link;

	if($link == null){

		initMySQL();

	}

	$string = mysql_real_escape_string($string, $link);

	return $string;

}

/*

This initializes the connection to the MySQL DB. It first connects to the
server specified in the config file, then selects the database specified
in the config file.

*/

function initMySQL(){

	global $link;

	$link = mysql_connect(getConfigVar('mysql_server'), getConfigVar('mysql_user'), getConfigVar('mysql_password'));
	if (!$link){
		die('<script language="Javascript"> alert("Q'.$numqs.': Could not connect: ' . mysql_error($link) . '")</script>');
	}

	$db_selected = mysql_select_db(getConfigVar('mysql_database'), $link);
	if(!$db_selected){
		die('<script language="Javascript"> alert("Q' . $numqs . ': Couldn\'t use '.getConfigVar('mysql_database').': ' . mysql_error($link) . '")</script>');
	}

}

/*

All queries should be done through this function.
On query errors it will popup an error message with the query number
and a short description of the error.

*/

function doQuery($v_query){

	global $numqs;
	global $link;

	if($link == null){

		initMySQL();

	}

	$numqs ++;

	$result = mysql_query($v_query, $link);
	if (!$result) {
		die('<script language="Javascript"> alert("Q'.$numqs.': Invalid Query: ' . mysql_error($link) . '\n\nQuery:\n'.$v_query.'")</script>');
	}

	return $result;

}


/*

Closes the global link to the MySQL DB.

*/

function closeMySQL(){

	global $link;

	$error = mysql_error($link);

	if($error){

		die('<script language="Javascript"> alert("Q'.$numqs.': Invalid Query: ' . mysql_error($link) . '")</script>');

	}

	mysql_close($link);

	$link = null;

}

?>