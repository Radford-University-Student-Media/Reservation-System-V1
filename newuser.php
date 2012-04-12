<?php

$message = "";

if($pageid == "finishnewuser"){

	$stu_id = $_POST['stu_id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$user_level = $_POST['user_level'];



	if(mysql_num_rows(getUserByID($stu_id)) > 0){

		$message = "User with username "+$stu_id+" already exists.";
			
	}else{

		$password = newUserNoPassword($stu_id, $name, $email, $user_level);
		
		if(getConfigVar("use_ldap")){
			$message = "New User Created. Username: ".$stu_id;
		}else{
			$message = "New User Created. Username: ".$stu_id." , Password: ".$password."<br><br>";
		}

	}

}

$page = "";

$page = $page = "<center><h3>Create New User</h3>".$message."</center>
	<form action=\"./index.php?pageid=finishnewuser\" method=\"POST\"><table class=\"newuser\">
		<tr>
		
			<td colspan=4 class=\"header\">User Information</td>
			
		</tr>
		<tr>
		
			<td class=\"centeredcellbold\">Username</td>
			<td class=\"centeredcell\"><input type=\"text\" name=\"stu_id\"></td>
			<td class=\"centeredcellbold\">Name</td>
			<td class=\"centeredcell\"><input type=\"text\" name=\"name\"></td>
		
		</tr>
		<tr>
		
			<td class=\"centeredcellbold\">Email</td>
			<td colspan=3 class=\"centeredcell\"><input type=\"text\" size=\"45\" name=\"email\"></td>
		
		</tr>
		<tr>
		
			<td class=\"centeredcellbold\">User Level</td>
			<td class=\"centeredcell\">".getUserLevelDropDown("user_level")."</td>
			<td colspan=2 class=\"centeredcell\"><input type=\"submit\" value=\"Create!\"></td>
		
		</tr>
	</form></table>";

echo $page;

?>