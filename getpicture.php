<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: getpicture.php

Purpose:
This will get and display the picture for equipment with the equipid
provided by the GET variable 'equip'

Known Bugs/Fixes:

None

*/

header("Content-type: image/jpeg");

session_start();

$equipid = $_GET['equip'];

require 'functions.php';

$equipid = makeMySQLSafe($equipid);

$pictureloc = "./pics/";

$row= mysql_fetch_assoc(doQuery("SELECT picture FROM ".getDBPrefix()."_equipment WHERE equip_id = '".$equipid."'"));

if($row['picture'] == ""){

	$pictureloc = $pictureloc."nopic.jpg";

}
else{

	$pictureloc = $pictureloc . $row['picture'];

}

readfile($pictureloc);

exit(0);

?>