<?php

ini_set('SMTP', 'localhost');
ini_set('smtp_port', '25');

/*

'location' : Full webaddress to the equipment reservation system.
ex- 'http://www.example.com/res/'

'pageheader' : Title displayed on the browser for each page.
ex- 'Radford Reservation System'

'banner' : Filename for the banner image.
The image should be 750x150px and stored in the root directory of
the reservation system
ex- 'banner.png'

'mysql_server' : IP Address or Hostname of the MySQL server.
ex- '127.0.0.1'

'mysql_user' : Username used to log into the MySQL server.
ex- 'ressys'

'mysql_password' : Passed used to log into the MySQL server.
ex- 'Password!'

'mysql_database' : MySQL database where the tables for the reservation
system are stored.
ex- 'reservationsystem'

'db_prefix' : Prefix used in the table names. This feature is used to
store multiple reservation systems in the same database. Each
reservation system should have it's own unique prefix value.
ex- 'res2'

'BLOWFISH_key' : The key used by the encryption functions to encrypt
the passwords that are stored in the database. The encryption
strength is determined by the key length, for 128 bit encryption
the key should be 16 characters long, for 256 bit encryption the
key should be 32 characters long.
ex (128bit)- '1234567890123456'
ex (256bit)- '1234567890123456ABCDEFGHIJKLMNOP'

'db_error_logging' : (true, false)
Whether or not the system will log errors in the database.

'error_output' : ('none', 'php, 'dbid', 'both')
These values only matter when db_error_logging is set to true,
otherwise php will handle error output.
Values:
* none - No error output
* php - PHP error output only
* dbid - The error_id from database only
* both - Both PHP error output and error_id from the database

'admin_rank' : The userlevel that is assosiated with admin users. This
doesn't need to be changed unless there are going to be greater than
5 user levels that rank below admins. Values for this could be as
low as 2 but that is not suggested because once set, 'admin_rank'
should not be changed.
ex- '6'

*/

$config = array(

'maint_mode' => false,

'location' => 'http://www.domain.com/res/',

'pageheader' => 'DEV Reservation System',

'banner' => 'banner.png',

'equipment_types' => array(
						'Geology', 'Geospatial', 'Physics', 'Anthropology', 'Forensics Institute'
),

'mysql_server' => 'localhost',

'mysql_user' => 'user',

'mysql_password' => 'password',

'mysql_database' => 'database',

'smtp_server' => 'ssl://smtp.gmail.com',

'smtp_port' => '465',

'smtp_user' => 'username',

'smtp_email' => 'email@domain.com',

'smtp_password' => 'emailPassword',

'db_prefix' => 'res',

'db_error_logging' => true,

'error_output' => 'both',

'BLOWFISH_key' => '1234567890123456',

'moderator_rank' => '3',

'admin_rank' => '6',

'use_ldap' => true,

'ldap_domain' => 'mydomain.com',

'ldap_server' => 'ldapserveraddress'

);

?>
