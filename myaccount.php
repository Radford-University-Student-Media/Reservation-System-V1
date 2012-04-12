<?php

$message = "";

$user = mysql_fetch_assoc(getUserByID(getSessionVariable('user_id')));

if($pageid == "savepassword"){

	$curpass = $_POST['curpass'];
	$newpass = $_POST['newpass'];
	$confpass = $_POST['confpass'];

	if($curpass != "" && $newpass != "" && $confpass != ""){

		if(encrypt($curpass) == $user['password']){

			if($newpass == $confpass){
					
				changeUserPassword(getSessionVariable('user_id'), $newpass);

				$user = mysql_fetch_assoc(getUserByID(getSessionVariable('user_id')));
					
				$message = "<font color=\"#005500\"><b>Password Updated!</b></font><br><br>";
					
			}
			else{
					
				$message = "<font color=\"#FF0000\"><b>Error: The New Passwords Don't Match</b></font><br><br>";
					
			}

		}
		else{
				
			$message = "<font color=\"#FF0000\"><b>Error: Current Password Incorrect</b></font><br><br>";

		}


	}else{

		$message = "<font color=\"#005500\"><b>Error: A Required Field Was Left Blank</b></font><br><br>";

	}

}
else if($pageid == "saveemail"){

	$email = $_POST['email'];

	if($email != ""){

		changeUserEmail(getSessionVariable('user_id'), $email);

		$user = mysql_fetch_assoc(getUserByID(getSessionVariable('user_id')));

		$message = "<font color=\"#005500\"><b>Email Updated!</b></font><br><br>";

	}else{

		$message = "<font color=\"#FF0000\"><b>Error: Email Field Was Left Blank</b></font><br><br>";

	}

}

$passwordRows = "";

if(!getConfigVar("use_ldap")){
	$passwordRows = "<tr>
		
			<td class=\"centeredcellbold\">Change Password</td>
			<td class=\"centeredcellbold\">Current Password</td>
			<td class=\"centeredcellbold\">New Password</td>
			<td class=\"centeredcellbold\">Confirm Password</td>
	
		</tr>
	
		<tr>
		
			<form action=\"./index.php?pageid=savepassword\" method=\"POST\"><td class=\"centeredcellbold\"><input type=\"submit\" value=\"Save Password\"></td>
			<td class=\"centeredcell\"><input type=\"password\" name=\"curpass\"></td>
			<td class=\"centeredcell\"><input type=\"password\" name=\"newpass\"></th>
			<td class=\"centeredcell\"><input type=\"password\" name=\"confpass\"></td></form>
	
		</tr>";
}

echo "
	<center><h3>My Account</h3>".$message."</center>
	
	<table class=\"myaccount\">
	
		<tr>
		
			<td colspan=4 class=\"header\">Edit User Information</td>
		
		</tr>
		
		<tr>

			<td class=\"centeredcellbold\">Username</td>
			<td colspan=3 class=\"centeredcell\">".$user['username']."</td>
			
		</tr>
	
		<tr>

			<td class=\"centeredcellbold\">Name</th>
			<td colspan=3 class=\"centeredcell\">".$user['name']."</td>
			
		</tr>
	
		".$passwordRows."
	
		<tr>
			
			<form action=\"./index.php?pageid=saveemail\" method=\"POST\">
			<td colspan=1 class=\"centeredcellbold\">Email</th><td colspan=3 class=\"centeredcell\"><input type=\"text\" name=\"email\" size=30 value=\"".$user['email']."\"><input type=\"submit\" value=\"Save Email\"></td></form>
				
		</tr>

		<tr>
		
			<td colspan=1 class=\"centeredcellbold\">Warnings</th>
			<td class=\"centeredcellbold\" colspan=3><a href=\"./index.php?pageid=viewmywarnings\">".mysql_num_rows(getActiveWarningsForUser($user['user_id']))."(".mysql_num_rows(getWarningsForUser($user['user_id'])).")</a></td>
		
		</tr>
			
	</table>";

?>