<?php
/**
 * Filename: mail.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide email support
 * @version 1.1.1
 * File created: 10SEP2010
 * @package GCTools
 * @subpackage Mail
 */

//Declare the namespace
//namespace GCTools/Mail;

//Mail class
class EMail {
	protected $mailTo; //Array: email addresses
	protected $mailCC; //Array: email addresses
	protected $mailBCC; //Array: email addresses
	protected $mailFrom; //String: email address
	protected $mailReplyTo; //String: email address
	protected $mailSubject; //String: Subject for email
	protected $mailMessage; //String: Text to send in email
	protected $mailAttachments; //Array of class Attachment
	protected $mailAddlHeaders; //String: Any additional headers the user wishes to add
	protected $mailSign; //Should this e-mail be digitally signed
	protected $mailEncrypt; //Should this e-mail be encrypted
	private $mailSplit; //Used for attachments
	
	//Constructor
	public function EMail() {
		/* Precondition: None */
		/* Postcondition: Class is set up */
		
		//Create arrays
		$this->mailTo = array();
		$this->mailCC = array();
		$this->mailBCC = array();
		$this->mailAttachments = array();
		
		//Initialize strings
		$this->mailFrom = "";
		$this->mailReplyTo = "";
		$this->mailSubject = "";
		$this->mailMessage = "";
		$this->mailAddlHeaders = "";

		//Set boolean values
		$this->mailSign = FALSE;
		$this->mailEncrypt = FALSE;
		
		//Set-up split for attachments
		$random_hash = md5(date('r', time()));
		$this->mailSplit = "PHP-mixed-".$random_hash;
	}
	
	/*
	 * addTo($address) function
	 * 
	 * $address => A single e-mail address to be added.
	 * 
	 * This function adds the given address to the array of addresses to
	 * be used in the "TO:" field of the email.
	 * 
	 * Returns TRUE on success, and FALSE otherwise.
	 */
	public function addTo($address) {
		/* Precondition: An e-mail address is provided */
		/* Postcondition: The address is added to the list
		 * of TO addresses.
		 */
		
		if (!$this->validEmail($address))
			return FALSE;
		
		array_push($this->mailTo, $address);
		
		return TRUE;
	}
	
	/*
	 * formatTo() function
	 * 
	 * No inputs
	 * 
	 * This function formats all the added "TO:" addresses to be used
	 * by the mail() function.
	 * 
	 * Returns the formatted address on success, and FALSE on failure.
	 */
	private function formatTo() {
		//THIS FUNCTION SHOULD NOT BE CHANGED
		/* Precondition: TO values should've been set. */
		/* Postcondition: Will format the TO addresses
		 * so that they can be sent to the PHP mail()
		 * function. Will return FALSE if no addresses
		 * have been set.
		 */
		
		//Check that there is a TO address
		if (empty($this->mailTo))
			return FALSE;
		
		//Format the TO addresses
		$addresses="";
		for ($i=0;$i<count($this->mailTo);$i++) {
			$addresses .= $this->mailTo[$i];
			
			//Check if there is one more address remaining
			if ($i != (count($this->mailTo)-1)) {
				//Since there are more addresses remaining, add a comma
				$addresses .= ",";
			}
		}
		
		//Return addresses to the caller
		return $addresses;
	}
	
	/*
	 * addCC($address) function
	 * 
	 * $address => A single e-mail address to be added.
	 * 
	 * This function adds the given address to the array of addresses to
	 * be used in the "CC:" field of the email.
	 * 
	 * Returns TRUE on success, and FALSE otherwise.
	 */
	public function addCC($address) {
		/* Precondition: An e-mail address is provided */
		/* Postcondition: The address is added to the list
		 * of CC addresses.
		 */
		
		if (!$this->validEmail($address))
			return FALSE;
		
		array_push($this->mailCC, $address);
		
		return TRUE;
	}
	
	/*
	 * formatCC() function
	 * 
	 * No inputs
	 * 
	 * This function formats all the added "CC:" addresses to be used
	 * by the mail() function.
	 * 
	 * Returns the formatted address on success, and FALSE on failure.
	 */
	private function formatCC() {
		//THIS FUNCTION SHOULD NOT BE CHANGED
		/* Precondition: CC values should've been set. */
		/* Postcondition: Will format the CC addresses
		 * so that they can be sent to the PHP mail()
		 * function. Will return FALSE if no addresses
		 * have been set.
		 */
		
		//Check that there is a TO address
		if (empty($this->mailCC))
			return FALSE;
		
		//Format the TO addresses
		$addresses="";
		for ($i=0;$i<count($this->mailCC);$i++) {
			$addresses .= $this->mailCC[$i];
			
			//Check if there is one more address remaining
			if ($i != (count($this->mailCC)-1)) {
				//Since there are more addresses remaining, add a comma
				$addresses .= ",";
			}
		}
		
		//Return addresses to the caller
		return $addresses;
	}
	
