<?php

if(issetSessionVariable('user_level')){

	if(getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN){



	}
	else{

		die("Error: You don't have permissions to access this page!");

	}

}
else{

	die("Error: You don't have permissions to access this page!");

}

$message = "";

$equipid = 0;

if($pageid == "saveequip"){

	require 'adminfunctions.php';

	saveEquipment($_POST['equipid'],$_POST['name'],$_POST['type'],$_POST['serial'],$_POST['max'],$_POST['minuserlevel'],$_POST['checkoutfrom'],$_POST['description']);

	$equipid = $_POST['equipid'];

	$message = "<font color=\"#005500\"><b>Successfully saved this equipment!</b><br><br></font>";

}

if($equipid == 0){
	$equipid = $_POST['selector'];
}

$equip = mysql_fetch_assoc(getEquipmentByID($equipid));

$users = "<select name=\"checkoutfrom\"><option value=\"-1\">None</option>";

$userresult = getAllUsersOrderByName();

while($row = mysql_fetch_assoc($userresult)){

	$selected = false;

	if($row['user_id'] == $equip['checkoutfrom']){

		$users = $users . "<option value=\"".$row['user_id']."\" SELECTED>".$row['name']."</option>";

	}else{

		$users = $users . "<option value=\"".$row['user_id']."\">".$row['name']."</option>";

	}

}

echo "<center><h3>Edit Equipment</h3>".$message."</center>

	<form enctype=\"multipart/form-data\" action=\"./index.php?pageid=saveequip\" method=\"post\">
	<input type=\"hidden\" name=\"equipid\" value=\"".$equipid."\">
	<table class=\"newequip\">
	
		<tr>
		
			<td colspan=4 class=\"header\">Equipment Information</td>
		
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Name</td>
			<td colspan=2 class=\"centeredcell\"><input type=\"text\" size=30 name=\"name\" value=\"".$equip['name']."\"></td>
			
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Equipment Type</td>
			<td colspan=2 class=\"centeredcell\">".getEquipmentTypesDropDownSelected("type", 1, $equip['type'])."</td>
			
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Primary Serial Number</td>
			<td colspan=2 class=\"centeredcell\"><input type=\"text\" size=30 name=\"serial\" value=\"".$equip['serial']."\"></td>
			
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Checkout From</td>
			<td colspan=2 class=\"centeredcell\">".$users."</td>
			
		</tr>
		
		<tr>
		
			<td class=\"centeredcellbold\">Max Length (days)</td>
			<td class=\"centeredcell\"><input type=\"text\" size=4 name=\"max\" value=\"".$equip['max_length']."\"></td>
			<td class=\"centeredcell\"><b>Minimum User Level</b></td>
			<td class=\"centeredcell\">".getUserLevelDropDownSelected("minuserlevel", $equip['min_user_level'])."</td>
			
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"header\">Equipment Description</td>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"centeredcell\"><textarea cols=50 rows=10 name=\"description\">".$equip['description']."</textarea></td>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"centeredcellbold\"><input type=\"submit\" value=\"save\"></td>
		
		</tr>
	
	</table>
	
	</form>";

?>