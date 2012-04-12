<?php

/*

Radford Reservation System
Author: Andrew Melton

Filename: /lib/email_functions.php

Purpose:
This file contains all functions related to emails... Right now
A good portion of this file is hard-coded to use my personal
gmail. Eventaully, I would like to switch this to either using
a gmail account for just this or to using the universities mail
system.

Known Bugs/Fixes:

None

*/

/*

Sends the user an email updating them about their reservation.

*/

function sendReservationNoticeToUser($email, $resid, $status, $adminMessage){

	$message = "Your reservation's status has been updated to: ".getStatusString($status).". To view your reservation please visit this address: ".getConfigVar("location")."index.php?pageid=viewreservation&&resid=".$resid."";

	if($adminMessage != ""){

		$message = "Your reservation's status has been updated to: ".getStatusString($status)." and the admin commented:\n\n".$adminMessage."\n\nTo view your reservation please visit this address: ".getConfigVar("location")."index.php?pageid=viewreservation&&resid=".$resid."";

	}

	$subject = "Reservation Status Update";
	$headers = 'From: '.getConfigVar('smtp_email'). "\r\n" .
    'Reply-To: '.getConfigVar('smtp_email') . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	//sendMail(getConfigVar('smtp_email'),$email, $subject, $message);
	mail($email, $subject, $message);

}

/*

Sends all of the admins an email notifying them of a new reservation.

*/

function sendReservationNoticeToAdmins($resid){

	$message = "New reservation pending approval. ".getConfigVar("location")."confirmReservation.php?resid=".$resid."";
	$subject = "New Reservation Notice";
	$headers = 'From: '.getConfigVar('smtp_email'). "\r\n" .
    'Reply-To: '.getConfigVar('smtp_email') . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	$admins = getAllUsersByUserLevel(getConfigVar("admin_rank"));

	while($row = mysql_fetch_assoc($admins)){

		//sendMail(getConfigVar('smtp_email'),$row['email'], $subject, $message);
		mail($row['email'], $subject, $message);

	}

}

/*

Sends all of the admins an email notifying them of a new reservation.

*/

function sendNewUserNoticeToAdmins($userid){

	$message = "New user pending approval. ".getConfigVar("location")."index.php?pageid=edituser&user=".$userid."";
	$subject = "New User Notice";
	$headers = 'From: '.getConfigVar('smtp_email'). "\r\n" .
    'Reply-To: '.getConfigVar('smtp_email') . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	$admins = getAllUsersByUserLevel(getConfigVar("admin_rank"));

	while($row = mysql_fetch_assoc($admins)){

		//sendMail(getConfigVar('smtp_email'),$row['email'], $subject, $message);
		mail($row['email'], $subject, $message);

	}

}

function sendWarningNotice($userid, $reason, $type){

	$subject = "Reservation System Warning";
	$message = "You have been given a(n) ".getWarningType($type).". The reason given was: ".$reason;
	$headers = 'From: '.getConfigVar('smtp_email'). "\r\n" .
    'Reply-To: '.getConfigVar('smtp_email') . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	$user = mysql_fetch_assoc(getUserByID($userid));

	//sendMail(getConfigVar('smtp_email'), $user['email'], $subject, $message);
	mail($row['email'], $subject, $message);
}

/*

This is used when ever email is sent.

*/

/*function sendMail($sender, $rec, $subj, $bod){

	require_once "Mail.php";

	$from = "<$sender>";
	$to = "<$rec>";
	$subject = $subj;
	$body = $bod;

	$host = getConfigVar('smtp_server');
	$port = getConfigVar('smtp_port');
	$username = getConfigVar('smtp_user');
	$password = getConfigVar('smtp_password');

	$headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
	$smtp = Mail::factory('smtp',
	array ('host' => $host,
     'port' => $port,
     'auth' => true,
     'username' => $username,
     'password' => $password));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
		echo("<p>" . $mail->getMessage() . "</p>");
	} else {
		#echo("<p>Message successfully sent!</p>");
	}

}*/

?>