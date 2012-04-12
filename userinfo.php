<?php

session_start();

require 'functions.php';

$user_id = $_GET['user_id'];

$user = mysql_fetch_assoc(getUserByID($user_id));

?>

<html>

<head>

<LINK REL=StyleSheet HREF="./style.css" TYPE="text/css">
<title>User Info</title>

</head>

<body>

	<center>
		<h3>View User</h3>
	</center>

	<table class="userinfo">

		<tr>

			<td colspan=4 class="header">User Info for <?php echo $user['name']; ?>
			</td>

		</tr>
		<tr>

			<td class="centeredcellbold">Name</t>
			
			<td class="centeredcell"><?php echo $user['name']; ?></td>

			<td class="centeredcellbold">ID Num
			
			</th>
			<td class="centeredcell"><?php echo $user['username']; ?></td>

		</tr>

		<tr>

			<td colspan=1 class="centeredcellbold">Email
			
			</th>
			<td colspan=3 class="centeredcell"><?php echo $user['email']; ?></td>

		</tr>

		<tr>

			<td class="centeredcellbold">User Level</td>
			<td class="centeredcell"><?php echo $user['user_level']; ?></td>

			<td class="centeredcellbold">Warnings</td>
			<td class="centeredcell"><?php echo "".mysql_num_rows(getActiveWarningsForUser($user['user_id']))."(".mysql_num_rows(getWarningsForUser($user['user_id'])).")"; ?>
			</td>

		</tr>

		<tr>

			<td colspan=4 class="centeredcellbold">Admin Notes</td>

		</tr>

		<tr>

			<td colspan=4 class="centeredcell"><textarea cols=60 rows=8 disabled>
				
			<?php echo $user['notes']; ?></textarea></td>

		</tr>

	</table>

</body>

</html>
