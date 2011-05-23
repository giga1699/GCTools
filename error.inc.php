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
	
	public function Error($from, $to) {
		//Precondition: Both from and to should be set
		//Postcondition: Set errorFrom and errorTo
		
		if (!isset($from) || !isset($to))
			throw new Exception("Both from, and to, addresses must be set for error handling.\n");
		
		$this->errorFrom = $from;
		$this->errorTo = $to;
	}
	
	public function sendError($errorMessage, $errorSubject=NULL) {
		//Precondition: $errorMessage should be defined
		//Postcondition: Send e-mail to errorTo
		
		if (!isset($errorMessage))
			return FALSE;
		
		mail($this->errorTo, (isset($errorSubject) ? $errorSubject : "GCTools Error Message"), $errorMessage, "From: " . $this->errorFrom);
	}
}