<?php

$equipid = $_GET['equipid'];

$equip = mysql_fetch_assoc(getEquipmentByID($equipid));

$page = "<center><h3>Equipment Info</h3></center>
	<table class=\"equipinfo\">
	
		<tr>
		
			<td colspan=2 class=\"header\">".$equip['name']."</td>
		
		</tr>
	
		<tr>
			
			<td class=\"centeredcellbold\">Checkout Length</td>
			<td class=\"centeredcell\">".$equip['max_length']." Day(s) Max</td>
		
		</tr>
		
		<tr>
		
			<td class=\"centeredcell\"><img src=\"./getpicture.php?equip=".$equipid."\"</td>
			<td class=\"topaligncell\">".$equip['description']."</td>
				
		</tr>
			
		<tr>
		
			<td class=\"centeredcell\"><a href=\"./viewsched.php?equipid=".$equip['equip_id']."\" target=\"_blank\">View Schedule</a></td>
			<td class=\"centeredcell\"><a href=\"./index.php?pageid=reservation&equipid=".$equip['equip_id']."\">Reserve</a></td>
			
		</tr>
					
	</table>
";

echo $page;

?>