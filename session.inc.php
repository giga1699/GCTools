<?php
/**
 * Filename: session.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide session functionality.
 * @version 0.0.1
 * File created: 02MAY2011
 * @package GCTools
 * @subpackage Session
 */

//Define namespace
//namespace GCTools/Session;

//Class uses Singleton pattern because you can have only 1 session open at a time.
class Session {
	private static $instance; //Holds the instance of the session class
	private static $sessionName; //The session's name
	private static $sessionID;
	
	private function Session() {
		//Prevent user from creating the class
	}
	
	/*
	 * init($session_name=NULL) function
	 * 
	 * $session_name => Defines a name for the session
	 * 
	 * This function checks if a session has been created already. If one
	 * has not been created, then the init function will create one. Otherwise
	 * it will return the current instance.
	 * 
	 * Returns the current instance of the Session, or FALSE
	 */
	public function init($session_name=NULL) {
		//Precondition: None
		//Postcondition: Return the current instance of the Session, or FALSE
		
		if (!isset(self::$instance)) {
			if (isset($session_name)) {
				self::$sessionName = $session_name;
				session_name($session_name);
			}
			if (session_start()) {
				$c = __CLASS__;
				self::$instance = new $c;
				
				//Update session ID
				self::$sessionID = session_id();
			}
			else
				throw new Exception("Unable to start session");
		}
		
		return self::$instance;
	}
	
	/*
	 * getSessionName() function
	 * 
	 * No inputs
	 * 
	 * This function returns the name of the current session.
	 * 
	 * Returns the name of the session, or FALSE on failure.
	 */
	public function getSessionName() {
		//Precondition: sessionName should be defined
		//Postcondition: Return the session name, or FALSE on failure
		
		if (!isset($this->sessionName))
			return FALSE;
		
		return $this->sessionName;
	}
	
	/*
	 * getSessionID() function
	 * 
	 * No inputs
	 * 
	 * This function provides the user with the current session ID
	 * 
	 * Returns the session ID, or FALSE otherwise
	 */
	public function getSessionID() {
		//Precondition: A session should exist
		//Postcondition: Returns the session ID, or FALSE otherwise
		
		if (!isset($this->sessionID))
			return FALSE;
			
		return $this->sessionID;
	}
	
	/*
	 * saveData($name, $data) function
	 * 
	 * $name => Defines the name for the data
	 * $data => Defines the raw data to be saved
	 * 
	 * This function registers a variable by the name of $name, and stores
	 * $data to that session variable.
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function saveData($name, $data) {
		//Precondition: $name and $data should be set
		//Postcondition: The data should be registered as a session variable named $name
		
		if (!isset($name) || !isset($data))
			return FALSE;
			
		//Create the variable
		$_SESSION[$name] = $data;
		
		if (isset($_SESSION[$name]))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getData($name) function
	 * 
	 * $name => Defines the name of the session variable to get
	 * 
	 * This function gets the saved data from the session variable named $name
	 * and returns it to the user.
	 * 
	 * Returns the session data on success, and FALSE otherwise
	 */
	public function getData($name) {
		//Precondition: $name should be set, and $_SESSION[$name] should be set
		//Postcondition: Return the stored data, or FALSE otherwise
		
		if (!isset($name) || !isset($_SESSION[$name]))
			return FALSE;
		
		return $_SESSION[$name];
	}
	
	/*
	 * destroyData($name) function
	 * 
	 * $name => Defines the name of the session variable to destroy
	 * 
	 * This function destroys a session variable named $name
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function destroyData($name) {
		//Precondition: $name should be set
		//Postcondition: Destroy the data. Return TRUE on success, and FALSE otherwise.
		
		if (!isset($name) || !isset($_SESSION[$name]))
			return FALSE;
		
		unset($_SESSION[$name]);
		
		return TRUE;
	}
}

?>