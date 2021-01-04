<?php
class Database {
	private $connection;
	private $hostname;
	private $username;
	private $password;
	private $database;
	public function __construct() {
		$this->hostname = DATABASE_HOST;
		$this->username = DATABASE_USER;
		$this->password = DATABASE_PASSWORD;
		$this->database = DATABASE_NAME;
	}
	public function openConnection() {
		$this->connection = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
		// Check connection
		if (!$this->connection) {
			die("Connection failed: " . mysqli_connect_error());
		}
	}
	public function closeConnection() {
		if (isset($this->connection)) {
			// Close database connection

			mysqli_close($this->connection);
		}
	}
	public function executeStatement($statement) {
		$this->openConnection();
		$result = mysqli_query($this->connection, $statement);

		if ($result) {
			return $result;
		} else {
			echo mysqli_error($this->connection);
		}
	}
	// insert data
	public function executeSqlQuery($sql) {
		// Execute database statement
		$result = $this->executeStatement($sql);
		$last_inserted_record = mysqli_insert_id($this->connection);
		// Close database cursor
		mysqli_free_result($result);
		// Close database connection
		$this->closeConnection();
		// Return dataset
		return $last_inserted_record;
	}
	// update data
	public function executeSqlUpdateQuery($sql) {
		// Execute database statement
		$result = $this->executeStatement($sql);
		//$last_inserted_record = mysql_insert_id();
		// Close database cursor
		mysqli_free_result($result);
		// Close database connection
		$this->closeConnection();
		// Return dataset
		return $result;
	}
	// get all data
	public function executeSqlQueryGetData($sql) {
		// Execute database statement
		$result = $this->executeStatement($sql);
		// Check number of rows returned
		if (mysqli_num_rows($result) == 1) {
			// Fetch one row from the result
			//$dataset = mysql_fetch_object($result);
			$dataset = array();
			while ($row = mysqli_fetch_object($result)) {
				//print_r($row);
				$dataset[] = $row;
			}
		} else {
			// Fetch multiple rows from the result
			$dataset = array();
			while ($row = mysqli_fetch_object($result)) {
				$dataset[] = $row;
			}
		}
		// Close database cursor
		mysqli_free_result($result);
		// Close database connection
		$this->closeConnection();
		// Return dataset
		return $dataset;
	}

	public function executeSqlQueryGetRow($sql) {
		$dataset = array();
		$result = $this->executeStatement($sql);
		if (mysqli_num_rows($result) == 1) {
			while ($row = mysqli_fetch_object($result)) {
				$dataset = $row;
			}
		}

		mysqli_free_result($result);
		$this->closeConnection();

		return $dataset;
	}

	// count data
	public function getcountsqldata($sql) {
		// Execute database statement
		$result = $this->executeStatement($sql);
		// Check number of rows returned
		$numrow = mysqli_num_rows($result);

		// Close database cursor
		mysqli_free_result($result);
		// Close database connection
		$this->closeConnection();
		// Return dataset
		return $numrow;
	}

	public function executeDml($dml) {
		// Execute database statement
		$this->executeStatement($dml);
		// Return affected rows
		return mysqli_affected_rows($this->connection);
	}
	public function sanitizeInput($value) {
		if (function_exists('mysqli_real_escape_string')) {
			if (get_magic_quotes_gpc()) {
				// Undo magic quote effects
				$value = stripslashes($value);
			}
			// Redo escape using mysql_real_escape_string
			$value = mysqli_real_escape_string($this->connection, $value);
		} else {
			if (!$this->get_magic_quotes_gpc()) {
				// Add slashed manually
				$value = addslashes($value);
			}
		}
		// Return sanitized value
		return $value;
	}

	public function escapeString($value) {
		$this->openConnection();
		$result = mysqli_real_escape_string($this->connection, $value);
		return $result;
	}
}
?>