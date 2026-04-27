<?php
###Connect to database###
$dbhost = "db";
$dbname = "brotherhood";
$dbuser = "username";
$dbpass = "password";

#
# On Server: username/dbname: socialnetdatab
# Password: Social-net-db1 
#

$conc= mysql_connect ($dbhost, $dbuser, $dbpass) or die("Unable to connect to MySQL");
mysql_select_db($dbname) or die("Unable to connect to $dbname");
?>