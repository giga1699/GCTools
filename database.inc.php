<?php
/**
 * Filename: database.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide database functionality.
 * @version 0.2.2
 * File created: 10SEP2010
 * @package GCTools
 * @subpackage Database
 */

//Declare the namespace
//namespace GCTools/Database;

//Define database types
define("MYSQL", 0);
define("MSSQL", 1);
define("PGSQL", 2);
define("SQLITE", 3);

//Database class
abstract class Database {
	/*
	 * $dbType => Defines the type of databasewe are working on. Should
	 * be one of the types in the defines above.
	 * 
	 * $dbLoc => Defines the location of the database. Can be an IP, a hostname,
	 * or a file location in the case of SQLite type databases.
	 * 
	 * $dbUser => Defines the username required to login to the database.
	 * 
	 * $dbPass => Defines the password required to login to the database.
	 * 
	 * $dbName => Defines the name of the database to work with.
	 * 
	 * $lastError => This variable is used to keep track of the last error, if any,
	 * that occured.
	 */
	private $dbType; //Defined value
	protected $dbLoc; //String: IP address, hostname, or file location
	protected $dbUser; //String
	protected $dbPass; //String
	protected $dbName; //String
	protected $lastError; //String
	protected $errorCallback;

	//Constructor
	protected function Database($loc, $user, $pass, $type, $name=NULL) {
		/* Precondition: The database type, username, password
		 * and name of the database to query is given.
		 */
		/* Postcondition: The class will set up the variables
		 * that will be used to connect to the database, and
		 * conduct queries.
		 */

		if (!isset($loc) || !isset($user) || !isset($pass) || !isset($type))
			throw new Exception("Unable to create Database class. Not all of the initial variables were defined.");

		//Set up variables
		$this->dbLoc = $loc;
		$this->dbUser = $user;
		$this->dbPass = $pass;
		$this->dbType = $type;
		if (isset($name))
			$this->dbName = $name;
		
		return TRUE;
	}

	/*
	 * hasError() function
	 * 
	 * No inputs
	 * 
	 * This functions finds out if the database has encountered an error.
	 * 
	 * Returns TRUE if an error has occured, and FALSE if none have occured.
	 */
	public function hasError() {
		/* Precondition: None */
		/* Postcondition: Returns TRUE if an error has occured, and
		 * FALSE if one has not occured.
		 */

		if ((isset($this->lastError) && !empty($this->lastError)))
			return TRUE;
		else
			return FALSE;
	}

	/*
	 * getLastError() function
	 * 
	 * No inputs
	 * 
	 * Gets the last error message reported by the database.
	 * 
	 * Returns the error in a string if an error has occured, and
	 * FALSE if no error has occured.
	 */
	public function getLastError() {
		/* Precondition: An error should have occured */
		/* Postcondition: The last error that has occured is returned
		 * in a string format. Will return FALSE if no error has
		 * occured.
		 */

		if ($this->hasError())
			return $this->lastError;
		else
			return FALSE;
	}

	/*
	 * resetError() function
	 * 
	 * No inputs
	 * 
	 * Resets any error that may have been set.
	 * 
	 * Returns TRUE on success, and FALSE on error.
	 */
	protected function resetError() {
		/* Precondition: An error should have occured, but is not required. */
		/* Postcondition: The error is cleared from the class */

		unset($this->lastError);
		
		if (!isset($this->lastError))
			return TRUE;
		else
			return FALSE;
	}
}

//MySQL Class
class MySQL extends Database {
	//Variables for class
	private $myCon; //Variable for keeping track of MySQL connection

	//Constructor
	public function MySQL($loc, $user, $pass, $name, $errorCallback=NULL) {
		/* Precondition: The location of the MySQL server, the username,
		 * the password, and the name of the database is given. Also, the
		 * MySQL libraries should have been loaded.
		 */
		/* Postcondition: The MySQL server is connected to
		 */

		//Check if the MySQL libary is loaded
		if (!extension_loaded('mysql')) {
			//Extension not loaded, so load based on OS
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				if (!dl('php_mysql.dll'))
					throw new Exception("Unable to load libraries. Please contact your IT support staff");
			}
			else {
				if (!dl('mysql.so'))
					throw new Exception("Unable to load libraries. Please contact your IT support staff");
			}
		}

