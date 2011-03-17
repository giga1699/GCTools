<?php
/*
 * Filename: security.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide security support
 * @version: 0.0.1
 * File created: 17MAR11
 * File updated: 17MAR11
 * @package GCTools
 * @subpackage Security
 * 
 * Change log:
 * 
 */

//namespace GCTools/Security;

define("SE_AUTH_TYPE_BASIC", 0);

class Security {
	//Variables for Security class
	private $authType; //Defines the authentication type
	private $htpassfile; //Defines where the .htpasswd file is located
	
	/*
	 * Security class initilization
	 * 
	 * This function sets-up the Security class, and sets up certain class defaults
	 * 
	 * No return value.
	 */
	public function Security() {
		//Precondition: None
		//Postcondition: Class defaults are set up
		
		$this->authType = SE_AUTH_TYPE_BASIC;
		$this->htpassfile = NULL;
	}
	
	/*
	 * getAuthType() function
	 * 
	 * This function provides the user with the currently set authentication type.
	 * 
	 * Returns the currently set authentication type, or FALSE on failure.
	 */
	public function getAuthType() {
		//Precondition: authType should be set
		//Postcondition: Return what the authType is set as, or FALSE on failure.
		
		if (isset($this->authType))
			return $this->authType;
		else
			return FALSE;
	}
	
	/*
	 * setAuthType($authType) function
	 * 
	 * $authType => Defines what the new authentication type should be.
	 * 
	 * This function sets what the authentication type should be for the class.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setAuthType($authType) {
		//Precondition: authType should be set
		//Postcondition: Sets the auth type for the class
		
		if (!isset($authType))
			return FALSE;
		
		$this->authType = $authType;
		
		if ($this->authType == $authType)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * createHTPassUser($user,$pass) function
	 * 
	 * This function creates the new line needed to add a user to an
	 * Apache .htpasswd file.
	 * 
	 * Returns the new string on success, and FALSE on failure.
	 */
	public function createHTPassUser($user, $pass) {
		//Precondition: A username, and password, are given
		//Postcondition: The Apache .htpasswd line is returned, or NULL on error.
		
		if (is_null($user) || is_null($pass))
			return FALSE;
		
		$fileString = $user . ":";
		$fileString .= crypt($pass, base64_encode($pass));
		
		return $fileString;
	}
}
?>
