<?php

require "functions.php";

$equipid = $_GET['equipid'];
$equip = mysql_fetch_assoc(getEquipmentByID($equipid));
$sched = getReservationsByEquipID($equipid,10);

?>

<html>

<head>

<LINK REL=StyleSheet HREF="./style.css" TYPE="text/css">

<title><?php echo $equip['name']; ?> Schedule</title>

</head>

<body>

	<center>
		<h3>
			
		<?php echo $equip['name']; ?>
			Schedule
		</h3>
	</center>

	<table class="viewsched">

		<tr>

			<td class="header">Chech-out Date</td>
			<td class="header">Due Date</td>

		</tr>
		
		
		
		
		
		<?php
		
		while($row = mysql_fetch_assoc($sched)){
		
			echo "
			<tr>
		
				<td class=\"centeredcell\">".$row['start_date']."</td>
				<td class=\"centeredcell\">".$row['end_date']."</td>
		
			</tr>
			";
		
		}
		
		?>
	
	</table>

</body>

</html>
