<?hh // strict
/*
 *	SDB - A simple database class
 *
 *	@author      Jonatan Saari
 *
 */
include_once "/var/www/spreadlesson.com/codebase/config.class.hh";
include_once Config::codebase . "/database/database.class.hh";

class SDB
{
	public DB $db;

	public function __construct(string $username, string $password, string $database = "")
	{
		$this->db = new DB($username, $password, $database);
	}

	// Secured query
	public function query(string $sql, array<string, Bind> $bind = []): array<string, mixed>
	{
		return $this->db->query($sql, $bind);
	}

	public function quote(string $text): string
	{
		return $this->db->pdo->quote($text);
	}

	// Secured execute
	public function exec(string $sql, array<string, Bind> $bind = []): void
	{
		$this->db->exec($sql, $bind);
	}

	public function CreateDatabase(string $databaseName): void
	{
		$this->quote($databaseName);
		$this->db->exec("CREATE DATABASE IF NOT EXISTS $databaseName");
		$this->db->exec("use $databaseName");
	}

	public function DropDatabase(string $databaseName): void
	{
		$this->quote($databaseName);
		$this->db->pdo->exec("DROP DATABASE $databaseName");
	}

	public function CreateTable(string $tableName): void
	{
		$this->quote($tableName);
		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS $tableName (
				`id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, PRIMARY KEY (`id`)
			) CHARACTER SET utf8 COLLATE utf8_general_ci"
		);
	}

	public function CreateProcedure(string $procedureName, string $params, string $code): void
	{
		$this->quote($procedureName);
		$this->db->pdo->exec(
			"DROP PROCEDURE IF EXISTS $procedureName;
			CREATE PROCEDURE $procedureName($params)
			BEGIN
				$code
			END;"
		);
	}

	public function DropProcedure(string $procedureName): void
	{
		$this->quote($procedureName);
		$this->db->pdo->exec("DROP PROCEDURE IF EXISTS $procedureName;");
	}

	public function DropTable(string $tableName): void
	{
		$this->quote($tableName);
		$this->db->pdo->exec("DROP TABLE IF EXISTS $tableName");
	}

	public function AddColumn(string $tableName, string $ColumnName, string $ColumnData): void
	{
		$this->quote($tableName);
		$this->quote($ColumnName);

		try
		{
			$this->db->pdo->exec("ALTER TABLE $tableName
								ADD $ColumnName
									$ColumnData");
		}
		catch (Exception $e)
		{
			// The column already exists
		}
	}
	/*
		VARCHAR( 42 ) NOT NULL
		varchar(2048) NOT NULL DEFAULT ''
		TEXT NOT NULL
	*/

	public function DropColumn(string $tableName, string $ColumnName): void
	{
		$this->quote($tableName);
		$this->quote($ColumnName);
		$this->db->pdo->exec("ALTER TABLE $tableName
								DROP COLUMN $ColumnName");
	}

	public function ModifyColumn(string $tableName, string $ColumnName, string $ColumnData): void
	{
		$this->quote($tableName);
		$this->quote($ColumnName);
		$this->db->pdo->exec("ALTER TABLE $tableName
								MODIFY $ColumnName
									$ColumnData");
	}

	// --- Show tables in database
	public function ShowTables(string $databaseName): array<string, mixed>
	{
		$this->quote($databaseName);
		return $this->db->query("SHOW TABLES IN $databaseName");
	}

	// --- Show columns a table
	public function ShowColumns(string $tableName): array<string, mixed>
	{
		$this->quote($tableName);
		return $this->db->query("SHOW COLUMNS FROM $tableName");
	}

	// --- Show all databases
	public function ShowDatabases(): array<string, mixed>
	{
		return $this->db->query("SHOW DATABASES");
	}
}