		//Set up the parent class
		$this->Database($loc, $user, $pass, MYSQL, $name) or die("Unable to initilize object.");
		
		//Set errorCallback, if needed
		if (isset($errorCallback))
			$this->errorCallback = $errorCallback;
		
		//Connect to the database
		if (!$this->connect())
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * connect() function
	 * 
	 * No inputs
	 * 
	 * This function establishes a MySQL connection
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	private function connect() {
		//THIS FUNCTION SHOULD NOT BE CHANGED
		/* Precondition: Class is set up. */
		/* Postcondition: A connection is made, and any
		 * errors are handled.
		 */
		
		//Connect to the MySQL server
		$this->myCon = @mysql_connect($this->dbLoc, $this->dbUser, $this->dbPass, TRUE);

		//Check if the connection is good
		if ($this->myCon) {
			//We made a good connection, so clear the errors
			$this->resetError();

			//Connection is good, so connect to the given database
			if (!mysql_select_db($this->dbName, $this->myCon)) {
				$this->throwError();
				return FALSE;
			}
			return TRUE;
		}
		else {
			//The connection could not be established
			$this->throwError("Could not connect to MySQL database.");
			return FALSE;
		}
	}
	
	/*
	 * throwError($specialError) function
	 * 
	 * $specialError => Defines any special error (like not being able to connect).
	 * 
	 * This function should be called if there was an error during an operation.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	protected function throwError($specialError=NULL) {
		/* Precondition: An error has occured */
		/* Postcondition: The error is created in the Database
		 * class with the proper information.
		 */
		
		if (!$this->resetError())
			return FALSE;
		
		if (isset($specialError))
			$this->lastError = $specialError;
		else
			$this->lastError = "MySQL Error (".@mysql_errno($this->myCon)."): ".@mysql_error($this->myCon);
		
		if (isset($this->errorCallback) && is_callable($this->errorCallback))
			call_user_func($this->errorCallback, $this->lastError);
		
		if (isset($this->lastError))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * query($qString) function
	 * 
	 * $qString => Defines the SQL query string to be executed
	 * 
	 * This function performs a MySQL query. It is not designed
	 * to prevent any sort of SQL injection. It is advised to utilize
	 * the escapeString($string) function in conjunction with this
	 * function.
	 * 
	 * Returns the result set on success, and FALSE on failure.
	 */
	public function query($qString) {
		/* Precondition: A query string should be presented */
		/* Postcondition: The class will attempt to execute
		 * the query. If the query can not be executed, it
		 * will return FALSE and create the error. If it
		 * is executed, the results will be returned.
		 */
		/* SECURITY NOTE: It is the user's responsibility
		 * to ensure they take the proper steps to prevent
		 * SQL injections, and other security issues.
		 */
		
		//Reset any previous errors
		$this->resetError();

		//Make sure we're connected
		if (!$this->connected())
			$this->connect();
		
		//Run query
		$result = mysql_query($qString, $this->myCon);
		
		//Check if query was executed okay
		if (!$result) {
			//Create error
			$this->throwError();
			return FALSE;
		}
		else
			return $result;
	}
	
	/*
	 * changeDB($dbName) function
	 * 
	 * $dbName => Defines the name of the database to connect to.
	 * 
	 * This function changes the current database that is being
	 * utilized by the class.
	 * 
	 * Returns TRUE on success, and FALSE on error.
	 */
	public function changeDB($dbName) {
		/* Precondition: A database name is given. */
		/* Postcondition: The class attempts to connect to the
		 * new database. If successful, it will return TRUE. If
		 * it fails, it will return FALSE and create the error.
		 */
		
		//Clear any previous errors
		$this->resetError();
		
		//Attempt to change databases
		if (!mysql_select_db($dbName, $this->myCon)) {
			//Could not switch
			$this->throwError();
			return FALSE;
		}
		else {
			//Update the dbName in the parent class
			$this->dbName = $dbName;
			return TRUE;
		}
	}
	
