<?php

$message = "";

$alreadyRes = false;

if($pageid == "finishmakeres"){

	$userid = $_POST['user_id'];
	$equipid = $_POST['equip_id'];
	$startdate = $_POST['startdate'];
	$length = $_POST['length'];
	$usercomment = $_POST['usercomment'];
	$admincomment = $_POST['admincomment'];

	$start_Date = new DateTime(''.$startdate.' 00:00:00');
	$start_Date->modify("+".$length." day");
	//$interval = new DateInterval("P".$length."D");
	//$start_Date->add($interval);
	$enddate = $start_Date->format("Y-m-d");

	$numrows = mysql_num_rows(getReservationsByEquipIDandDate($equipid, $startdate, $enddate));

	if($numrows > 0 && !isset($_POST['confirm'])){

		$message = "<font color=\"#FF0000\"><b>Error: There already is a reservation durring your start date and end date.</b></font><br><br>";

		echo "<center><h3>Make Reservation</h3>".$message."
		
		<form action=\"./index.php?pageid=finishmakeres\" method=\"POST\">
		
			<input type=\"hidden\" name=\"user_id\" value=\"".$userid."\">
			<input type=\"hidden\" name=\"equip_id\" value=\"".$equipid."\">
			<input type=\"hidden\" name=\"startdate\" value=\"".$startdate."\">
			<input type=\"hidden\" name=\"length\" value=\"".$length."\">
			<input type=\"hidden\" name=\"usercomment\" value=\"".$usercomment."\">
			<input type=\"hidden\" name=\"admincomment\" value=\"".$admincomment."\">
		
			Are you sure you want to make this reservation?<br>
			<input type=\"radio\" name=\"confirm\" value=\"yes\">: Yes -- <input type=\"radio\" name=\"confirm\" value=\"no\">: No
			<br><input type=\"submit\" value=\"continue\">
		
		</form></center>
		
		";

		$alreadyRes = true;

	}else{

		if(isset($_POST['confirm']) && $_POST['confirm'] == "no"){

			$alreadyRes = true;
			$message = "<font color=\"#005500\"><b>Reservation Aborted.</b></font><br><br>";

		}

	}

	if(!$alreadyRes && $equipid != "" && $startdate != "" && $length != ""){

		createAdminReservation(getSessionVariable('user_id'),$userid, $equipid, $startdate, $length, $usercomment, $admincomment, 1);
		$message = "<font color=\"#005500\"><b>Successfully created new reservation!</b></font><br><br>";

	}

}

$users = "<select name=\"user_id\">";

$userresult = getAllUsersOrderByName();

while($row = mysql_fetch_assoc($userresult)){

	$users = $users . "<option value=\"".$row['user_id']."\">".$row['name']."</option>";

}

$equipment = "<select name=\"equip_id\">";

$equipresult = getAllEquipment();

while($row = mysql_fetch_assoc($equipresult)){

	if(getSessionVariable('user_level') >= $row['min_user_level'])
	$equipment = $equipment . "<option value=\"".$row['equip_id']."\">".$row['name']." -- Max: ".$row['max_length']." day(s)</option>";


}

$equipment = $equipment . "</select>";

if(!$alreadyRes){

	echo "
<script language=\"JavaScript\" id=\"jscal1x\">
var cal1x = new CalendarPopup(\"testdiv1\");
</script>

	<center><h3>Make Reservation</h3>".$message."</center>
	
	<form action=\"./index.php?pageid=finishmakeres\" method=\"POST\">
	
	<table class=\"reservation\">
	
		<tr>

			<td colspan=4 class=\"header\">Reservation Information</td>
			
		</tr>
		<tr>

			<td class=\"centeredcellbold\">User</td>
			<td colspan=3 class=\"centeredcell\">".$users."</td>
			
		</tr>
	
		<tr>

			<td class=\"centeredcellbold\">Equipment</td>
			<td colspan=3 class=\"centeredcell\">".$equipment."</td>
			
		</tr>
	
		<tr>
		
			<td class=\"centeredcellbold\">Date (YYYY-MM-DD)</td>
			<td class=\"centeredcell\"><input type=\"text\" name=\"startdate\" id=\"startdate\" onClick=\"cal1x.select(document.forms[0].startdate,'anchor1x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor1x\" id=\"anchor1x\">a</a></td>
			<td class=\"centeredcellbold\">Length</th>
			<td class=\"centeredcell\"><input type=\"text\" size=5 name=\"length\"></td>
	
		</tr>
	
		<!--<tr>
			
			<td colspan=4 class=\"centeredcellbold\">Pickup Time: 
				<select name=\"pickup\">
					<option value=\"10am-12pm (Monday/Friday)\">10am-12pm (Monday/Friday)</option>
					<option value=\"9am-12pm (Tuesday)\">9am-12pm (Tuesday)</option>
					<option value-\"9-10 (Wednesday/Thursday)\">9-10 (Wednesday/Thursday)</option>
					<option value=\"2pm-4pm (Wednesday)\">2pm-4pm (Wednesday)</option>
					<option value=\"12pm-2pm (Thursday)\">12pm-2pm (Thursday)</option>
				</select>
			</td>
				
		</tr>-->

		<tr>
		
			<td colspan=1 class=\"centeredcellbold\">User Comment</td>
			<td class=\"centeredcell\" colspan=3><textarea rows=5 cols=45 name=\"usercomment\"></textarea></td>
		
		</tr>

		<tr>
		
			<td colspan=1 class=\"centeredcellbold\">Admin Comment</td>
			<td class=\"centeredcell\" colspan=3><textarea rows=5 cols=45 name=\"admincomment\"></textarea></td>
		
		</tr>
			
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\"><input type=\"submit\" value=\"Reserve\"></td>
	
			</tr>
			
		</table>
		</form><DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;\"></DIV>";

}else{

	echo "<center><h3>Make Reservation</h3>".$message."</center>";

}
?>
