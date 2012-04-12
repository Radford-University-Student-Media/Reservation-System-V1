<?php

if(mysql_num_rows(getActiveWarningsForUser(getSessionVariable('user_id'))) < RES_WARNING_MAX_ACTIVE){

	if(isset($_GET['equipid']))
	$equipid = $_GET['equipid'];

	$message = "";

	if($pageid == "finishres"){

		$userid = getSessionVariable('user_id');
		$equipid = $_POST['equip_id'];
		$startdate = $_POST['startdate'];
		$length = $_POST['length'];
		$usercomment = $_POST['usercomment'];

		$start_Date = new DateTime(''.$startdate.' 00:00:00');
		$start_Date->modify("+".$length." day");
		//$interval = new DateInterval("P".$length."D");
		//$start_Date->add($interval);
		$enddate = $start_Date->format("Y-m-d");

		$numrows = mysql_num_rows(getReservationsByEquipIDandDate($equipid, $startdate, $enddate));

		if($numrows > 0){

			$message = "<font color=\"#FF0000\"><b>Error: There already is a reservation durring your start date and end date.</b><br>Please check <a href=\"./viewsched.php?equipid=".$equipid."\" target=\"_blank\">the shedule</a>.</font><br><br>";

		}
		else if($numrows == 0 && ($equipid != "" && $startdate != "" && $length != "")){

			$equipment = mysql_fetch_assoc(getEquipmentByID($equipid));

			if($equipment['max_length'] < $length){
					
				$message = "<font color=\"#FF0000\"><b>Error: Cannot reserve this equipment for that long.</b></font><br><br>";
					
			}
			else{
					
				if(isDateRangeBlackedOut($startdate,$enddate)){

					$message = "<font color=\"#FF0000\"><b>Error: Date Range Blacked-Out.</b></font><br><br>";

				}else{
						
					if($equipment['checkoutfrom'] != -1){
							
						$message = "<font color=\"#FF0000\"><b>Error: You must check this out from it's assigned user.</b></font><br><br>";

					}
					else{
						createReservation($userid, $equipid, $startdate, $length, $usercomment, "", 0);
						$message = "<font color=\"#005500\"><b>Successfully created new reservation!</b></font><br><br>";
					}
						
				}
					
			}

		}

	}

	$equipment = mysql_fetch_assoc(getEquipmentByID($equipid));

	$length = "";

	for($i = 1; $i <= $equipment["max_length"]; $i++){

		$length = $length . "<option value=\"".($i)."\">".($i)."</option>";

	}

	echo "
		<center><h3>Make Reservation</h3>".$message."</center>
		
		<script type=\"text/javascript\">
			function checkDate(){

				if(document.reservation.startdate.value == \"".getCurrentMySQLDate()."\"){

					return confirm(\"Reservations placed on the same day as they are created cannot be guaranteed to be ready for their start date. By continuing you are acknowledging that. Would you like to continue?\");

				}else{
					return true;
				}

			}
		</script>

		<form name=\"reservation\" action=\"./index.php?pageid=finishres\" method=\"POST\" onsubmit=\"return checkDate();\">
		
		<table class=\"reservation\">
		
			<tr>
			
				<td colspan=4 class=\"header\">Reserve the ".$equipment["name"]."<input type=\"hidden\" name=\"equip_id\" value=\"".$equipid."\"></td>
			
			</tr>
		
			<tr>
			
				<td class=\"centeredcellbold\">Date (YYYY-MM-DD)</td>
				<td class=\"centeredcell\"><script language=\"JavaScript\" id=\"jscal1x\">
	var cal1x = new CalendarPopup(\"testdiv1\");
	</script><input type=\"text\" name=\"startdate\" id=\"startdate\" onClick=\"cal1x.select(document.forms[0].startdate,'anchor1x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor1x\" id=\"anchor1x\">a</a></td>
				<td class=\"centeredcellbold\">Length</td>
				<td class=\"centeredcell\"><select name=\"length\">".$length."</select></td>
		
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
			
				<td colspan=1 class=\"centeredcellbold\">User Comment</th>
				<td class=\"centeredcell\" colspan=3><textarea rows=5 cols=45 name=\"usercomment\"></textarea></td>
			
			</tr>
				
				<tr>
				
					<td colspan=4 class=\"centeredcell\"><input type=\"submit\" value=\"Reserve\"></td>
		
				</tr>
				
			</table>
			</form></div><DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;\"></DIV>
	";

}else{

	echo "<center><h3><font color=\"#FF0000\">Error: You have recieved 3 or more warnings.</font></h3>To reserve equipment please contact an admin: <br><br>";

	$admins = getAdmins();

	while($row = mysql_fetch_assoc($admins)){

		echo $row['name'] . " -- " . $row['email'] . "<br>";

	}

	echo "</center>";

}
	
?>
