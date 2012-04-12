<?php

$message = "";

if($pageid == "createblackout"){

	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];

	createBlackout($name, $comment, $startdate, $enddate, RES_USERLEVEL_NOLOGIN);
	$message = "<font color=\"#005500\"><b>Successfully created new blackout!</b></font><br><br>";

}
else if($pageid == "saveblackout"){

	$bid = $_POST['bid'];
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];

	updateBlackout($bid, $name, $comment, $startdate, $enddate, RES_USERLEVEL_NOLOGIN);
	$message = "<font color=\"#005500\"><b>Successfully saved blackout!</b></font><br><br>";

}

$blackouts = getBlackouts();
$blackout_select = "";

while($row = mysql_fetch_assoc($blackouts)){

	$blackout_select = $blackout_select."<option value=\"".$row['blackout_id']."\">".$row['name']."</option>";

}

if($pageid == "newblackout"){

	echo "
	<center><h3>Create New Blackout</h3></center>
	
		<table class=\"blackout\">
		<form action=\"index.php?pageid=createblackout\" method=\"post\">
		
			<tr>
			
				<td colspan=4 class=\"header\">Blackout Information</th>
				
			</tr>
		
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\">Name: <input type=\"text\" name=\"name\" size=\"25\"></th>
				
			</tr>
			<tr>
			
				<td class=\"centeredcellbold\">Start Date </td>
				<td class=\"centeredcellbold\"><script language=\"JavaScript\" id=\"jscal1x\">
						var cal1x = new CalendarPopup(\"testdiv1\");
					</script>
					<input type=\"text\" name=\"startdate\" size=\"25\" id=\"startdate\" onClick=\"cal1x.select(document.forms[0].startdate,'anchor1x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor1x\" id=\"anchor1x\">a</a></td>
				<td class=\"centeredcellbold\">End Date </td>
				<td class=\"centeredcellbold\"><input type=\"text\" name=\"enddate\" size=\"25\" id=\"enddate\" onClick=\"cal1x.select(document.forms[0].enddate,'anchor2x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor2x\" id=\"anchor2x\">a</a></td>
			
			</tr>
			<tr>
			
				<td colspan=4 class=\"header\">Blackout Comment</td>
				
			</tr>
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\"><textarea cols=40 rows=5 name=\"comment\"></textarea></td>
				
			</tr>
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\"><input type=\"submit\" value=\"Create\"></td>
				
			</tr>
			
		</form>
		</table><DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;\"></DIV>
	
	</center>

	";

}
else if($pageid == "editblackout" || $pageid == "saveblackout"){

	$blackout = mysql_fetch_assoc(getBlackoutByID($_POST['bid']));
	$start = new DateTime($blackout['start_date']);
	$start_date = $start->format("Y-m-d");
	$end = new DateTime($blackout['end_date']);
	$end_date = $end->format("Y-m-d");

	echo "
	<center><h3>Create New Blackout</h3>".$message."</center>
	
		<table class=\"blackout\">
		<form action=\"index.php?pageid=saveblackout\" method=\"POST\">
		
			<tr>
			
				<td colspan=4 class=\"header\">Blackout Information</th>
				
			</tr>
		
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\">Name: <input type=\"text\" name=\"name\" size=\"25\" value=\"".$blackout['name']."\"><input type=\"hidden\" name=\"bid\" value=\"".$blackout['blackout_id']."\"</th>
				
			</tr><tr>
			
				<td class=\"centeredcellbold\">Start Date </td>
				<td class=\"centeredcellbold\"><script language=\"JavaScript\" id=\"jscal1x\">
						var cal1x = new CalendarPopup(\"testdiv1\");
					</script>
					<input type=\"text\" name=\"startdate\" value=\"".$start_date."\" size=\"25\" id=\"startdate\" onClick=\"cal1x.select(document.forms[0].startdate,'anchor1x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor1x\" id=\"anchor1x\">a</a></td>
				<td class=\"centeredcellbold\">End Date </td>
				<td class=\"centeredcellbold\"><input type=\"text\" name=\"enddate\" value=\"".$end_date."\" size=\"25\" id=\"enddate\" onClick=\"cal1x.select(document.forms[0].enddate,'anchor2x','yyyy-MM-dd'); return false;\"><a style=\"visibility:hidden;\" name=\"anchor2x\" id=\"anchor2x\">a</a></td>
			
			</tr>
			<tr>
			
				<td colspan=4 class=\"header\">Blackout Comment</td>
				
			</tr>
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\"><textarea cols=40 rows=5 name=\"comment\">".$blackout['comments']."</textarea></td>
				
			</tr>
			<tr>
			
				<td colspan=4 class=\"centeredcellbold\"><input type=\"submit\" value=\"Save\"></td>
				
			</tr>
			
		</form>
		</table><DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;\"></DIV>
	
	</center>

	";

}
else{

	echo "

	<center><h3>Manage Blackouts</h3>".$message."</center>
	<center><form action=\"./index.php?pageid=editblackout\" method=\"POST\">
	
		<select name=\"bid\" size=5>
			".$blackout_select."
		</select>
		<br><input type=\"button\" value=\"Create Blackout\" onClick=\"window.location = './index.php?pageid=newblackout'\"><input type=\"submit\" value=\"Edit\">
	</form></center><br>

	";

}

?>