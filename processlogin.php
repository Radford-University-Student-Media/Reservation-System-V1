<?php

session_start();

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require 'functions.php';

$id = $_POST['id'];
$password = $_POST['pass'];

$userq = processLogin($id, $password);

$error = 0;

$user = null;

if(getConfigVar('use_ldap')){

	if(mysql_num_rows($userq) == 0){

		$error = RES_ERROR_LOGIN_USER_PASS;

	}else{

		$user = mysql_fetch_assoc($userq);

	}

}else{

	if(mysql_num_rows($userq) == 0){

		$error = RES_ERROR_LOGIN_NO_USER;

	}else{

		$user = mysql_fetch_assoc($userq);

		if($user['password'] != encrypt($password)){

			$error = RES_ERROR_LOGIN_USER_PASS;

		}

	}

}

$page = "";

if($error > 0){

	sleep(1);
	$page = "Location: ".getConfigVar("location")."index.php?pageid=login&loginoption=".$error;

}
else{

	setSessionVariable('user_level', $user['user_level']);

	setSessionVariable('user_id', $user['user_id']);

	sleep(1);

	$page = "Location: ".getConfigVar("location")."index.php?pageid=home&sesid=".session_id();

}

if(strpos($_POST['redir'], "logout") === false && strpos($_POST['redir'], "login") === false){
	header("Location: ".$_POST['redir']);
}else{
	header($page);
}

?>