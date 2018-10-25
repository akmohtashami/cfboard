<?php
	include_once "init.php";
	include_once "cfapi.php";
	include_once "contests.php";
	class User
	{
		private $id;
		private $cfid;
		private $fname;
		private $cnt;
		private $lastUpdated;
		public function __construct($uid)
		{
			global $db;
			$objarr = $db->select("users", array("cfid", "fname", "lastUpdated", "problemData"), array("id" => $uid));
			if ($objarr == NULL)
				die("User not found");
			$obj = $objarr[0];
			$this->id = $uid;
			$this->cfid = $obj["cfid"];
			$this->fname = $obj["fname"];
			$this->lastUpdated = $obj["lastUpdated"];
			$this->cnt = json_decode($obj["problemData"], true);
			if (empty($this->cnt))
				$this->cnt = array();
		}
		public function createUser($fname, $cfid)
		{
			global $db;
			$this->id = $db->insert("users", array("fname" => $fname, "cfid" => $cfid));
			if ($this->id == NULL)
				throw "Creating new user failed.";
			$this->fname = $fname;
			$this->cfid = $cfid;
			$this->lastUpdated = 0;
			$this->cnt = array();
		}
		public function getName()
		{
			return $this->fname;
		}
		public function getProblemCnt($problem)
		{
			if (array_key_exists($problem, $this->cnt))
				return count($this->cnt[$problem]);
			else
				return 0;
		}
		private function getSubmissions($t)
		{
			$pURL = "http://codeforces.com/api/user.status?handle=".$this->cfid."&count=100&from=";
			$from = 1;
			$submissions = array();
			do
			{
				$newSubmissions = CFAPI::getResult($pURL.$from);
				if (empty($newSubmissions))
					break;
				foreach ($newSubmissions as $newSubmission)
				{
					$lastSubmissionTime = $newSubmission["creationTimeSeconds"];
					if (validateContest($newSubmission["contestId"]) && $newSubmission["creationTimeSeconds"] > $t && $newSubmission["verdict"] == "OK")
						$submissions[] = $newSubmission;
				}
				$from += 100;
			}while ($lastSubmissionTime > $t);
			return $submissions;
		}
		public function update()
		{
			global $startSessionTime;
			$this->lastUpdated = max($this->lastUpdated, $startSessionTime);
			$submissions = $this->getSubmissions($this->lastUpdated);
			if (empty($submissions))
			{
				$this->commit();
				return;
			}
			foreach ($submissions as $submission)
			{
				$this->lastUpdated = max($this->lastUpdated, $submission["creationTimeSeconds"]);
				$this->cnt[$submission["problem"]["index"]][$submission["problem"]["contestId"]] = 1;
			}
			$this->commit();
		}
		public function calculateScore()
		{
			global $problems;
			$ans = 0;
			foreach ($problems as $problem => $score)
				$ans += $this->getProblemCnt($problem) * $score;
			return $ans;
		}
		private function commit()
		{
			global $db;
			$data = array("cfid" => $this->cfid, "fname" => $this->fname, "problemData" => json_encode($this->cnt), "lastUpdated" => $this->lastUpdated);
			$db->update("users", $data, array("id" => $this->id));
		}
		public static function cmpByScore($u1, $u2)
		{
			$x1 = $u1->calculateScore();
			$x2 = $u2->calculateScore();
			if ($x1 == $x2)
				return 0;
			return ($x1 < $x2) ? 1 : -1;
		}
	};
	function getUsers($sorted = false)
	{
		global $db;
		$users = array();
		$objs = $db->select("users", array("id"));
		foreach ($objs as $data)
			$users[] = new User($data["id"]);
		if ($sorted)
			usort($users, "User::cmpByScore");
		return $users;
	}


?>
