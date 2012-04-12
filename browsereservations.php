<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: confirmReservation.php

Purpose:
This page allows an admin to select two dates and display all reservations
in those dates inclusively. It will automatically select the current day
as the start and a week ahead as the end. From that list an admin can click
the 'view' link and be sent to a more detailed page.

Known Bugs/Fixes:

Bug #1:
None

Todo:

#1
Add options to limit to confirmed only, denied only, or checked-in only.
Eventually it'd be nice to just have check boxes.

*/

$browsetable = "";
$start = "";
$end = "";


if(isset($_GET['start']) && isset($_GET['end'])){

	$start = $_GET['start'];
	$end = $_GET['end'];

}else{

	$mysqldate = getCurrentMySQLDate();
	$start_Date = new DateTime(''.$mysqldate.' 00:00:00');
	$start_Date->modify("+7 day");
	//$interval = new DateInterval("P7D");
	//$start_Date->add($interval);
	$enddate = $start_Date->format("Y-m-d");

	$start = $mysqldate;
	$end = $enddate;

}

$browsetable = "P: Pending | C: Confirmed | D: Denied | CO: Checked-Out | CI: Checked-In<br><br>
<table class=\"browse\">
	<tr>
		<td class=\"header\">User</td>
		<td class=\"header\">Equipment</td>
		<td class=\"header\">Start Date</td>
		<td class=\"header\">Status</td>
		<td class=\"header\">Due Date</td>
		<td class=\"header\">-</td>
		<td class=\"header\">-</td>
	</tr>";

$result = getReservationsByDate($start, $end);

while($row = mysql_fetch_assoc($result)){

	$status = "-";
	if($row['mod_status']==RES_STATUS_PENDING){

		$status = "P";

	}
	else if($row['mod_status']==RES_STATUS_CONFIRMED){

		$status = "C";

	}
	else if($row['mod_status']==RES_STATUS_DENIED){

		$status = "D";

	}
	else if($row['mod_status']==RES_STATUS_CHECKED_IN){

		$status = "CI";

	}
	else if($row['mod_status']==RES_STATUS_CHECKED_OUT){
		$status = "CO";
	}

	$equip = mysql_fetch_assoc(getEquipmentByID($row['equip_id']));
	$user = mysql_fetch_assoc(getUserByID($row['user_id']));
	$editlink = "&nbsp;&nbsp;-&nbsp;&nbsp;";
	if(getSessionVariable('user_level') == getConfigVar("admin_rank")){
		$editlink = "<a href=\"./index.php?pageid=editreservation&resid=".$row['res_id']."\">Edit</a>";
	}
	$browsetable = $browsetable . "<tr><td class=\"centeredcell\"><a href=\"./userinfo.php?user_id=".$user['user_id']."\" target=\"_BLANK\">".$user['name']."</a></td><td class=\"centeredcell\">".$equip['name']."</td><td class=\"centeredcell\">".$row['start_date']."</td><td class=\"centeredcell\">".$status."</td><td class=\"centeredcell\">".$row['end_date']."</td><td class=\"centeredcell\"><a href=\"./index.php?pageid=viewreservation&resid=".$row['res_id']."\">View</a></td><td class=\"centeredcell\">".$editlink."</td></tr>";

}

$browsetable = $browsetable . "</table>";

echo "
<script language=\"JavaScript\" id=\"jscal1x\">
var cal1x = new CalendarPopup(\"testdiv1\");
</script>

<center>
	<h3>Browse Reservations</h3>

	<form action=\"./index.php\" method=\"GET\">
		<input type=\"hidden\" name=\"pageid\" value=\"browseres\">
		Start: <input type=\"text\" name=\"start\" value=\"".$start."\" id=\"start\" onClick=\"cal1x.select(document.forms[0].start,'anchor1x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor1x\" id=\"anchor1x\">a</a> 
		End: <input type=\"text\" name=\"end\" value=\"".$end."\" id=\"end\" onClick=\"cal1x.select(document.forms[0].end,'anchor2x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor2x\" id=\"anchor2x\">a</a>
		<input type=\"Submit\" value=\"View\">
	</form>

	".$browsetable."
</center><br>

<DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;\"></DIV>
";

?>
