<?php
/**
 * Filename: user.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide user functionality.
 * @version 0.0.1
 * File created: 01MAY2011
 * @package GCTools
 * @subpackage User
 */

//Define namespace
//namespace GCTools/User;

//Define hash types
define("USER_PWHASH_NONE", 0); //This is generally used when another application hashes the password
define("USER_PWHASH_MD5", 1); //MD5 Hashes
define("USER_PWHASH_SHA1", 2); //SHA1 Hashes

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
	protected $userPhone; //User's phone number
	/*Begin User Address*/
	protected $userAddress1;
	protected $userAddress2;
	protected $userCity;
	protected $userState;
	protected $userZip;
	/*End User Address*/
	
	//Constructor
	public function User($userID, $userNick, $userEMail=NULL, $userLName=NULL, $userFName=NULL, $userMName=NULL, $userPhone=NULL, $userAddress1=NULL, $userCity=NULL, $userState=NULL, $userZip=NULL, $userAddress2=NULL) {
		//Precondition: userID, userNick, and userEMail must be set
		//Postcondition: The class is initialized
		 
		if (!isset($userID) || !isset($userNick))
			throw new Exception("User class was not initalized properly");
		
		//Set-up class variables
		$this->userID = $userID;
		$this->userNick = $userNick;
		
		//Add additional variables if they are set
		if (isset($userEMail)) {
			if (!$this->setUserEMail($userEMail))
				throw new Exception("Invalid E-Mail provided");
		}
		if (isset($userLName))
			$this->userLName = $userLName;
		if (isset($userFName))
			$this->userFName = $userFName;
		if (isset($userMName))
			$this->userMName = $userMName;
		if (isset($userPhone))
			$this->setUserPhone($userPhone);
		if (isset($userAddress1) && isset($userCity) && isset($userState) && isset($userZip)) {
			$this->userAddress1 = $userAddress1;
			if (isset($userAddress2))
				$this->userAddress2 = $userAddress2;
			$this->userCity = $userCity;
			$this->userState = $userState;
			$this->userZip = $userZip;
		}
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
	
	public function getHashType() {
		//Precondition: userHashType should be defiend
		//Postcondition: Return the hash type, or FALSE otherwise
		
		if (!isset($this->userHashType))
			return FALSE;
		
		return $this->userHashType;
	}
	
	public function setHashType($hashType) {
		//Precondition: $hashType should be defined, and valid
		//Postcondition: Set the hash type. Return TRUE on success, and FALSE otherwise
		
		if (!isset($hashType))
			return FALSE;
		
		$this->userHashType = $hashType;
		
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
	 * setUserPassword($newPass[, $hashType[, $salt]]) function
	 * 
	 * $newPass => Defines the new user password
	 * $hashType => Defines how to hash the user's password
	 * $salt => Defines a salt to include in front of the user's password
	 * 
	 * This function sets the user's new password
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setUserPassword($newPass, $hashType=NULL, $salt=NULL) {
		//Precondition: $newPass should be set
		//Postcondition: New password should be set. If $hashType was defined, set it as well.
		//	Return TRUE on success, and FALSE otherwise
		
		if (!isset($newPass))
			return FALSE;
		
		if (!isset($hashType) && isset($this->userHashType))
			$hashType = $this->userHashType;
		elseif (!isset($this->userHashType))
			return FALSE;
		
		switch($hashType) {
			case USER_PWHASH_NONE:
				if (!isset($salt) || empty($salt)
					$this->userPassword = $newPass;
				else
					$this->userPassword = $salt . ":" . $newPass;
			break;
			case USER_PWHASH_MD5:
				if (!isset($salt) || empty($salt))
					$this->userPassword = md5($newPass);
				else
					$this->userPassword = md5($salt . ":" . $newPass);
			break;
			case USER_PWHASH_SHA1:
				if (!isset($salt) || empty($salt))
					$this->userPassword = sha1($newPass);
				else
					$this->userPassword = sha1($salt . ":" . $newPass);
			break;
			default:
				return FALSE;
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
		else
			unset($this->userMName);
			
		if ($this->userFName != $fname || $this->userLName != $lname)
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * getUserPhone() function
	 * 
	 * No inputs
	 * 
	 * This function returns a formatted phone number for the user
	 * 
	 * Returns the user's phone number, or FALSE
	 */
	public function getUserPhone() {
		//Precondition: userPhone should be set
		//Postcondition: Return the user's phone number, or FALSE
		
		if (!isset($this->userPhone))
			return FALSE;
		
		return $this->userPhone;
	}
	
	/*
	 * setUserPhone($number) function
	 * 
	 * $number => Defines the phone number
	 * 
	 * This function sets the phone number for the user
	 * 
	 * Returns TRUE on success and FALSE on failure
	 */
	public function setUserPhone($number) {
		//Precondition: $number should be set, and be a 10-digit number
		//Postcondition: Set the user's phone number. Return TRUE on success and FALSE on failure.
		
		if (!isset($number))
			return FALSE;
		
		//Remove all non-numeric characters
		$number = preg_replace("/[^0-9]/", "", $number);
		
		if (strlen($number) != 10)
			return FALSE;
			
		$this->userPhone = $number;
		
		return TRUE;
	}
	
	/*
	 * getUserAddress() function
	 * 
	 * No inputs
	 * 
	 * This function returns the formatted address for the user.
	 * 
	 * Returns the address, or FALSE on failure
	 */
	public function getUserAddress() {
		//Precondition: User address should be defined
		//Postcondition: Return the user's address, or FALSE on failure
		
		if (!isset($this->userAddress1) || !isset($this->userCity) || !isset($this->userState) || !isset($this->userZip))
			return FALSE;
		
		return $this->userAddress1 . "\n" . (isset($this->userAddress2) ? $this->userAddress2 . "\n" : "") . $this->userCity . ", " . $this->userState . " " . $this->userZip;
	}
	
	/*
	 * setUserAddress($userAddress1, $userCity, $userState, $userZip, $userAddress2=NULL) function
	 * 
	 * $userAddress1 => Defines the first line of the address
	 * $userCity => Defines the city for the address
	 * $userState => Defines the state for the address
	 * $userZip => Defines the zip code for the address
	 * $userAddress2 => Defines the second line of the address
	 * 
	 * This function sets the user's address.
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setUserAddress($userAddress1, $userCity, $userState, $userZip, $userAddress2=NULL) {
		//Precondition: $userAddress1, $userCity, $userState, $userZip should be set
		//Postcondition: Set the user's address. Return TRUE on success, and FALSE otherwise
		
		if (!isset($userAddress1) || !isset($userCity) || !isset($userState) || !isset($userZip))
			return FALSE;
		
		$this->userAddress1 = $userAddress1;
		if (isset($userAddress2))
			$this->userAddress2 = $userAddress2;
		else
			unset($this->userAddress2);
		$this->userCity = $userCity;
		$this->userState = $userState;
		$this->userZip = $userZip;
		
		return TRUE;
	}
}
?>
