<?php
/**
 * Filename: session.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide session functionality.
 * @version 0.0.1
 * File created: 02MAY2011
 * File modified: 02MAY2011
 * @package GCTools
 * @subpackage Session
 */

//Define namespace
//namespace GCTools/Session;

//Class uses Singleton pattern because you can have only 1 session open at a time.
class Session {
	private static $instance; //Holds the instance of the session class
	
	private function Session() {
		//Prevent user from creating the class
	}
	
	/*
	 * init() function
	 * 
	 * No inputs
	 * 
	 * This function checks if a session has been created already. If one
	 * has not been created, then the init function will create one. Otherwise
	 * it will return the current instance.
	 * 
	 * Returns the current instance of the Session, or FALSE
	 */
	public function init() {
		//Precondition: None
		//Postcondition: Return the current instance of the Session, or FALSE
		
		if (!isset(self::$instance)) {
			if (session_start()) {
				$c = __CLASS__;
				self::$instance = new $c;
			}
			else
				throw new Exception("Unable to start session");
		}
		
		return self::$instance;
	}
}

?>