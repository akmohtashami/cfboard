<?php
	include "db.php";
	include "config.php";
	$db = new Database(DBNAME, DBHOST, DBUSER, DBPASS);
	$startSessionTime = $db->select("logInfo", array("startTime"), array("id" => LOGID))[0]["startTime"];
?>

