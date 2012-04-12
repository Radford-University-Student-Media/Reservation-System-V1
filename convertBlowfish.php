<?php

/*


Radford Reservation System
Author: Andrew Melton

Filename: convertBlowfish.php

Purpose:
This file is used to convert from the old encryption scheme
(DES) to a newer, more secure scheme (BLOWFISH). It gets all
of the users from the database, re-encrypts the passwords
with the new encrypt function, then updates the database.

The reason for this change would be the recent (as of December
2010) compromise of the GAWKER user database. As I was reading
about the attack, one of the reasons it was so easy was that
the passwords were encrypted with DES, which is what I first used.
Apparently, DES only encrypts the first 8 characters, the rest
is truncated. So, I decided in the name of security, we needed
a better scheme...

Known Bugs/Fixes:

None

*/

/*---------------------
 require 'functions.php';

function updateUser($userid, $oldPass){

$newpass = makeMySQLSafe(encrypt(oldDecrypt($oldPass)));

doQuery("UPDATE ".getDBPrefix()."_users SET password = '".$newpass."'  WHERE user_id = ".$userid."");

}

$result = doQuery("SELECT * FROM ".getDBPrefix()."_users");

while($row = mysql_fetch_assoc($result)){

updateUser($row['user_id'], $row['password']);

}
----------*/
?>