	/*
	 * escapeString($string) function
	 * 
	 * $string => Defines the string that should be escaped.
	 * 
	 * This function is meant to help with SQL injection attacks. It
	 * escapes a given string, that can be used safely in a SQL query.
	 * 
	 * Returns the escaped string on success, and FALSE on failure.
	 */
	public function escapeString($string) {
		/* Precondition: A string is provided. */
		/* Postcondition: The string is escaped using the
		 * current MySQL connection.
		 */
		
		//Clear any prior errors
		$this->resetError();

		//Make sure we've connected first
		if (!$this->connected())
			$this->connect();
		
		if ($this->hasError())
			return FALSE;
		
		$eString = mysql_real_escape_string($string, $this->myCon);
		
		if (!$eString) {
			$this->throwError();
			return FALSE;
		}
		else
			return $eString;
	}
	
	/*
	 * connected() function
	 * 
	 * No inputs
	 * 
	 * This function determines if you are still connected to a MySQL server.
	 * 
	 * Returns TRUE if you are connected, and FALSE if not.
	 */
	public function connected() {
		/* Precondition: None. */
		/* Postcondition: Checks if the connection is still
		 * established.
		 */
		
		if (@mysql_ping($this->myCon) == TRUE)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * reconnect() function
	 * 
	 * No inputs
	 * 
	 * This function reconnects to the MySQL server safely.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function reconnect() {
		/* Precondition: None. */
		/* Postcondition: Will close any current connection,
		 * and re-establish a connection to the MySQL server.
		 */
		
		//Clear existing errors
		$this->resetError();
		
		//Check if we're still connected to the server
		if ($this->connected())
			mysql_close($this->myCon);
			
		//Re-establish connection
		$this->connect();
		
		//Determine if an error occured
		if ($this->hasError())
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * setErrorCallback($callback) function
	 * 
	 * $callback => Defines the error callback
	 * 
	 * This function allows a user to set/change the error callback for MySQL errors
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setErrorCallback($callback) {
		//Precondition: $callback should be defined
		//Postcondition: Set errorCallback
		
		if (!isset($callback))
			return FALSE;
		
		$this->errorCallback = $callback;
		
		if ($this->errorCallback == $callback)
			return TRUE;
		else
			return FALSE;
	}
	
	//Destructor
	public function __destruct() {
		/* Precondition: The class is being destroyed */
		/* Postcondition: The class will ensure clean closing
		 * of the MySQL connection, if still connected.
		 */
		
		//Check if we're still connected to MySQL
		if ($this->connected())
			mysql_close($this->myCon);
	}
}

//MSSQL Class
class MSSQL extends Database {
	private $msCon;
	
	public function MSSQL($loc, $user, $pass, $name, $errorCallback=NULL) {
		/* Precondition: The location of the MSSQL server, the username,
		 * the password, and the name of the database is given. Also, the
		 * MSSQL libraries should have been loaded.
		 */
		/* Postcondition: The MSSQL server is connected to
		 */

		//Check if the MSSQL libary is loaded
		if (!extension_loaded('mssql')) {
			//Extension not loaded, so load based on OS
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				if (!dl('php_mssql.dll'))
					throw new Exception("Unable to load libraries. Please contact your IT support staff");
			}
			else
				throw new Exception("Unable to load libraries. Please contact your IT support staff");
		}
		
		//Set up the parent class
		$this->Database($loc, $user, $pass, MSSQL) or die("Unable to initilize object.");
		
		//Set errorCallback, if needed
		if (isset($errorCallback))
			$this->errorCallback = $errorCallback;
		
		//Connect to the database
		if (!$this->connect())
			return FALSE;
		else
			return TRUE;
	}
	
	private function connect() {
		//THIS FUNCTION SHOULD NOT BE CHANGED
		/* Precondition: Class is set up. */
		/* Postcondition: A connection is made, and any
		 * errors are handled.
		 */
		
		//Connect to the MSSQL server
		$this->msCon = @mssql_connect($this->dbLoc, $this->dbUser, $this->dbPass);

		//Check if the connection is good
		if ($this->msCon) {
			//We made a good connection, so clear the errors
			$this->resetError();

			//Connection is good
			if (!mssql_select_db($this->dbName, $this->msCon)) {
				$this->throwError();
				return FALSE;
			}
			return TRUE;
		}
		else {
			//The connection could not be established
			$this->throwError("Could not connect to MSSQL database.");
			return FALSE;
		}
	}
	
	protected function throwError($specialError=NULL) {
		/* Precondition: An error has occured */
		/* Postcondition: The error is created in the Database
		 * class with the proper information.
		 */
		
		if (!$this->resetError())
			return FALSE;
		
		if (isset($specialError))
			$this->lastError = $specialError;
		else
			$this->lastError = "MSSQL Error: ".@mssql_get_last_message();
		
		if (isset($this->errorCallback) && is_callable($this->errorCallback))
			call_user_func($this->errorCallback, $this->lastError);
		
		if (isset($this->lastError))
			return TRUE;
		else
			return FALSE;
	}
	
	public function query($qString) {
		/* Precondition: A query string should be presented */
		/* Postcondition: The class will attempt to execute
		 * the query. If the query can not be executed, it
		 * will return FALSE and create the error. If it
		 * is executed, the results will be returned.
		 */
		/* SECURITY NOTE: It is the user's responsibility
		 * to ensure they take the proper steps to prevent
		 * SQL injections, and other security issues.
		 */
		
		//Reset any previous errors
		$this->resetError();
		
		//NOTE: MSSQL does not support the same active connection checking that MySQL does
		
		//Run query
		$result = mssql_query($qString, $this->msCon);
		
		//Check if query was executed okay
		if (!$result) {
			//Create error
			$this->throwError();
			return FALSE;
		}
		else
			return $result;
	}
	
	public function changeDB($dbName) {
		/* Precondition: A database name is given. */
		/* Postcondition: The class attempts to connect to the
		 * new database. If successful, it will return TRUE. If
		 * it fails, it will return FALSE and create the error.
		 */
		
		//Clear any previous errors
		$this->resetError();
		
		//Attempt to change databases
		if (!mssql_select_db($dbName, $this->msCon)) {
			//Could not switch
			$this->throwError();
			return FALSE;
		}
		else {
			//Update the dbName in the parent class
			$this->dbName = $dbName;
			return TRUE;
		}
	}
	
	/*
	 * MSSQL does not have a way to check if we are currently connected.
	 * It also does not allow for the escaping of strings, so we'll have to
	 * add this functionality ourselves.
	 */
	
	//TODO: Add escape string function
	
	//Destructor
	function __destruct() {
		/* Precondition: The class is being destroyed */
		/* Postcondition: The class will ensure clean closing
		 * of the MSSQL connection, if still connected.
		 */
		
		mssql_close($this->msCon);
	}
}

//PGSQL Class
class PGSQL extends Database {
	//TODO: Add functionality for PostgreSQL
	private $pgCon; //Variable to keep track of PGSQL connection
	
