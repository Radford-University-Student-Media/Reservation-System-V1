<?php

//$userid = 1;

if(isset($_GET['user'])){

	$userid = $_GET['user'];

}
else if(isset($_POST['userid'])){

	$userid = $_POST['userid'];

}

$users = "";

$result = getAllUsersOrderByName();

$message = "";

if($pageid == "adminsavepassword"){

	if($_POST['newpass'] == $_POST['confpass']){

		changeUserPassword($userid, $_POST['newpass']);

	}
	else{

		$message = "Error: Passwords Don't Match<br><br>";

	}

}
else if($pageid == "adminsaveemail"){

	changeUserEmail($userid, $_POST['email']);
	$message = "User Saved!<br><br>";

}
else if($pageid == "adminsavelevel"){

	changeUserLevel($userid, $_POST['level']);
	$message = "User Saved!<br><br>";

}
else if($pageid == "adminsavenotes"){

	changeUserNotes($userid, $_POST['notes']);
	$message = "User Saved!<br><br>";

}

if($pageid == "edituser" || $pageid == "adminsavepassword" || $pageid == "adminsaveemail" || $pageid == "adminsavelevel"){

	$sel = "";

}else{

	$sel = " SELECTED";

}

while($row = mysql_fetch_assoc($result)){

	if(isset($userid) && $row['user_id'] == $userid){

		$sel = " SELECTED";

	}

	$users = $users . "<option value=\"".$row['user_id']."\"$sel>".$row['name']."</option>";
	$sel = "";
}

if($pageid == "edituser" || $pageid == "adminsavepassword" || $pageid == "adminsaveemail" || $pageid == "adminsavenotes" || $pageid == "adminsavelevel"){

	echo "<center><h3>Manage Users</h3></center>
<center><form action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"pageid\" value=\"edituser\"><select name=\"user\">
".$users."
</select><input type=\"submit\" value=\"Edit\"></form>".$message."</center>";

	$user = mysql_fetch_assoc(getUserByID($userid));

	$passwordRows = "";
	
	if(!getConfigVar("use_ldap")){
	
		$passwordRows = "<tr>
		
			<td class=\"centeredcellbold\">Change Password</td>
			<td class=\"centeredcellbold\">New Password</td>
			<td class=\"centeredcellbold\">Confirm Password</td>
			<td class=\"centeredcellbold\">--</td>
	
		</tr>
	
		<tr>
		
			<form action=\"./index.php?pageid=adminsavepassword\" method=\"POST\"><td class=\"centeredcellbold\">--</td>
			<td class=\"centeredcell\"><input type=\"hidden\" name=\"userid\" value=\"".$user['user_id']."\"><input type=\"password\" name=\"newpass\"></td>
			<td class=\"centeredcell\"><input type=\"password\" name=\"confpass\"></th>
			<td class=\"centeredcell\"><input type=\"submit\" value=\"Save Password\"></td></form>
	
		</tr>";
	}
	
	echo "<table class=\"userinfo\">
	
		<tr>
		
			<td colspan=4 class=\"header\">User Information</td>
		
		</tr>
	
		<tr>

			<td class=\"centeredcellbold\">ID Number</th>
			<td colspan=3 class=\"centeredcell\">".$user['username']."</td>
			
		</tr>
	
		<tr>

			<td class=\"centeredcellbold\">Name</td>
			<td colspan=3 class=\"centeredcell\">".$user['name']."</td>
			
		</tr>
	
		".$passwordRows."
	
		<tr>
			
			<td colspan=1 class=\"centeredcellbold\">Email</td>
			<form action=\"./index.php?pageid=adminsaveemail\" method=\"POST\"><td colspan=3 class=\"centeredcell\"><input type=\"hidden\" name=\"userid\" value=\"".$user['user_id']."\"><input type=\"text\" name=\"email\" size=30 value=\"".$user['email']."\"><input type=\"submit\" value=\"Save Email\"></td></form>
				
		</tr>
	
		<tr>
			
			<td colspan=1 class=\"centeredcellbold\">Userlevel</td>
			<form action=\"./index.php?pageid=adminsavelevel\" method=\"POST\"><td colspan=3 class=\"centeredcell\">
			<input type=\"hidden\" name=\"userid\" value=\"".$user['user_id']."\">".getUserLevelDropDownSelected("level",$user['user_level'])."<input type=\"submit\" value=\"Save Level\"></td>
			</form>
				
		</tr>

		<tr>
		
			<td colspan=1 class=\"centeredcellbold\">Warnings</td>
			<td class=\"centeredcell\" colspan=2><a href=\"./index.php?pageid=viewwarnings&user_id=".$user['user_id']."\">".mysql_num_rows(getActiveWarningsForUser($user['user_id']))."(".mysql_num_rows(getWarningsForUser($user['user_id'])).")</a></td>
			<td class=\"centeredcell\" colspan=1><a href=\"./index.php?pageid=warnuser&user_id=".$user['user_id']."\">Warn User</a></td>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"centeredcellbold\">User Notes</th>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"centeredcellbold\"><br><form action=\"./index.php?pageid=adminsavenotes\" method=\"POST\"><input type=\"hidden\" name=\"userid\" value=\"".$user['user_id']."\"><textarea cols=60 rows=8 name=\"notes\">".$user['notes']."</textarea><br><input type=\"submit\" value=\"Save Notes\"</form></th>
		
		</tr>
			
	</table>";


}
else{

	echo "<center><h3>Manage Users</h3></center>
	<center><form action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"pageid\" value=\"edituser\"><select name=\"user\" size=10>
	".$users."
	</select><br><input type=\"button\" value=\"Create User\" onClick=\"window.location = './index.php?pageid=newuser'\"><input type=\"submit\" value=\"Edit\"></form></center>";


}

?>