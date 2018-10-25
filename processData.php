<?php
	include_once "users.php";
	include_once "contests.php";
	if (isset($_GET['contest']))
		updateContests();
	$users = getUsers();
	foreach ($users as $user)
	{
		echo "Updating ".$user->getName()."<br />";
		flush();
		$user->update();
	}
	
?>
