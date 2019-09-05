<?php
/*
 *	SDB - A simple database class
 *
 *	@author      Jonatan Saari
 *
 */
include_once "database.class.hh";

class SDB
{
	public $db;

	public function __construct($username, $password, $database = "")
	{
		$this->db = new DB($username, $password, $database);
	}

	// Secured query
	public function query($sql, $bind = [])
	{
		return $this->db->query($sql, $bind);
	}

	public function quote($text): string
	{
		return $this->db->pdo->quote($text);
	}

	// Secured execute
	public function exec($sql, $bind = [])
	{
		$this->db->exec($sql, $bind);
	}

	public function CreateDatabase($databaseName)
	{
		$this->quote($databaseName);
		$this->db->exec("CREATE DATABASE IF NOT EXISTS $databaseName");
		$this->db->exec("use $databaseName");
	}

	public function DropDatabase($databaseName)
	{
		$this->quote($databaseName);
		$this->db->pdo->exec("DROP DATABASE $databaseName");
	}

	public function CreateTable($tableName)
	{
		$this->quote($tableName);
		$this->db->pdo->exec("CREATE TABLE IF NOT EXISTS $tableName (
				`id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, PRIMARY KEY (`id`)
			) CHARACTER SET utf8 COLLATE utf8_general_ci"
		);
	}

	public function CreateProcedure($procedureName, $params, $code)
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

	public function DropProcedure($procedureName)
	{
		$this->quote($procedureName);
		$this->db->pdo->exec("DROP PROCEDURE IF EXISTS $procedureName;");
	}

	public function DropTable($tableName)
	{
		$this->quote($tableName);
		$this->db->pdo->exec("DROP TABLE IF EXISTS $tableName");
	}

	public function AddColumn($tableName, $ColumnName, $ColumnData)
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

	public function DropColumn($tableName, $ColumnName)
	{
		$this->quote($tableName);
		$this->quote($ColumnName);
		$this->db->pdo->exec("ALTER TABLE $tableName
								DROP COLUMN $ColumnName");
	}

	public function ModifyColumn($tableName, $ColumnName, $ColumnData)
	{
		$this->quote($tableName);
		$this->quote($ColumnName);
		$this->db->pdo->exec("ALTER TABLE $tableName
								MODIFY $ColumnName
									$ColumnData");
	}

	// --- Show tables in database
	public function ShowTables($databaseName)
	{
		$this->quote($databaseName);
		return $this->db->query("SHOW TABLES IN $databaseName");
	}

	// --- Show columns a table
	public function ShowColumns($tableName)
	{
		$this->quote($tableName);
		return $this->db->query("SHOW COLUMNS FROM $tableName");
	}

	// --- Show all databases
	public function ShowDatabases()
	{
		return $this->db->query("SHOW DATABASES");
	}
}