	/*
	 * addBCC($address) function
	 * 
	 * $address => A single e-mail address to be added.
	 * 
	 * This function adds the given address to the array of addresses to
	 * be used in the "BCC:" field of the email.
	 * 
	 * Returns TRUE on success, and FALSE otherwise.
	 */
	public function addBCC($address) {
		/* Precondition: An e-mail address is provided */
		/* Postcondition: The address is added to the list
		 * of BCC addresses.
		 */
		
		if (!$this->validEmail($address))
			return FALSE;
		
		array_push($this->mailBCC, $address);
		
		return TRUE;
	}
	
	/*
	 * formatBCC() function
	 * 
	 * No inputs
	 * 
	 * This function formats all the added "BCC:" addresses to be used
	 * by the mail() function.
	 * 
	 * Returns the formatted address on success, and FALSE on failure.
	 */
	private function formatBCC() {
		//THIS FUNCTION SHOULD NOT BE CHANGED
		/* Precondition: BCC values should've been set. */
		/* Postcondition: Will format the BCC addresses
		 * so that they can be sent to the PHP mail()
		 * function. Will return FALSE if no addresses
		 * have been set.
		 */
		
		//Check that there is a TO address
		if (empty($this->mailBCC))
			return FALSE;
		
		//Format the TO addresses
		$addresses="";
		for ($i=0;$i<count($this->mailBCC);$i++) {
			$addresses .= $this->mailBCC[$i];
			
			//Check if there is one more address remaining
			if ($i != (count($this->mailBCC)-1)) {
				//Since there are more addresses remaining, add a comma
				$addresses .= ",";
			}
		}
		
		//Return addresses to the caller
		return $addresses;
	}
	
	/*
	 * setFrom($address) function
	 * 
	 * $address => Defines the address to be used for the "FROM:" field
	 * 
	 * This function sets the class variable to be used as the "FROM:" field
	 * when using the mail() function.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setFrom($address) {
		/* Precondition: An address is provided. */
		/* Postcondition: The from address is set. */
		
		if (!$this->validEmail($address))
			return FALSE;
		
		//Unset the current variable
		unset($this->mailFrom);
		
		$this->mailFrom = $address;
		
		if (isset($this->mailFrom))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getFrom() function
	 * 
	 * No input
	 * 
	 * This function returns the stored variable for the "FROM:" field.
	 * 
	 * Returns the "FROM:" address on success, and FALSE on failure.
	 */
	public function getFrom() {
		/* Precondition: The From address should be defined. */
		/* Postcondition: The From address is returned. */
		
		if (isset($this->mailFrom))
			return $this->mailFrom;
		else
			return FALSE;
	}
	
	/*
	 * setReplyTo($address) function
	 * 
	 * $address => Defines the address to be used as the "Reply-to:" field
	 * 
	 * This function sets the class variable so that it can be used in the mail() function.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setReplyTo($address) {
		/* Precondition: An address is provided. */
		/* Postcondition: The reply-to address is set. */
		
		if (!$this->validEmail($address))
			return FALSE;
		
		//Unset the variable
		unset($this->mailReplyTo);
		
		$this->mailReplyTo = $address;
		
		if (isset($this->mailReplyTo))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getReplyTo() function
	 * 
	 * No input
	 * 
	 * This function returns the "Reply-To:" field.
	 * 
	 * Returns the "Reply-To:" field, or FALSE if it is not set.
	 */
	public function getReplyTo() {
		/* Precondition: A Reply-To address should be defined. */
		/* Postcondition: The reply-to address is returned. */
		
		if (isset($this->mailReplyTo))
			return $this->mailReplyTo;
		else
			return FALSE;
	}
	
	/*
	 * setSubject($subject) function
	 * 
	 * $subject => Defines the subject of the mail message
	 * 
	 * This function sets the subject of the message to be used in the mail() function.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setSubject($subject) {
		/* Precondition: A subject is provided. */
		/* Postcondition: The subject for the email is set. */
		
		//Unset previous subject
		unset($this->mailSubject);
		
		//Set subject
		$this->mailSubject = $subject;
		
		if(isset($this->mailSubject))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getSubject() function
	 * 
	 * No input
	 * 
	 * This function returns the currently set subject of the message
	 * 
	 * Returns the subject if set, and FALSE if it's not set.
	 */
	public function getSubject() {
		/* Precondition: Subject should be set. */
		/* Postcondition: Subject is returned. */
		
		if(isset($this->mailSubject))
			return $this->mailSubject;
		else
			return FALSE;
	}
	
