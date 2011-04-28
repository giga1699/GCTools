<?php
/**
 * Filename: file.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide file functionality.
 * @version 0.0.1
 * File created: 28APR2011
 * File modified: 28APR2011
 * @package GCTools
 * @subpackage File
 * 
 * Changelog:
 */

//Declare the namespace
//namespace GCTools/File;

class File {
	protected $fileName;
	protected $fileMIMEType;
	protected $fileBuffer;
	protected $fileSize;
	protected $fileNoError;
	
	public function File($file) {
		//Add file
		$this->fileNoError = $this->addFile($file);
	}
	
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
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			
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
	 * hadError() function
	 * 
	 * No inputs
	 * 
	 * This function determines if there was an error in adding the file.
	 * 
	 * Returns TRUE if error occured, and FALSE otherwise
	 */
	public function hadError() {
		//Precondition: None
		//Postcondition: Returns TRUE if error occured, and FALSE otherwise
		
		if ($this->fileNoError)
			return FALSE;
		else
			return TRUE;
	}
}

?>