<?php
/*
 * Filename: photo.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide phto support
 * @version: 0.0.1
 * File created: 02DEC10
 * File updated: 03DEC10
 * @package GCTools
 * @subpackage Photo
 */

//namespace GCTools/Photo;

//Picture types
define("PT_JPG", 0);
define("PT_GIF", 1);
define("PT_PNG", 2);

class Picture {
	//Variables for picture class
	protected $picLoc; //Holds the file location of file (Not required)
	protected $picBuffer; //Holds the buffer
	protected $picSize; //Size of picture (in bytes)
	protected $picHeight; //In pixels
	protected $picWidth; //In pixels	
	protected $picType;
	protected $picName; //Friendly name (Not required)
	protected $picGDRes; //Image resource for GD
	
	//Constructor
	public function Picture($loc=NULL) {
		//Precondition: None, or a file location is given
		//Postcondition: If file location is given, the file is loaded. Otherwise, the class should just be created
		
		//Load picture if loc is given
		if ($loc != NULL)
			return $this->loadFromFile($loc);
		else		
			return TRUE;
	}
	
	//Load from file
	//This is just a helper function, and may be used in an extended class
	protected function loadFromFile($file) {
		//Precondition: A valid file location is given
		//Postcondition: The file is loaded into GD, and most of the class attributes are filled
		//Ensure file exists
		if (!file_exists($file))
			return FALSE;
		
		//Get file name
		$this->picName = basename($file);
		
		//Get file size
		$this->picSize = filesize($file);
		
		//Determine file type
		switch(mime_content_type($file)) {
			case "image/jpeg":
				$this->picType = PT_JPG;
				
				//Import into GD, and set the resource
				$this->picGDRes = imagecreatefromjpeg($file);
			break;
			case "image/gif":
				$this->picType = PT_GIF;
				
				//Import into GD, and set the resource
				$this->picGDRes = imagecreatefromgif($file);
			break;
			case "image/png":
				$this->picType = PT_PNG;
				
				//Import into GD, and set the resource
				$this->picGDRes = imagecreatefrompng($file);
			break;
		}
		//Ensure file type was set
		if (!isset($this->picType) || empty($this->picType) || is_null($this->picType) || empty($this->picGDRes) || is_null($this->picGDRes))
			return FALSE;
			
		//Get image width and height
		$this->picWidth = imagesx($this->picGDRes);
		$this->picHeight = imagesy($this->picGDRes);
	}
	
	/*
	 * resize($width, $height) function
	 * 
	 * $width => Defines the width of the resized image
	 * $height => Defines the height of the resized image
	 * 
	 * This function resizes the picture that has been loaded to the
	 * given width, and height. It attempts to keep the picture intact
	 * as far as ratio is concerned. This is helpful for creating thumbnail
	 * images.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function resize($width, $height) {
		//Precondition: A valid width and height are given, and the GD resource should exist
		//Postcondition: The image is resized using GD
		
		//Ensure the GD resource exists
		if (!isset($this->picGDRes) || is_null($this->picGDRes) || empty($this->picGDRes))
			return FALSE;
		
		//Copy the resource
		$temp = $this->picGDRes;
		
		//Resize the image
		$resize = imagecopyresampled($this->picGDRes, $temp, 0,0,0,0, $width, $height, $this->picWidth, $this->picHeight);
		if (!$resize) {
			//Destroy the temporary image
			imagedestroy($temp);
			return FALSE;
		}
		
		//Reset the width and height
		$this->picWidth = imagesx($this->picGDRes);
		$this->picHeight = imagesy($this->picGDRes);
		
		//Destroy the temporary image
		imagedestroy($temp);
		return TRUE;
	}
	
	/*
	 * convertToGreeyscale() function
	 * 
	 * No inputs
	 * 
	 * This function converts the image that is being manipulated into greyscale. This
	 * overwrites the original image that has been loaded.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function convertToGreyscale() {
		//Precondition: A GD resource should exist
		//Poscondition: The image is converted to greyscale
		
		//Ensure the GD resource exists
		if (!isset($this->picGDRes) || is_null($this->picGDRes) || empty($this->picGDRes))
			return FALSE;
		
		//Copy the GD resource
		$temp = $this->picGDRes;
		
		//Make greyscale
		$grey = imagecopymergegrey($temp, $this->picGDRes, 0,0,0,0, $this->picWidth, $this->picHeight, 0);
		
		if (!$grey) {
			//Destroy the temporary image
			imagedestroy($temp);
			
			return FALSE;
		}
		else {
			//Destroy the temporary image
			imagedestroy($temp);
			
			return TRUE;
		}
	}
	
	/*
	 * addSiteText($text) function
	 * 
	 * $text => Defines the text to be written on top of the image.
	 * 
	 * This function adds the given text to the picture. This is generally used for
	 * adding a site url, or name, to the image to say that it's from that particular
	 * site.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function addSiteText($text) {
		//Precondition: A GD resource should exist, and text should not be empty
		//Postcondition: Add text on top of the image
		
		//Ensure the GD resource exists
		if (!isset($this->picGDRes) || is_null($this->picGDRes) || empty($this->picGDRes))
			return FALSE;
		
		//Ensure text is not blank
		if (!isset ($text) || is_null($text) || empty($text))
			return FALSE;
		
		//TODO: Add text to picture
	}
	
	//TODO: Finish Picture class
	
	//Destructor
	public function __destruct() {
		//Precondition: Class is being destroyed
		//Postcondition: Everything is cleaned up properly
		
		//Destroy the GD resource
		if (isset($this->picGDRes) && !empty($this->picGDRes) && !is_null($this->picGDRes))
			imagedestroy($this->picGDRes);
	}
}
?>