	/*
	 * setMessage($message) function
	 * 
	 * $message => The content to be set as the body of the message
	 * 
	 * This function sets the body of the message.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setMessage($message) {
		/* Precondition: A message is provided. */
		/* Postcondition: The message for the email is set. */
		
		//Unset current message
		unset($this->mailMessage);
		
		$this->mailMessage = $message;
		
		if (isset($this->mailMessage))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getMessage() function
	 * 
	 * No input
	 * 
	 * This function gives the user a copy of the body of the mail message.
	 * 
	 * Returns the body of the message, or FALSE if it's not set.
	 */
	public function getMessage() {
		/* Precondition: A message has been set. */
		/* Postcondition: The message that has been set
		 * is returned. Will return FALSE if no message
		 * has been set.
		 */
		
		if (isset($this->mailMessage))
			return $this->mailMessage;
		else
			return FALSE;
	}
	
	/*
	 * addAttachment($file) function
	 * 
	 * $file => Defines the location of the file to be added
	 * 
	 * This function creates a new instance of the Attachment class, and
	 * adds it to the array of files to be sent with the message.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function addAttachment($file) {
		/* Precondition: A file is provided. */
		/* Postcondition: A file is added to the attachment
		 * list, and returns FALSE on fail.
		 */
		
		//Requires File class
		require_once("file.inc.php");
		
		//Attempt to add the attachment
		$newAttachment = new GCFile($file);
		if ($newAttachment->hadError())
			return FALSE;
		
		//Add the attachement to the list
		array_push($this->mailAttachments, $newAttachment);
		
