<?php

	$result = doQuery("SELECT * FROM resldap_users");
	while($row = mysql_fetch_assoc($result)){
	
		echo $row['email']."; ";
	
	}

?>