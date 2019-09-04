<?hh // strict
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
	public function query(string $sql, array<string, Bind> $bind = []): array<string, mixed>
	{
		$statement = $this->pdo->prepare($sql);

		foreach ($bind as $name => $value)
		{
			$statement->bindValue(':' . $name, $value->data, $value->datatype);
		}

		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	// Secured execute
	public function exec(string $sql, array<string, Bind> $bind = []): void
	{
		$statement = $this->pdo->prepare($sql);

		foreach ($bind as $name => $value)
		{
			$statement->bindValue(':' . $name, $value->data, $value->datatype);
		}

		$statement->execute();
	}
}

class Bind
{
	public string $data = "";
	public int $datatype = -1;

	public function __construct(string $data, int $datatype)
	{
		$this->data = $data;
		$this->datatype = $datatype;
	}
}

/*
	PDO::PARAM_BOOL
	PDO::PARAM_NULL
	PDO::PARAM_INT
	PDO::PARAM_STR
*/
