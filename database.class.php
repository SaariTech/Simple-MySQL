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
	public PDO $pdo;

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
/*
class Bind
{
	public $data = "";
	public $datatype = -1;

	public function __construct($data, $datatype)
	{
		$this->data = $data;
		$this->datatype = $datatype;
	}
}
*/

/*
	PDO::PARAM_BOOL
	PDO::PARAM_NULL
	PDO::PARAM_INT
	PDO::PARAM_STR
*/
