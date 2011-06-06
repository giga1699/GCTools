<?php
/**
 * Filename: security.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide security support
 * @version: 0.0.1
 * File created: 17MAR11
 * @package GCTools
 * @subpackage Security
 */

//namespace GCTools/Security;

//Authentication types
define("SE_AUTH_TYPE_BASIC", 0);
define("SE_AUTH_TYPE_HTML", 1);

//Password hashes
define("SE_AUTH_HASH_MD5", 0);
define("SE_AUTH_HASH_HTPAS", 1);

//Authentication sources
define("SE_AUTH_SOURCE_HTPASS", 0);
define("SE_AUTH_SOURCE_MYSQL", 1);
define("SE_AUTH_SOURCE_MSSQL", 2);
define("SE_AUTH_SOURCE_LDAP", 3);

class Security {
	//Variables for Security class
	private $authSource; //Defines the authentication source
	private $authType; //Defines the authentication type
	private $authHash; //Defines the authentication hash type
	private $htpassfile; //Defines where the .htpasswd file is located
	
	/*
	 * Security class initilization
	 * 
	 * This function sets-up the Security class, and sets up certain class defaults
	 * 
	 * No return value.
	 */
	public function Security($authSource=NULL, $authType=NULL, $authHash=NULL, $htpassfile=NULL) {
		//Precondition: None
		//Postcondition: Class defaults are set up
		
		//Auth Source
		if (!isset($authSource))
			$this->authSource = SE_AUTH_SOURCE_HTPASS;
		else
			$this->authSource = $authSource;
		
		//Auth type
		if (!isset($authType))
			$this->authType = SE_AUTH_TYPE_BASIC;
		else
			$this->authType = $authType;
		
		//Auth hash
		if (!isset($authHash))
			$this->authHash = SE_AUTH_HASH_MD5;
		else
			$this->authHash = $authHash;
		
		//.htpasswd file
		if (!isset($htpassfile))
			$this->htpassfile = NULL;
		else
			$this->htpassfile = $htpassfile;
	}
	
	public function getAuthSource() {
		//Precondition: authSource should be defined
		//Postcondition: Return authSource, or FALSE otherwise.
		
		if (!isset($this->authSource))
			return FALSE;
		
		return $this->authSource;
	}
	
	public function setAuthSource($authSource) {
		//Precondition: $authSource should be defined
		//Postcondition: Set authSource for use. Return TRUE on success, and FALSE otherwise.
		
		if (!isset($authSource))
			return FALSE;
		
		$this->authSource = $authSource;
		
		return TRUE;
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
	 * getAuthHash() function
	 * 
	 * This function provides the user with the currently set authentication hash.
	 * 
	 * Returns the currently set authentication hash, or FALSE on failure.
	 */
	public function getAuthHash() {
		//Precondition: authHash should be set
		//Postcondition: Return what the authHash is set as, or FALSE on failure.
		
		if (isset($this->authHash))
			return $this->authHash;
		else
			return FALSE;
	}
	
	/*
	 * setAuthHash($authHash) function
	 * 
	 * $authHash => Defines what the new authentication hash should be.
	 * 
	 * This function sets what the authentication hash should be for the class.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setAuthHash($authHash) {
		//Precondition: authHash should be set
		//Postcondition: Sets the auth hash for the class
		
		if (!isset($authHash))
			return FALSE;
		
		$this->authHash = $authHash;
		
		if ($this->authHash == $authHash)
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
