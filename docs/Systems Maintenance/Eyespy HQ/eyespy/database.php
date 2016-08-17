<?php

$mysqli = new mysqli("argentetpierrescom.ipagemysql.com", "datamaster", "test0r!", "eyespyhq");
if($mysqli->connect_errno) 
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;


?>