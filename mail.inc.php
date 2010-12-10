<?php
/*
 * Filename: mail.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide email support
 * @version: 1.1.0
 * File created: 10SEP2010
 * File updated: 03DEC2010
 * @package GCTools
 * @subpackage Mail
 */

//Declare the namespace
//namespace GCTools/Mail;

//Attachment class
class Attachment {
	protected $fileName; //File name
	protected $fileMIMEType; //File MIME type
	protected $fileBuffer; //Holds contents of the file
	protected $fileSize; //Holds the size of the file
	private $noError; //TRUE if there wasn't an error adding the file
	
	//Constructor
	public function Attachment($file) {
		//Add the file
		$this->noError = $this->addFile($file);
	}
	
	/*
	 * isError() function
	 * 
	 * No inputs
	 * 
	 * This function determines if an error has occured.
	 * 
	 * Returns TRUE if an error exists, and FALE otherwise.
	 */
	public function isError() {
		/* Precondition: None */
		/* Postcondition: Return TRUE if an error has occured, and
		 * FALSE if one hasn't.
		 */
		
		return !$this->noError;
	}
	
	/*
	 * getFileName() function
	 * 
	 * No inputs
	 * 
	 * This function returns the filename of the file to be attached.
	 * 
	 * Returns the file name, or FALSE if it was not set.
	 */
	public function getFileName() {
		/* Precondition: A file has been loaded. */
		/* Postcondition: Return the value of fileName */
		
		if(isset($this->fileName))
			return $this->fileName;
		else
			return FALSE;
	}
	
	/*
	 * getMIMEType() function
	 * 
	 * No inputs
	 * 
	 * This function returns the MIME type of the file to be attached.
	 * 
	 * Returns the MIME type, or FALSE if it was not set.
	 */
	public function getMIMEType() {
		/* Precondition: A file has been loaded. */
		/* Postcondition: Return the value of fileMIMEType */
		
		if(isset($this->fileMIMEType))
			return $this->fileMIMEType;
		else
			return FALSE;
	}
	
	/*
	 * getSize() function
	 * 
	 * No inputs
	 * 
	 * This function returns the size of the file to be attached.
	 * 
	 * Returns file size, or FALSE if it was not set.
	 */
	public function getSize() {
		/* Precondition: A file has been loaded. */
		/* Postcondition: The file size is returned. */
		
		if(isset($this->fileSize))
			return $this->fileSize;
		else
			return FALSE;
	}
	
	/*
	 * getFile() function
	 * 
	 * No inputs
	 * 
	 * This function provides the user access to get the buffered string of the file.
	 * 
	 * Returns the buffered file string, or FALSE if it's not set.
	 */
	public function getFile() {
		/* Precondition: A file has been loaded. */
		/* Postcondition: The file in the buffer is returned. */
		
		if(isset($this->fileBuffer))
			return $this->fileBuffer;
		else
			return FALSE;
	}
	
	/*
	 * addFile($file) function
	 * 
	 * $file => Defines the location of the file to be attached
	 * 
	 * This function does the loading of the attachment. It sets most of the vaiables
	 * for the entire class.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	protected function addFile($file) {
		/* Precondition: A file name, with location, is given */
		/* Postcondition: The file is made into an attachment.
		 * Returns FALSE if anything fails.
		 */
		
		//Ensure we have a file, not a directory
		if (!is_file($file))
			return FALSE;
			
		//Get file name
		$this->fileName = basename($file);
		if (empty($this->fileName))
			return FALSE;
		
		//Get file size
		$this->fileSize = filesize($file);
		if ($this->fileSize == 0 || $this->fileSize == FALSE)
			return FALSE;
		
		//Get MIME Type
		//Check if we have the right version to use Fileinfo
		if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 5 && PHP_MINOR_VERSION >= 3) {
			//We have the right version to use Fileinfo
			
			//Create new instance of finfo
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			
			//Add MIME type
			$this->fileMIMEType = finfo_file($finfo, $file);
			if ($this->fileMIMEType == FALSE)
				return FALSE;
			
			//Close finfo class
			finfo_close($finfo);
		}
		else {
			//We don't have the right version, must use a different method
			$this->fileMIMEType = mime_content_type($file);
		}
		
		//Read file into the buffer
		$this->fileBuffer = chunk_split(base64_encode(file_get_contents($file)));
		if (!$this->fileBuffer)
			return FALSE;
		
		//We're done, so return TRUE
		return TRUE;
	}
}

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
	 * No return
	 */
	public function addTo($address) {
		/* Precondition: An e-mail address is provided */
		/* Postcondition: The address is added to the list
		 * of TO addresses.
		 */
		
		//TODO: Add address validation
		
		array_push($this->mailTo, $address);
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
	 * No return
	 */
	public function addCC($address) {
		/* Precondition: An e-mail address is provided */
		/* Postcondition: The address is added to the list
		 * of CC addresses.
		 */
		
		//TODO: Add address validation
		
		array_push($this->mailCC, $address);
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
	 * No return
	 */
	public function addBCC($address) {
		/* Precondition: An e-mail address is provided */
		/* Postcondition: The address is added to the list
		 * of BCC addresses.
		 */
		
		//TODO: Add address validation
		
		array_push($this->mailBCC, $address);
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
		
		//TODO: Add address validation
		
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
		
		//TODO: Add address validation
		
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
		
		//Attempt to add the attachment
		$newAttachment = new Attachment($file);
		if ($newAttachment->isError())
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
}
?>
