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
	
	public function Error($to, $from, $subject=NULL) {
		//Precondition: Both from and to should be set
		//Postcondition: Set errorFrom and errorTo
		
		if (!isset($from) || !isset($to))
			throw new Exception("Both from, and to, addresses must be set for error handling.\n");
		
		$this->errorFrom = $from;
		$this->errorTo = $to;
		
		if (isset($subject))
			$this->errorSubject = $subject;
	}
	
	public function setErrorSubject($errorSubject) {
		//Preconditoin: $errorSubject should be defined
		//Postcondition: Set the errorSubject
		
		if (!isset($errorSubject))
			return FALSE;
		
		$this->errorSubject = $errorSubject;
		
		return TRUE;
	}
	
	public function doBacktrace() {
		//Precondition: None
		//Postcondition: Do a backtrace on the current execution
		
		ob_start();
		debug_print_backtrace();
		$bt = ob_get_clean();
		
		return $bt;
	}
	
	public function sendError($errorMessage) {
		//Precondition: $errorMessage should be defined
		//Postcondition: Send e-mail to errorTo
		
		if (!isset($errorMessage))
			return FALSE;
		
		echo "Sending message...\nTo: " . $this->errorTo . "\nFrom: " . $this->errorFrom . "\nSubject: " . $this->errorSubject . "\nMessage: " . $errorMessage . "\n\n\n";
		mail($this->errorTo, (isset($this->errorSubject) ? $this->errorSubject : "GCTools Error Message"), $errorMessage, "From: " . $this->errorFrom);
	}
}

class GCErrorHandler extends Error {
	public function GCErrorHandler($to, $from, $subject=NULL) {
		//Precondition: Both from and to should be set
		//Postcondition: Set-up Error class
		
		try {
			$this->Error($to, $from, $subject);
		}
		catch (Exception $e) {
			throw new Exception($e);
		}
	}
	
	public function setGCErrorGlobally($errors=NULL) {
		//Precondition: None
		//Postcondition: Set the PHP error handler to GCErrorHandler->handleError
		
		set_error_handler(array($this, "handleError"), (isset($errors) ? $errors : NULL));
	}
	
	public function handleError($errno, $errstr, $errfile=NULL, $errline=NULL, $errcontext=NULL) {
		//Precondition: $errno and $errstr should be defined
		//Postcondition: Send an e-mail with the information about the error
		
		$errorMessage = "A PHP error has occured with the following conditions:\n\n";
		
		//Handle errorno
		switch($errorno) {
			case E_USER_ERROR:
				$errorMessage .= "FATAL ERROR ";
			break;
			case E_USER_WARNING:
				$errorMessage .= "WARNING ERROR ";
			break;
			case E_USER_NOTICE:
				$errorMessage .= "NOTICE ERROR ";
			break;
			default:
				$errorMessage .= "UNKNOWN ERROR ";
			break;
		}
		
		$errorMessage .= "[" . $errno . "]: " . $errstr . "\n\n";
		
		if (isset($errfile)) {
			$errorMessage .= "Filename: " . $errfile . "\n";
			
			if (isset($errline))
				$errorMessage .= "Line: " . $errline . "\n";
			
			if (isset($errcontext))
				$errorMessage .= "\nError Context:\n" . $errcontext . "\n\n";
		}
		
		//Do a backtrace on the issue
		$errorMessage .= "\n\nPHP Backtrace:\n" . $this->doBacktrace() . "\n";
		
		//Send the message
		$this->sendError($errorMessage);
	}
}

class GCExceptionHandler extends Error {
	public function GCExceptionHandler($to, $from, $subject=NULL) {
		//Precondition: Both from and to should be set
		//Postcondition: Set-up Error class
		
		try {
			$this->Error($to, $from, $subject);
		}
		catch (Exception $e) {
			throw new Exception($e);
		}
	}
	
	public function setGCExceptionGlobally() {
		//Precondition: None
		//Postcondition: Set the PHP exception handler to GCExceptionHandler->handleException
		
		set_error_handler(array($this, "handleException"));
	}
	
	public function handleException($obj) {
		//Precondition: $obj will be defined
		//Postcondition: Send an exception error report
		
		//TODO: Finsh the handling of the exception
	}
}
?>