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
	private $ldapUser;
	private $ldapPassword;
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
}
?>