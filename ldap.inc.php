<?php
/**
 * Filename: ldap.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide LDAP support
 * @version: 0.0.1
 * File created: 06JUN2011
 * @package GCTools
 * @subpackage LDAP
 */

//namespace GCTools/LDAP;

//LDAP Class
class LDAP {
	protected $ldapServer;
	protected $ldapPort;
	protected $ldapLink;
	protected $baseDN;
	protected $lastError;
	
	public function LDAP($host, $errorCallback=NULL, $port=NULL) {
		//Precondition: At least $host should be defined
		//Postcondition: Create a connection to the LDAP server
		
		//Check if the LDAP libary is loaded
		if (!extension_loaded('ldap')) {
			//Extension not loaded, so load based on OS
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				dl('php_ldap.dll') or die("Unable to load libraries. Please contact your IT support staff.");
			}
			else {
				dl('ldap.so') or die("Unable to load libraries. Please contact your IT support staff.");
			}
		}
		
		if (!isset($host))
			throw new Exception("A LDAP server must be defined");
		
		$this->ldapServer = $host;
		
		if (isset($port))
			$this->ldapPort = $port;
		
		//Set errorCallback, if needed
		if (isset($errorCallback))
			$this->errorCallback = $errorCallback;
		
		//Connect
		$this->connect();
	}
	
	private function connect() {
		//Precondition: None
		//Postcondition: Attempt to make a connection to the LDAP server
		
		$this->ldapLink = ldap_connect($this->ldapServer, (isset($this->ldapPort) ? $this->ldapPort : "389"));
		
		if (!$this->ldapLink)
			$this->throwError();
	}
	
	protected function throwError($specialError=NULL) {
		/* Precondition: An error has occured */
		/* Postcondition: The error is created in the LDAP
		 * class with the proper information.
		 */
		
		if (!$this->resetError())
			return FALSE;
		
		if (isset($specialError))
			$this->lastError = $specialError;
		else
			$this->lastError = "LDAP Error (".@ldap_errno($this->ldapLink)."): ".@ldap_error($this->ldapLink);
		
		if (isset($this->errorCallback) && is_callable($this->errorCallback))
			call_user_func($this->errorCallback, $this->lastError);
		
		if (isset($this->lastError))
			return TRUE;
		else
			return FALSE;
	}
	
	public function bind($dn=NULL, $password=NULL) {
		//Precondition: If either $dn, or $password, are defined, then both must be defined
		//Postcondition: Attempt to bind to the LDAP server. Returns TRUE on success,
		//   and FALSE otherwise.
		
		if ((isset($dn) && !isset($password)) || (!isset($dn) && isset($password)))
			return FALSE;
		
		//Attempt to do the LDAP bind
		if (ldap_bind($this->ldapLink, (isset($dn) ? $dn : NULL), (isset($password) ? $password : NULL)))
			return TRUE;
		else
			return FALSE;
		
	}
	
	public function getLastError() {
		//Precondition: lastError should have been defined
		//Postcondition: Return the last error, or FALSE otherwise
		
		if (!isset($this->lastError))
			return FALSE;
		
		return $this->lastError;
	}
	
	public function clearLastError() {
		//Precondition: None
		//Postcondition: Clear any previous error
		
		if (!isset($this->lastError))
			return TRUE;
		
		unset($this->lastError);
		
		return TRUE;
	}
	
	public function getBaseDN() {
		//Precondition: baseDN should be defined
		//Postcondition: Return baseDN or FALSE otherwise
		
		if (!isset($this->baseDN))
			return FALSE;
		
		return $this->baseDN;
	}
	
	public function setBaseDN($dn) {
		//Precondition: $dn should be defined, and valid
		//Postcondition: Set the base DN. Return TRUE on success, and FALSE otherwise.
		
		if (!isset($dn))
			return FALSE;
		
		//TODO: Add $dn validity checking
		
		$this->baseDN = $dn;
		
		return TRUE;
	}
	//TODO: Finish LDAP class
}

//Windows ActiveDirectory specific class
class ActiveDirectory extends LDAP {
	public function ActiveDirectory($host, $errorCallback=NULL, $port=NULL) {
		try {
			$this->LDAP($host, $errorCallback, $port);
		}
		catch (Exception $e) {
			throw new Exception($e);
		}
	}
}
?>