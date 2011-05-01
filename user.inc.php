<?php
/**
 * Filename: user.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide user functionality.
 * @version 0.0.1
 * File created: 01MAY2011
 * File modified: 01MAY2011
 * @package GCTools
 * @subpackage User
 * 
 * Changelog:
 */

//Define namespace
//namespace GCTools/User;

//Define hash types
define("USER_PWHASH_NONE", 0); //This is generally used when another application hashes the password
define("USER_PWHASH_MD5", 1);

//User class
class User {
	protected $userID; //Unique user ID
	protected $userNick; //User's login name
	protected $userFName; //User's first name
	protected $userMName; //User's middle name
	protected $userLName; //User's last name
	protected $userEMail; //User's e-mail address
	protected $userHashType; //How the user's password is hashed
	protected $userPassword; //User's hashed password
	
	//Constructor
	public function User($userID, $userNick, $userHashType, $userPassword, $userEMail=NULL, $userLName=NULL, $userFName=NULL, $userMName=NULL) {
		//Precondition: userID, userNick, userEMail, userHashType and userPassword must be set
		//Postcondition: The class is initialized
		 
		if (!isset($userID) || !isset($userNick) || !isset($userHashType) || !isset($userPassword))
			throw new Exception("User class was not initalized properly");
		
		//Set-up class variables
		$this->userID = $userID;
		$this->userNick = $userNick;
		$this->userHashType = $userHashType;
		$this->userPassword = $userPassword;
		
		//Add additional variables if they are set
		if (isset($userEMail))
			$this->userEMail = $userEMail;
		if (isset($userLName))
			$this->userLName = $userLName;
		if (isset($userFName))
			$this->userFName = $userFName;
		if (isset($userMName))
			$this->userMName = $userMName;
	}
	
	/*
	 * getUserID() function
	 * 
	 * No inputs
	 * 
	 * This function returns the unique User ID
	 * 
	 * Returns the User ID, or FALSE on error
	 */
	public function getUserID() {
		//Precondition: userID should be set
		//Postcondition: Return the user's ID, or FALSE on error
		
		if (!isset($this->userID))
			return FALSE;
		
		return $this->userID;
	}
	
	/*
	 * setUserID($userID) function
	 * 
	 * $userID => Defines the new user ID
	 * 
	 * This function sets the user's ID
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function setUserID($userID) {
		//Precondition: $userID should be set
		//Postcondition: userID is set to new ID
		
		if (!isset($userID))
			return FALSE;
			
		$this->userID = $userID;
		
		if ($this->userID != $userID)
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * getUserNick() function
	 * 
	 * No inputs
	 * 
	 * This function gets the user's nickname, or login name
	 * 
	 * Returns the user's nick on success, and FALSE otherwise
	 */
	public function getUserNick() {
		//Precondition: userNick should be set
		//Postcondition: Return userNick, or FALSE
		
		if (!isset($this->userNick))
			return FALSE;
		
		return $this->userNick;
	}
	
	/*
	 * setUserNick($userNick) function
	 * 
	 * $userNick => Defines the new user's nickname, or login name
	 * 
	 * This function sets the user's nickname, or login name
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setUserNick($userNick) {
		//Precondition: $userNick should be set
		//Postcondition: Return TRUE on success, and FALSE otherwise
		
		if (!isset($userNick))
			return FALSE;
			
		$this->userNick = $userNick;
		
		if ($this->userNick != $userNick)
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * getUserPassword() function
	 * 
	 * No inputs
	 * 
	 * This function gets the user's hashed password
	 * 
	 * Returns hashed password on success, and FALSE otherwise
	 */
	public function getUserPassword() {
		//Precondition: userPassword should be set
		//Postcondition: Returns the hashed password, or FALSE
		
		if (!isset($this->userPassword))
			return FALSE;
			
		return $this->userPassword;
	}
	
	/*
	 * setUserPassword($newPass) function
	 * 
	 * $newPass => Defines the new user password
	 * 
	 * This function sets the user's new password
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setUserPassword($newPass) {
		//Precondition: $newPass should be set
		//Postcondition: New password should be set. Return TRUE on success, and FALSE otherwise
		
		if (!isset($newPass))
			return FALSE;
			
		switch($this->userHashType) {
			case USER_PWHASH_NONE:
				$this->userPassword = $newPass;
			break;
			case USER_PWHASH_MD5:
				$this->userPassword = md5($newPass);
			break;
		}
		
		return TRUE;
	}
	
	/*
	 * getUserEMail() function
	 * 
	 * No inputs
	 * 
	 * This function gets the user's E-Mail address
	 * 
	 * Returns the e-mail address, or FALSE
	 */
	public function getUserEMail() {
		//Precondition: userEMail should be set
		//Postcondition: Return the user's e-mail address, or FALSE
		
		if (!isset($this->userEMail))
			return FALSE;
			
		return $this->userEMail;
	}
	
	/*
	 * setUserEMail($email) function
	 * 
	 * $email => Defines the user's new e-mail address
	 * 
	 * This function changes the user's e-mail address
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setUserEMail($email) {
		//Precondition: $email should be set
		//Postcondition: Sets the user's e-mail. Return TRUE on success, and FALSE otherwise
		
		if (!isset($email))
			return FALSE;
			
		$this->userEMail = $email;
		
		if ($this->userEMail != $email)
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * getUserName() function
	 * 
	 * No inputs
	 * 
	 * This function gets the actual name of the user
	 * 
	 * Returns the formatted user's name, or FALSE
	 */
	public function getUserName() {
		//Precondition: userFName and userLName should be set
		//Postcondition: Return the formatted name, or FALSE
		
		if (!isset($this->userFName) || !isset($this->userLName))
			return FALSE;
			
		return $this->userFName . (isset($this->userMName) ? " " . $this->userMName . " " : " ") . $this->userLName; 
	}
	
	/*
	 * setUserName($lname, $fname, $mname) function
	 * 
	 * $lname => Defines the user's last name
	 * $fname => Defines the user's first name
	 * $mname => Defines the user's middle name, or initial
	 * 
	 * This function sets the user's real name
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setUserName($lname, $fname, $mname=NULL) {
		//Precondition: $lname and $fname should be set
		//Postcondition: Set the user's name. Return TRUE on success and FALSE otherwise.
		
		if (!isset($lname) || !isset($fname))
			return FALSE;
		
		$this->userFName = $fname;
		$this->userLName = $lname;
		
		if (isset($mname))
			$this->userMName = $mname;
			
		if ($this->userFName != $fname || $this->userLName != $lname)
			return FALSE;
		else
			return TRUE;
	}
}
?>