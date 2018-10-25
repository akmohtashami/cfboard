<?php

	class Database
	{
		private $con;

		public function __construct($name, $host, $user, $pass)
		{
			$this->con = new mysqli($host, $user, $pass, $name);
			if ($this->con->connect_error)
				die("Connection failed. please contact server administrator");
		}
		public function select($tname, $fields, $conditions = array())
		{
			if (!$this->con)
				die("No Connection.");
			if (empty($fields))
				die ("No field selected");
			$len = count($fields);
			$sql = "SELECT ";
			foreach ($fields as $field)
			{
				$sql .= $field;
				$len = $len - 1;
				if ($len == 0)
					$sql .= " ";
				else
					$sql .= ", ";
			}
			$sql .= "FROM ".$tname;
			$len = count($conditions);
			if ($len != 0)
			{
				$sql .= " WHERE ";
				foreach ($conditions as $key => $value)
				{
					$sql .= $key."=".$value;
					$len = $len - 1;
					if ($len != 0)
						$sql .= ", ";
				}
			}
			$result = $this->con->query($sql);
			if (!$result)
				die ("ERROR - Executing following query failed: ".$sql);
			return $result->fetch_all(MYSQL_BOTH);
		}
		public function update($tname, $values, $conditions)
		{
			if (!$this->con)
				die("No Connection.");
			$sql = "UPDATE ".$tname." SET ";
			$len = count($values);
			foreach ($values as $key => $value)
			{
				$sql .= $key." = '".$value."'";
				$len = $len - 1;
				if ($len != 0)
					$sql .= ", ";
			}
			$len = count($conditions);
			if ($len != 0)
			{
				$sql .= " WHERE ";
				foreach ($conditions as $key => $value)
				{
					$sql .= $key." = '".$value."'";
					$len = $len - 1;
					if ($len != 0)
						$sql .= ", ";
				}
			}
			$result = $this->con->query($sql);		
			if (!$result)
				die ("ERROR - Executing following query failed: ".$sql);
		}
		public function insert($tname, $values, $ignErr = false)
		{
			if (!$this->con)
				die("No Connection.");
			$sql = "INSERT INTO ".$tname." (";
			$sql2 = "VALUES (";
			$len = count($values);
			foreach ($values as $key => $value)
			{
				$sql .= $key;
				$sql2 .= "'".$value."'";
				$len = $len - 1;
				if ($len != 0)
				{
					$sql .= ",";
					$sql2 .= ",";
				}
			}
			$sql = $sql.") ".$sql2. ")";
			$result = $this->con->query($sql);
			if (!$result && !$ignErr)
				die ("ERROR - Executing following query failed: ".$sql);
		}
		public function __destruct()
		{
			if ($this->con)
				$this->con->close();
		}
	}
