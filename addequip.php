<?php

if(issetSessionVariable('user_level')){

	if(getSessionVariable('user_level') >= RES_USERLEVEL_ADMIN){



	}
	else{

		echo "Error: You don't have permissions to access this page!";
		die("");

	}

}
else{

	echo "Error: You don't have permissions to access this page!";
	die("");

}

$name = "";

if($pageid == "finishaddequip"){

	$name = $_POST['name'];
	$serial = $_POST['serial'];
	$description = $_POST['description'];
	$max = $_POST['max'];
	$minuserlevel = $_POST['minuserlevel'];

}

$message = "";

if($name != "" && $serial != "" && $description != "" && $max != "" && $minuserlevel != ""){

	$target_path = "./pics/";

	$new_name = $serial. "_" . basename( $_FILES['image']['name']);

	$target_path = $target_path .  $new_name;

	if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)){

		$message = "<font color=\"#005500\"><b>Successfully added new equipment!</b><br><br></font>";

		addEquipment($name, $serial, $description, $max, $new_name, $minuserlevel);

	}
	else{

		$message = "There was an error creating the equipment. ("+handleFileError($_FILES['image']['error'])+")";

	}


}

echo "
	<center><h3>Add New Equipment</h3>".$message."</center>

	<form enctype=\"multipart/form-data\" action=\"./index.php?pageid=finishaddequip\" method=\"post\">
	<table class=\"newequip\">
	
		<tr>
		
			<td colspan=4 class=\"header\">Equipment Information</td>
		
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Name</td>
			<td colspan=2 class=\"centeredcell\"><input type=\"text\" size=30 name=\"name\"></td>
			
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Primary Serial Number</td>
			<td colspan=2 class=\"centeredcell\"><input type=\"text\" size=30 name=\"serial\"></td>
			
		</tr>
		
		<tr>
		
			<td class=\"centeredcellbold\">Max Length (days)</td>
			<td class=\"centeredcell\"><input type=\"text\" size=4 name=\"max\"></td>
			<td class=\"centeredcell\"><b>Minimum User Level</b></td>
			<td class=\"centeredcell\">".getUserLevelDropDown("minuserlevel")."</td>
			
		</tr>
		
		<tr>
		
			<td colspan=2 class=\"centeredcellbold\">Image (250x250px)</td>
			<td colspan=2 class=\"centeredcell\"><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"15000000\">
<input type=\"file\" name=\"image\"></td>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"header\">Equipment Description</td>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"centeredcell\"><textarea cols=50 rows=10 name=\"description\"></textarea></td>
		
		</tr>
		
		<tr>
		
			<td colspan=4 class=\"centeredcellbold\"><input type=\"submit\" value=\"Add\"></td>
		
		</tr>
	
	</table>
	
	</form>";

?>