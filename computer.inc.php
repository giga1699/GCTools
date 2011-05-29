<?php
/**
 * Filename: computer.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide the ability to abstract a computer system
 * @version 0.0.1
 * File created: 25MAY2011
 * @package GCTools
 * @subpackage Computer
 */

//Define namespace
//namespace GCTools/Computer;

//Operating System Types
define("COMP_OS_WIN", 0);
define("COMP_OS_LIN", 1);
define("COMP_OS_MAC", 2);
define("COMP_OS_UNI", 3);

class Computer {
	protected $id; //A unique computer ID
	protected $name; //The computer's name
	protected $mac; //The computer's MAC address
	protected $ip; //The IPv4 address of the computer
	protected $ip6; //The IPv6 address of the computer
	protected $osType; //The operating system type of the computer
	protected $osName; //The operating system's name
	protected $serial; //Serial number for the computer
	protected $location; //Location of the computer
	protected $make; //The make of the computer
	protected $model; //The model of the computer
	protected $cpu; //CPU information
	protected $ram; //RAM information
	protected $hdd; //Hard drive information
	protected $licensing; //Licence information
	protected $notes; //Additional notes about the system
	
	public function Computer() {
		//Class constructor isn't used at this time
	}
	
	public function getID() {
		//Precondition: id should be set
		//Postcondition: Return the ID, or FALSE if none is set
		
		if (!isset($this->id))
			return FALSE;
		
		return $this->id;
	}
	
	public function setID($newID) {
		//Precondition: $newID should be set
		//Postcondition: Return TRUE if ID was set, and FALSE otherwise
		
		if (!isset($newID))
			return FALSE;
		
		$this->id = $newID;
		
		if ($this->id = $newID)
			return TRUE;
		else
			return FALSE;
	}
	
	public function getName() {
		//Precondition: name should be set
		//Postcondition: Return computer name, or FALSE
		
		if (!isset($this->name))
			return FALSE;
		
		return $this->name;
	}
	
	public function setName($newName) {
		//Precondition: $newName should be set
		//Postcondition: Set the computer name. Return TRUE on success, and FALSE otherwise
		
		if (!isset($newName))
			return FALSE;
		
		$this->name = $newName;
		
		if ($this->name = $newName)
			return TRUE;
		else
			return FALSE;
	}
}