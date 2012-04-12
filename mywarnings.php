<?php

$userid = getSessionVariable('user_id');
$warnings = getWarningsForUser($userid);
$user = mysql_fetch_assoc(getUserByID($userid));
$options = "";
$types = array(RES_WARNING_ACTIVE => "Active", RES_WARNING_NOTE => "Note", RES_WARNING_INACTIVE => "Inactive");

while($row = mysql_fetch_assoc($warnings)){

	$options = $options."<option value=\"".$row['warn_id']."\">".$row['time']." - ".$types[$row['type']]."</option>";

}

echo "<center><h3>View Warnings For ".$user['name']."</h3>";

if($options != ""){
	echo "<form action=\"index.php\" method=\"GET\">
		<input type=\"hidden\" name=\"pageid\" value=\"viewmywarning\">
		<select name=\"warn_id\">".$options."</select><input type=\"submit\" value=\"View\"></form>";
}
else{

	echo "<h4>You don't have any warnings. :)</h4>";

}

if($pageid == "viewmywarning"){

	$warning = mysql_fetch_assoc(getWarningByID($_GET['warn_id']));

	echo "<table class=\"warning\">
			<tr>
			
				<td colspan=2 class=\"centeredcellbold\">Warning Reason</td>
			
			</tr>
			
			<tr>
			
				<td colspan=2 class=\"centeredcell\"><textarea cols=\"55\" rows=\"7\" readonly>".$warning['reason']."</textarea></td>
			
			</tr>
			
			<tr>
			
				<td class=\"centeredcellbold\">Type: ".getWarningType($warning['type'])."</td>
				<td class=\"centeredcellbold\">Time: ".$warning['time']."</td>
			
			</tr>
		
		</table>";

}

echo "</center>";

?>