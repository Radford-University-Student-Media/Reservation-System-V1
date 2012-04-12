body{ text-align: center; background-color: #B9FFE0; } table.main{

width: 750px; margin-left: auto; margin-right: auto; } th.banner{

height: 125px; } td.navi{ text-align: center; } td.admin{ text-align:
center; } td.content{ height: 300px; text-align: left; vertical-align:
top; }

<?php

$page = $_GET['page'];

if($page == "home" || $page == "login"){
	echo "
		table.myequip{
		
			width: 600px;
		
		}
		
		td.myequipPending{
		
			text-align: center;
		
		}
		
		td.myequipApproved{
		
			text-align: center;
			font-weight: bold;
			color: #005500;
		
		}
		
		td.myequipChecked-In{
		
			text-align: center;
			font-weight: bold;
			color: #005500;
		
		}
		
		td.myequipDenied{
		
			text-align: center;
			font-weight: bold;
			color: #FF0000;
		
		}";

}
else if($page == "addequip" || $page == "finishaddequip"){

	echo "
		
		div.form{
		
			width:100%;
			text-align: center;
		
		}";

}
else if($page == "makeres" || $page == "finishmakeres" || $page == "reservation" || $page == "finishres"){

	echo "
		
		div.form{
		
			width:100%;
			text-align: center;
		
		}
		
		table.equipinfo{

			width: 75%;
			margin-left: auto;
			margin-right: auto;
		
		}
		
		td.info{
		
			text-align: center;
		
		}";

}
else if($page == "ourequip"){

	echo "
		table.ourequip{
		
			width: 500px;
		
		}
		
		td.ourequip{
		
			text-align: center;
		
		}";

}
else if($page == "moreinfo"){

	echo "
		table.equipinfo{
		
			width: 600px;
		
		}
		
		td.info{
		
			text-align: center;
		
		}";

}
else if($page == "viewreservation" || $page == "editreservation"){

	echo "
		table.info{
		
			width: 600px;
			margin-left: auto;
			margin-right: auto;
		
		}
		
		td.info{
		
			text-align: center;
		
		}";

}
else if($page == "newuser" || $page == "finishnewuser"){

	echo "
		table.usertable{
		
			width: 600px;
			margin-left: auto;
			margin-right: auto;
		
		}
		
		td.info{
		
			text-align: center;
		
		}";

}
else if($page == "myaccount" || $page == "savepassword" || $page == "saveemail" || $page == "edituser" || $page == "adminsavepassword" || $page == "adminsaveemail" || $page == "adminsavenotes"){

	echo "
		div.form{
		
			width:100%;
			text-align: center;
		
		}
		
		table.equipinfo{
		
			width: 600px;
			margin-left: auto;
			margin-right: auto;
		
		}
		
		td.info{
		
			text-align: center;
		
		}";

}
else if($page == "browseres"){

	echo "

		table.browse{width: 85% ; margin-left: auto; margin-right: auto;}

		td.info{text-align: center;}
		
		";

}

?>