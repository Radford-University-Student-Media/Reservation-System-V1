<?php

if($pageid == "warnuser"){

	$user = mysql_fetch_assoc(getUserByID($_GET['user_id']));

	echo "

		<center><h3>Warn ".$user['name']."</h3></center>

		<form action=\"./index.php?pageid=submitwarning\" method=\"POST\">
		<input type=\"hidden\" name=\"user_id\" value=\"".$_GET['user_id']."\">
			<table class=\"warning\">
			
				<tr>
				
					<td colspan=2 class=\"centeredcellbold\">Warn Reason</td>
					
				</tr>
				
				<tr>
				
					<td colspan=2 class=\"centeredcellbold\"><textarea cols=\"55\" rows=\"7\" name=\"reason\"></textarea></td>
				
				</tr>
				
				<tr>
				
					<td class=\"centeredcell\"><select name=\"type\"><option value=\"1\">Active</option><option value=\"2\">Notification</option><option value=\"3\">Inactive</option></select></td>
					<td class=\"centeredcell\"><input type=\"submit\" value=\"Warn\"></textarea></td>
				
				</tr>
			
			</table>
		
		</form>

	";
}
else if($pageid == "submitwarning"){

	warnUser($_POST['user_id'],$_POST['reason'],$_POST['type']);

	$user = mysql_fetch_assoc(getUserByID($_POST['user_id']));

	echo "<center><h3>".$user['name']." Warned</h3><a href=\"./index.php?pageid=edituser&user=".$user['user_id']."\">View User</a></center>";

}
else if($pageid == "viewwarnings"){

	if(getSessionVariable('user_level') < getConfigVar("admin_rank") && getSessionVariable('user_id') != $_GET['user_id']){

		echo "<center><h3><font color=\"#FF0000\">Error: You are not authorized to view other user's warnings.</font></h3></center>";

	}
	else{

		$warnings = getWarningsForUser($_GET['user_id']);
		$user = mysql_fetch_assoc(getUserByID($_GET['user_id']));
		$options = "";

		while($row = mysql_fetch_assoc($warnings)){

			$options = $options."<option value=\"".$row['warn_id']."\">".$row['time']." - ".getWarningType($row['type'])."</option>";

		}

		echo "<center><h3>View Warnings For ".$user['name']."</h3>";

		if($options != ""){
			echo "<form action=\"index.php\" method=\"GET\">
			<input type=\"hidden\" name=\"pageid\" value=\"editwarning\">
			<select name=\"warn_id\">".$options."</select><input type=\"submit\" value=\"View\"></form></center>";
		}
		else{

			echo "<h4>User has no warnings.</h4>";

		}

	}

}
else if($pageid == "editwarning" || $pageid == "savewarning"){

	$message = "";

	if($pageid == "savewarning"){

		saveWarning($_POST['warn_id'], $_POST['reason'], $_POST['type']);

		$warning = mysql_fetch_assoc(getWarningByID($_POST['warn_id']));

		$message = "<font color=\"#008800\"><b>Warning Saved</b></font><br><br>";

	}else{

		$warning = mysql_fetch_assoc(getWarningByID($_GET['warn_id']));

	}
	$user = mysql_fetch_assoc(getUserByID($warning['user_id']));
	$selected = array(RES_WARNING_ACTIVE => "",RES_WARNING_NOTE => "",RES_WARNING_INACTIVE => "");
	$selected[$warning['type']] = "SELECTED";

	echo "<center><h3>Edit Warning For ".$user['name']."</h3>".$message."</center>
	<form action=\"./index.php?pageid=savewarning\" method=\"POST\">
		<input type=\"hidden\" name=\"warn_id\" value=\"".$warning['warn_id']."\">
			<table class=\"warning\">
			
				<tr>
				
					<td colspan=2 class=\"centeredcellbold\">Warn Reason</th>
					
				</tr>
				
				<tr>
				
					<td colspan=2 class=\"centeredcell\"><textarea cols=\"55\" rows=\"7\" name=\"reason\">".$warning['reason']."</textarea></td>
				
				</tr>
				
				<tr>
				
					<td class=\"centeredcell\"><select name=\"type\"><option value=\"".RES_WARNING_ACTIVE."\" $selected[1]>Active</option><option value=\"".RES_WARNING_NOTE."\" $selected[2]>Notification</option><option value=\"".RES_WARNING_INACTIVE."\" $selected[3]>Inactive</option></select></td>
					<td class=\"centeredcell\"><input type=\"submit\" value=\"Save\"></textarea></td>
				
				</tr>
			
			</table>
		
		</form>
	</center>";


}
?>