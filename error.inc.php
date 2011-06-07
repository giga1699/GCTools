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
	
	protected function doBacktrace() {
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
		
		mail($this->errorTo, (isset($this->errorSubject) ? $this->errorSubject : "GCTools Error Message"), $errorMessage, "From: " . $this->errorFrom);
	}
}

class GCErrorHandler extends Error {
	protected $errorHandlerSet;
	protected $exceptionHandlerSet;
	
	public function GCErrorHandler($to, $from, $subject=NULL) {
		//Precondition: Both from and to should be set
		//Postcondition: Set-up Error class
		
		try {
			$this->Error($to, $from, $subject);
		}
		catch (Exception $e) {
			throw new Exception($e);
		}
		
		$this->errorHandlerSet = FALSE;
		$this->exceptionHandlerSet = FALSE;
	}
	
	public function setGCErrorGlobally($errors=NULL) {
		//Precondition: errorHandlerSet should be false
		//Postcondition: Set the PHP error handler to GCErrorHandler->handleError.
		//    Returns TRUE on success, or FALSE otherwise
		
		if ($this->errorHandlerSet == TRUE)
			return FALSE;
		
		set_error_handler(array($this, "handleError"), (isset($errors) ? $errors : E_ALL | E_STRICT));
		
		$this->errorHandlerSet = TRUE;
		
		return TRUE;
	}
	
	public function unsetGCErrorGlobally() {
		//Precondition: Error handler should've been set already
		//Postcondition: Restore the old error handler. Returns TRUE on success, and FALSE otherwise
		
		if ($this->errorHandlerSet === FALSE)
			return FALSE;
		
		restore_error_handler();
		$this->errorHandlerSet = FALSE;
		
		return TRUE;
	}
	
	public function handleError($errno, $errstr, $errfile=NULL, $errline=NULL, $errcontext=NULL) {
		//Precondition: $errno and $errstr should be defined
		//Postcondition: Send an e-mail with the information about the error
		
		$errorMessage = "A PHP error has occured with the following conditions:\n\n";
		
		//Handle errorno
		switch($errno) {
			case E_USER_ERROR:
			case E_RECOVERABLE_ERROR:
				$errorMessage .= "FATAL ERROR ";
			break;
			case E_USER_WARNING:
			case E_WARNING:
				$errorMessage .= "WARNING ERROR ";
			break;
			case E_USER_NOTICE:
			case E_NOTICE:
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
		
		//Die if fatal error
		if ($errno == E_USER_ERROR || $errno == E_RECOVERABLE_ERROR)
			die("Fatal Error. Please contact your IT support staff.");
	}
	
	public function setGCExceptionGlobally() {
		//Precondition: None
		//Postcondition: Set the PHP exception handler to GCExceptionHandler->handleException.
		//    Returns TRUE on success, or FALSE otherwise
		
		if ($this->exceptionHandlerSet == TRUE)
			return FALSE;
		
		if (set_exception_handler(array($this, "handleException")) == NULL)
			return FALSE;
		
		$this->exceptionHandlerSet = TRUE;
		
		return TRUE;
	}
	
	public function unsetGCExceptionGlobally() {
		//Precondition: Exception handler should've been set
		//Postcondition: Return to the old handler. Return TRUE on success, and FALSE otherwise
		
		if ($this->exceptionHandlerSet === FALSE)
			return FALSE;
		
		restore_exception_handler();
		$this->exceptionHandlerSet = FALSE;
		
		return TRUE;
	}
	
	public function handleException($obj) {
		//Precondition: $obj will be defined
		//Postcondition: Send an exception error report
		
		$exceptionMessage = "A PHP exception has occured with the following conditions:\n\n";
		
		//Get the message
		$exceptionMessage .= "Exception message: " . $obj->getMessage() . "\n\n";
		
		//File and line info
		$exceptionMessage .= "File: " . $obj->getFile() . "\nLine: " . $obj->getLine() . "\n\n";
		
		//Do backtrace
		$exceptionMessage .= "PHP Backtrace:\n" . $this->doBacktrace() . "\n";
		
		//Send the message
		$this->sendError($exceptionMessage);
	}
}
?>