		return TRUE;
	}
	
	/*
	 * setAddlHeaders($headers) function
	 * 
	 * $headers => Defines additional headers to be sent along with the mail message.
	 * 
	 * This function adds additional headers to the message being sent. Please ensure they
	 * are done properly, and according to RFC2822 standard. It is important that multiple
	 * headers are separated with "\r\n".
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setAddlHeaders($headers) {
		/* Precondition: Additional headers are provided. */
		/* Postcondition: The additional headers are added to
		 * the class.
		 */
		
		//Unset current headers
		unset($this->mailAddlHeaders);
		
		$this->mailAddlHeaders = $headers;
		
		if(isset($this->mailAddlHeaders))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getAddlHeaders() function
	 * 
	 * No input
	 * 
	 * This function gives the user a copy of the additional headers to be added to the
	 * mail message.
	 * 
	 * Returns the headers if they are set, and FALSE if they are not set.
	 */
	public function getAddlHeaders() {
		/* Precondition: Additional headers should be defined. */
		/* Postcondition: Additional headers are returned. */
		
		if(isset($this->mailAddlHeaders))
			return $this->mailAddlHeaders;
		else
			return FALSE;
	}
	
	/*
	 * getHeaders() function
	 * 
	 * No input
	 * 
	 * This function returns the formatted headers to be used by the mail() function.
	 * 
	 * Returns the headers on success, and FALSE on failure.
	 */
	protected function getHeaders() {
		/* Precondition: None */
		/* Postcondition: Formatted headers are returned. */
		
		//Set up Headers
		$headers = "";
		
		//Add CC header
		if (isset($this->mailCC) && !empty($this->mailCC))
			$headers .= (empty($headers) ? "" : "\r\n")."CC: ".$this->formatCC();
		
		//Add BCC header
		if (isset($this->mailBCC) && !empty($this->mailBCC))
			$headers .= (empty($headers) ? "" : "\r\n")."BCC: ".$this->formatBCC();
		
		//Add FROM header
		if (isset($this->mailFrom) && !empty($this->mailFrom))
			$headers .= (empty($headers) ? "" : "\r\n")."From: ".$this->mailFrom.(empty($this->mailReplyTo) ? "\r\nReturn-Path: ".$this->mailFrom : "");

		//Add Reply-To header
		if (isset($this->mailFrom) && !empty($this->mailReplyTo))
			$headers .= (empty($headers) ? "" : "\r\n")."Reply-To: ".$this->mailReplyTo."\r\nReturn-Path: ".$this->mailReplyTo;
		
		//Add X-Mailer header
		$headers .= (empty($headers) ? "" : "\r\n")."X-Mailer: GCTools/Mailer";
		
		//Add additional headers
		if (isset($this->mailAddlHeaders) && !empty($this->mailAddlHeaders))
			$headers .= (empty($headers) ? "" : "\r\n").$this->mailAddlHeaders;
		
		//Add content-type header
		if (isset($this->mailAttachments) && !empty($this->mailAttachments)) {
			$headers .= (empty($headers) ? "" : "\r\n")."MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"".$this->mailSplit."\"";
		}
		else
			$headers .= (empty($headers) ? "" : "\r\n")."Content-Type: text/html; charset=\"iso-8859-1\"";
			
		//Return headers
		if (isset($headers) && !empty($headers))
			return $headers;
		else
			return FALSE;
	}
	
	/*
	 * send() function
	 * 
	 * No input
	 * 
	 * This function sends the mail message based off the current settings.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function send() {
		/* Precondition: TO should be set. */
		/* Postcondition: Message is sent. Returns FALSE on fail. */
		
		//Try to set up TO addresses
		$toAddresses = $this->formatTo();
		if (!$toAddresses)
			return FALSE;

		//Set subject to "(no subject)" if empty or unset
		if (!isset($this->mailSubject) || empty($this->mailSubject))
			$this->setSubject("(no subject)");
		
		//Set message body to "" if unset
		if (!isset($this->mailMessage))
			$this->setMessage("");
			
		//Set up message body
		$messageBody = "";
		
		//Add attachments, if they exist. Otherwise, just add the message body
		if (!empty($this->mailAttachments)) {
			$messageBody = "--".$this->mailSplit."\r\nContent-Type: text/html; charset=\"iso-8859-1\"\r\n\r\n";
			$messageBody .= $this->getMessage();
			
			//Add attachments individually
			for ($i=0;$i<count($this->mailAttachments);$i++) {
				$messageBody .= "\r\n--".$this->mailSplit."\r\n";
				$messageBody .= "Content-Type: ".$this->mailAttachments[$i]->getMIMEType()."; name=\"".$this->mailAttachments[$i]->getFileName()."\"\r\n";
				$messageBody .= "Content-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"".$this->mailAttachments[$i]->getFileName()."\"\r\n\r\n";
				$messageBody .= $this->mailAttachments[$i]->getFile();
			}
			$messageBody .= "\r\n--".$this->mailSplit."--\r\n";
		}
		else
			$messageBody = $this->getMessage();
			
		echo "<pre>".$messageBody."</pre>";
		
		//Send message
		if (mail($toAddresses, $this->getSubject(), $messageBody, $this->getHeaders()))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * validEmail($address) function
	 * 
	 * $address => An email address to check if valid
	 * 
	 * This function ensures that a given address is valid.
	 * 
	 * Returns TRUE if the email is valid, and FALSE otherwise.
	 */
	public function validEmail($address) {
		//Precondition: An address is given.
		//Postcondition: Returns TRUE if the address is valid, and FALSE otherwise.
		
		//Ensure an address was given
		if (!isset($address) || empty($address))
			return FALSE;
		
		//Check format of address
		if (!preg_match("/^([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4})$/" , $address))
			return FALSE;
		
		//Get username/domain of address
		list($username, $domain) = split("@", $address);
		
		//Ensure there is a MX entry for the domain
		if (!checkdnsrr($domain, "MX"))
			return FALSE;
		
		//TODO: Make sure we can get to the SMTP server
		
		//If we've gotten this far, it should be a valid email
		return TRUE;
	}

	/*
	 * signEmail() function
	 *
	 * This function will require the message to be digitally signed.
	 *
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function signEmail() {
		//Precondition: None
		//Postcondition: Set mailSign to TRUE

		$this->mailSign = TRUE;

		if ($this->mailSign === TRUE)
			return TRUE;
		else
			return FALSE;
	}

	/*
	 * unsignEmail() function
	 *
	 * This function stops the e-mail from being digitally signed.
	 *
	 * Returns TRUE on success, and FALSE otherwise.
	 */
	public function unsignEmail() {
		//Precondition: None
		//Postcondition: Set mailSign to FALSE

		$this->mailSign = FALSE;

		if ($this->mailSign === FALSE)
			return TRUE;
		else
			return FALSE;
	}

	/*
	 * encryptEmail() function
	 *
	 * This function will require the message to be encrypted.
	 *
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function encryptEmail() {
		//Precondition: None
		//Postcondition: Set mailEncrypt to TRUE

		$this->mailEncrypt = TRUE;

		if ($this->mailEncrypt === TRUE)
			return TRUE;
		else
			return FALSE;
	}

	/*
	 * unencryptEmail() function
	 *
	 * This function stops the e-mail from being encrypted.
	 *
	 * Returns TRUE on success, and FALSE otherwise.
	 */
	public function unencryptEmail() {
		//Precondition: None
		//Postcondition: Set mailEncrypt to FALSE

		$this->mailEncrypt = FALSE;

		if ($this->mailEncrypt === FALSE)
			return TRUE;
		else
			return FALSE;
	}
}
?>
