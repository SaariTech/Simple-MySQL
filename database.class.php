<?php
/*
 *	DB - A database class
 *
 *	@author      Jonatan Saari
 *
 */
use \PDO;

class DB
{
	public $pdo;

	public function __construct(string $username, string $password, string $database = "")
	{
		if($database == "")
		{
			$this->pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8;",
				$username,
				$password
			);
		}
		else
		{
			$this->pdo = new PDO("mysql:host=127.0.0.1;port=3306;charset=utf8;dbname=$database;",
				$username,
				$password
			);
		}
	}

	// Secured query
	public function query($sql, $bind = [])
	{
		$statement = $this->pdo->prepare($sql);

		foreach ($bind as $name => $value)
		{
			$statement->bindValue(':' . $name, $value);
		}

		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	// Secured execute
	public function exec(string $sql, $bind = [])
	{
		$statement = $this->pdo->prepare($sql);

		foreach ($bind as $name => $value)
		{
			$statement->bindValue(':' . $name, $value);
		}

		$statement->execute();
	}
}
