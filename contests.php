<?php
	include_once "cfapi.php";
	include_once "init.php";
	function validateContest($id)
	{
		global $db;
		return count($db->select("contests", array("id"), array("id" => $id))) > 0;
	}
	function updateContests()
	{
		global $db;
		$pURL = "http://codeforces.com/api/contest.list";
		$ans = CFAPI::getResult($pURL);
		foreach ($ans as $contest)
			if ($contest["type"] == "CF" && strpos($contest["name"], "Div. 1") === false)
				$db->insert("contests", array("id" => $contest["id"]), true);
	}

?>
