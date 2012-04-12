<?php

function db_error_logger($errno, $errstr, $errfile = "", $errline = "", $errorcontext = array()){

	$errno = makeStringSafe($errno);
	$errstr = makeStringSafe($errstr);
	$errfile = makeStringSafe($errfile);
	$errline = makeStringSafe($errline);

	if($errno < E_STRICT && $errno != E_WARNING){

		doQuery("INSERT INTO ".getDBPrefix()."_error_log set user_id = '".getSessionVariable("user_id")."', error_number = '".$errno."',
				message = '".$errstr."', file = '".$errfile."', line_number = '".$errline."', context = '".serialize($errorcontext)."',
				time = '".getCurrentMySQLDateTime()."'");

		$errorrow = mysql_fetch_assoc(doQuery("SELECT error_id FROM ".getDBPrefix()."_error_log ORDER BY error_id DESC LIMIT 1"));
			
		if(getConfigVar('error_output') == ERROR_OUTPUT_DBID || getConfigVar('error_output') == ERROR_OUTPUT_BOTH){
				
			echo "<h4 style=\"color: #FF0000;\">An error occured! If you would like to report this error, please report that your 'ERROR_ID' is '".$errorrow['error_id']."'.</h4>";
				
		}

	}

	return !(getConfigVar("error_output") == ERROR_OUTPUT_PHP || getConfigVar("error_output") == ERROR_OUTPUT_BOTH);

}

?>