	public function PGSQL($loc, $user, $pass, $name, $errorCallback=NULL) {
		//TODO: Add construction instructions
	}
	
	private function connect() {
		//THIS FUNCTION SHOULD NOT BE CHANGED
		/* Precondition: Class is set up. */
		/* Postcondition: A connection is made, and any
		 * errors are handled.
		 */
		
		//Connect to the PGSQL server
		$this->pgCon = @pg_connect("host=" . $this->dbLoc . " user=" . $this->dbUser . " password=" . $this->dbPass . " dbname=" . $this->dbName);

		//Check if the connection is good
		if ($this->pgCon) {
			//We made a good connection, so clear the errors
			$this->resetError();
			
			return TRUE;
		}
		else {
			//The connection could not be established
			$this->throwError("Could not connect to PGSQL database.");
			return FALSE;
		}
	}
	
	protected function throwError($specialError=NULL) {
		/* Precondition: An error has occured */
		/* Postcondition: The error is created in the Database
		 * class with the proper information.
		 */
		
		if (!$this->resetError())
			return FALSE;
		
		if (isset($specialError))
			$this->lastError = $specialError;
		else
			$this->lastError = "PGSQL Error: ".@pg_last_error();
		
		if (isset($this->errorCallback) && is_callable($this->errorCallback))
			call_user_func($this->errorCallback, $this->lastError);
		
		if (isset($this->lastError))
			return TRUE;
		else
			return FALSE;
	}
	
	//Destructor
	public function __destruct() {
		/* Precondition: The class is being destroyed */
		/* Postcondition: The class will ensure clean closing
		 * of the PGSQL connection, if still connected.
		 */
		
		pg_close($this->pgCon);
	}
}

//SQLite Class
class SQLite extends Database {
	//TODO: Add functionality for SQLite
}
?>
