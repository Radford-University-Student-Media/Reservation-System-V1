<?php

if(isset($_GET['loginoption'])){

	$loginoption = $_GET['loginoption'];

}
else{

	$loginoption= "login";

}

$loginpage = "";
$errormessage = "";

if($loginoption == RES_ERROR_LOGIN_NO_USER){

	$errormessage = "No such username<br><br>";

}
else if($loginoption == RES_ERROR_LOGIN_USER_PASS){

	$errormessage = "Incorrect username or password<br><br>";

}

$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

require 'hourscalendar.php';

$loginpage = $loginpage . "
	
		<center><h3>Welcome!</h3>
		<font color=\"#FF0000\">".$errormessage."</font></center>
	
		<form action=\"./processlogin.php\" method=\"POST\" name=\"loginform\">
		<input type=\"hidden\" name=\"redir\" value=\"$url\">
			<table class=\"login\">
				<tr>
					<td colspan=2 class=\"header\">User Login</td>
				</tr>
				<tr>
					<td class=\"centeredcellbold\">Username</td>
					<td class=\"centeredcell\"><input type=\"text\" name=\"id\"></td>
				</tr>
				<tr>
					<td class=\"centeredcellbold\">Password</td>
					<td class=\"centeredcell\"><input type=\"password\" name=\"pass\"></td>
				</tr>
				<tr>
					<td colspan=2 class=\"centeredcellbold\"><input type=\"submit\" value=\"Login\"></td>
				</tr>
			</table>
		
		</form>
$hourscalendar
	
	</center>

";

echo $loginpage;

?>
