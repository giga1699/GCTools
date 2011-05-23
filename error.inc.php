<?php
/**
 * Filename: user.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide error handling.
 * @version 0.0.1
 * File created: 23MAY2011
 * @package GCTools
 * @subpackage Error
 */

//Define namespace
//namespace GCTools/Error;

class Error {
	protected $errorFrom; //E-mail to show as from address when sending error messages
	protected $errorTo; //E-mail to send error messages
	protected $errorSubject; //E-Mail subject when sending errors
	
	public function Error($from, $to) {
		//Precondition: Both from and to should be set
		//Postcondition: Set errorFrom and errorTo
		
		if (!isset($from) || !isset($to))
			throw new Exception("Both from, and to, addresses must be set for error handling.\n");
		
		$this->errorFrom = $from;
		$this->errorTo = $to;
	}
	
	public function setErrorSubject($errorSubject) {
		if (!isset($errorSubject))
			return FALSE;
		
		$this->errorSubject = $errorSubject;
		
		return TRUE;
	}
	
	public function sendError($errorMessage) {
		//Precondition: $errorMessage should be defined
		//Postcondition: Send e-mail to errorTo
		
		if (!isset($errorMessage))
			return FALSE;
		
		mail($this->errorTo, (isset($this->errorSubject) ? $this->errorSubject : "GCTools Error Message"), $errorMessage, "From: " . $this->errorFrom);
	}
}