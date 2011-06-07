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
	
	public function getMac() {
		//Precondition: mac should be definied
		//Postcondition: Return MAC address, or FALSE otherwise
		
		if (!isset($this->mac))
			return FALSE;
		
		return $this->mac;
	}
	
	public function setMac($macAddr) {
		//Precondition: $macAddr should be definied, and a proper MAC address
		//Postcondition: Set the MAC address. Return TRUE on success, and FALSE otherwise.
		
		if (!isset($macAddr))
			return FALSE;
		
		//Remove all non-MAC characters
		$macAddr = preg_replace("/[^0-9A-Fa-f]/", "", $macAddr);
		
		//Check that it is a MAC address
		if (strlen($macAddr) != 12)
			return FALSE;
		
		//Set the MAC address
		$this->mac = $macAddr;
		
		return TRUE;
	}
	
	public function getIP() {
		//Precondition: ip should be definied
		//Postcondidion: Return the IP, or FALSE otherwise
		
		if (!isset($this->ip))
			return FALSE;
		
		return $this->ip;
	}
	
	public function setIP($ipAddr) {
		//Precondition: $ipAddr should be definied, and a valid IP address
		//Postcondition: Set IP address. Return TRUE on success, and FALSE otherwise
		
		//Check for preconditions
		if (!isset($ipAddr) || !preg_match("/^([0-9]{1,3}\.){3}([0-9]{1,3})$/", $ipAddr))
			return FALSE;
		
		//Set the ip address
		$this->ip = $ipAddr;
		
		return TRUE;
	}
	
	public function getIP6() {
		//Precondition: ip6 should be defined
		//Postcondition: Return the IPv6 address, or FALSE otherwise
		
		if (!isset($this->ip6))
			return FALSE;
		
		return $this->ip6;
	}
	
	public function setIP6($ip6) {
		//Precondition: $ip6 should be defined, and a valid IPv6 address
		//Postcondition: Set the IPv6 address. Return TRUE on success, and FALSE otherwise
		
		//TODO: Add precondition checking for valid IPv6 address
		if (!isset($ip6))
			return FALSE;
		
		$this->ip6 = $ip6;
		
		return TRUE;
	}
	
	public function getType() {
		//Precondition: osType should be defined
		//Postcondition: Return the OS type of the computer, or FALSE otherwise
		
		if (!isset($this->osType))
			return FALSE;
		
		return $this->osType;
	}
	
	public function setType($ostype) {
		//Precondition: $ostype should be defined, and valid
		//Postcondition: Set the OS type, and return TRUE on success or FALSE otherwise.
		
		//TODO: Validate $ostype
		if (!isset($ostype))
			return FALSE;
		
		$this->osType = $ostype;
		
		return TRUE;
	}
	
	public function getOSName() {
		//Precondition: osName should be defined
		//Postcondition: Return the OS name, or FALSE otherwise
		
		if (!isset($this->osName))
			return FALSE;
		
		return $this->osName;
	}
	
	public function setOSName($osname) {
		//Precondition: $osname should be defined, and valid
		//Postcondition: Set the OS name. Return TRUE on success, and FALSE otherwise.
		
		//TODO: Add $osname validation
		if (!isset($osname))
			return FALSE;
		
		$this->osName = $osname;
		
		return TRUE;
	}
	
	public function getSerial() {
		//Precondition: serial should be defined
		//Postcondition: Return the serial, or FALSE otherwise
		
		if (!isset($this->serial))
			return FALSE;
		
		return $this->serial;
	}
	
	public function setSerial($serial) {
		//Precondition: $serial should be defined
		//Postcondition: Set the serial. Return TRUE on success, and FALSE otherwise.
		
		if (!isset($serial))
			return FALSE;
		
		$this->serial = $serial;
		
		return $this->serial;
	}
	
	public function getLocation() {
		//Precondition: location should be defined
		//Postcondition: Return the location, or FALSE otherwise
		
		if (!isset($this->location))
			return FALSE;
		
		return $this->location;
	}
	
	public function setLocation($loc) {
		//Precondition: $loc should be defined
		//Postcondition: Set the location. Return TRUE on success, and FALSE otherwise
		
		if (!isset($loc))
			return FALSE;
		
		$this->location = $loc;
		
		return TRUE;
	}
	
	public function getMake() {
		//Precondition: make should be defined
		//Postcondition: Return the make of the computer, or FALSE otherwise
		
		if (!isset($this->make))
			return FALSE;
		
		return $this->make;
	}
	
	public function setMake($make) {
		//Precondition: $make should be defined
		//Postcondition: Set the make of the computer. Return TRUE on success, and FALSE otherwise
		
		if (!isset($make))
			return FALSE;
		
		$this->make = $make;
		
		return TRUE;
	}
	
	public function getModel() {
		//Precondition: model should be defined
		//Postcondition: Return the model of the computer, or FALSE othewise
		
		if (!isset($this->model))
			return FALSE;
		
		return $this->model;
	}
	
	public function setModel($model) {
		//Precondition: $model should be defined
		//Postconditoin: Set the model of the computer. Return TRUE on success, and FALSE otherwise
		
		if (!isset($model))
			return FALSE;
		
		$this->model = $model;
		
		return TRUE;
	}
	
	public function getCPU() {
		//Precondition: cpu should be defined
		//Postcondition: Return CPU info, or FALSE otherwise
		
		if (!isset($this->cpu))
			return FALSE;
		
		return $this->cpu;
	}
	
	public function setCPU($cpu) {
		//Precondition: $cpu should be defined
		//Postcondition: Set the CPU information, and return TRUE on success or FALSE otherwise
		
		if (!isset($cpu))
			return FALSE;
		
		$this->cpu = $cpu;
		
		return TRUE;
	}
	
	public function getRAM() {
		//Precondition: ram should be defined
		//Postcondition: Return ram, or FALSE otherwise
		
		if (!isset($this->ram))
			return FALSE;
		
		return $this->ram;
	}
	
	public function setRAM($ram) {
		//Precondition: $ram should be defiend
		//Postcondition: Set RAM information. Return TRUE on success, and FALSE otherwise
		
		if (!isset($ram))
			return FALSE;
		
		$this->ram = $ram;
		
		return TRUE;
	}
	
	public function getHDD() {
		//Precondition: hdd should be defined
		//Postcondition: Return HDD information, or FALSE otherwise
		
		if (!isset($this->hdd))
			return FALSE;
		
		return $this->hdd;
	}
	
	public function setHDD($hddInfo) {
		//Precondition: $hddInfo should be defiend
		//Postcondition: Set the HDD information. Return TRUE on success and FALSE otherwise
		
		if (!isset($hddInfo))
			return FALSE;
		
		$this->hdd = $hddInfo;
		
		return TRUE;
	}
	
	public function getLicensing() {
		//Precondition: licensing should be defined
		//Postcondition: Return licensing, or FALSE otherwise
		
		if (!isset($this->licensing))
			return FALSE;
		
		return $this->licensing;
	}
	
	public function setLicensing($license) {
		//Precondition: $license should be defined
		//Postcondition: Set the licensing information. Return TRUE on success, and FALSE otherwise
		
		if (!isset($license))
			return FALSE;
	
		$this->licensing = $license;
		
		return TRUE;
	}
	
	public function getNotes() {
		//Precondition: notes should be defined
		//Postcondition: Return notes, or FALSE otherwise
		
		if (!isset($this->notes))
			return FALSE;
		
		return $this->notes;
	}
	
	public function setNotes($notes) {
		//Precondition: $notes should be defined
		//Postcondition: Set notes. Return TRUE on success, and FALSE otherwise
		
		if (!isset($notes))
			return FALSE;
		
		$this->notes = notes;
		
		return TRUE;
	}
}
?>