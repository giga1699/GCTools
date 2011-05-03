<?php
/*
 * Filename: photo.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide phto support
 * @version: 0.1.0
 * File created: 02DEC10
 * File updated: 30APR11
 * @package GCTools
 * @subpackage Photo
 * 
 * Change log:
 * 
 * 30APR2011:
 * Used file.inc.php to load picture from file
 * 
 * 28APR2011:
 * Added GD library detection, and loading
 * Added other picture types to save function
 * Added display function
 * Fixed resize function call to imagecopyresampled
 * Added additional error checking
 * Added pre/post-condition checking to display and save functions
 * Added additional error checking to multiple functions
 * Added another way to get the height/width in the event of a failure
 * Fixed resize function
 * Enabled the ability to write white text to top-left corner of picture
 * Added multiple colors to site text
 * Moved to version 0.1.0
 * Modified how the grayscale image is created
 * 
 * 29DEC10:
 * Added save function
 * 
 * 10DEC10:
 * Changed constructor if from ($loc != NULL) to isset($loc)
 */

//namespace GCTools/Photo;

//Requires use of file.inc.php
require_once("file.inc.php");

//Picture types
define("PT_JPG", 0);
define("PT_GIF", 1);
define("PT_PNG", 2);

//Text colors
define("CLR_WHITE", 0);
define("CLR_BLACK", 1);
define("CLR_BLUE", 2);

class Picture {
	//Variables for picture class
	protected $picLoc; //Holds the file location of file (Not required)
	protected $picBuffer; //Holds the buffer
	protected $picSize; //Size of picture (in bytes)
	protected $picHeight; //In pixels
	protected $picWidth; //In pixels	
	protected $picType; //Picture type
	protected $picName; //Friendly name (Not required)
	protected $picGDRes; //Image resource for GD
	
	//Constructor
	public function Picture($loc=NULL) {
		//Precondition: None, or a file location is given
		//Postcondition: If file location is given, the file is loaded. Otherwise, the class should just be created
		
		//Ensure that GD has been loaded
		if (!extension_loaded("gd")) {
			//Try to load the extension
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		        dl('php_gd2.dll');
		    else
		        dl('gd.so');
		    
		    //Ensure extension is loaded
		    if (!extension_loaded("gd"))
		    	throw new Exception("Unable to load GD library");
		}
		
		//Load picture if loc is given
		if (isset($loc)) {
			if ($this->loadFromFile($loc))
				return TRUE;
			else
				throw new Exception("Could not load picture from file");
		}
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
			
		//Load file
		$picture = new File($file);
		if ($picture->hadError())
			return FALSE;
		
		//Get file name
		$this->picName = $picture->getFileName();
		
		//Get file size
		$this->picSize = $picture->getSize();
		
		//Determine file type
		switch($picture->getMIMEType()) {
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
		if (!isset($this->picType) || is_null($this->picType) || !isset($this->picGDRes) || empty($this->picGDRes) || is_null($this->picGDRes))
			return FALSE;
			
		//Get image width and height
		$this->picWidth = imagesx($this->picGDRes);
		$this->picHeight = imagesy($this->picGDRes);
		
		//Check that width/height was set
		if (!isset($this->picWidth) || !isset($this->picHeight)) {
			//Try to get the width/height another way
			$size = getimagesize($file);
			if (!$size)
				return FALSE;
			else {
				$this->picWidth = $size[0];
				$this->picHeight = $size[1];
			}
		}
		
		return TRUE;
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
		
		//Ensure height/width is set
		if (!isset($this->picWidth) || !isset($this->picHeight) || !isset($width) || !isset($height))
			return FALSE;
		
		//Get scaling
		$xscale = $width/$this->picWidth;
		$yscale = $height/$this->picHeight;
		
		//Calculate new width and height
		if ($xscale > $yscale) {
			$newwidth = round($width/(1/$xscale));
			$newheight = round($height/(1/$xscale));
		}
		else {
			$newwidth = round($width/(1/$yscale));
			$newheight = round($height/(1/$yscale));
		}
		
		//Create new image
		$new = imagecreatetruecolor($newwidth, $newheight);
		
		//Resize the image
		$resize = imagecopyresampled($new, $this->picGDRes, 0,0,0,0, $newwidth, $newheight, $this->picWidth, $this->picHeight);
		if (!$resize) {
			imagedestroy($new);
			return FALSE;
		}
		
		//Reset the width and height
		$this->picWidth = imagesx($this->picGDRes);
		$this->picHeight = imagesy($this->picGDRes);
		
		//Redefine Res, and destroy old image
		imagedestroy($this->picGDRes);
		$this->picGDRes = $new;
		
		return TRUE;
	}
	
	/*
	 * convertToGrayscale() function
	 * 
	 * No inputs
	 * 
	 * This function converts the image that is being manipulated into grayscale. This
	 * overwrites the original image that has been loaded.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function convertToGrayscale() {
		//Precondition: A GD resource should exist
		//Poscondition: The image is converted to grayscale
		
		//Ensure the GD resource exists
		if (!isset($this->picGDRes) || is_null($this->picGDRes) || empty($this->picGDRes))
			return FALSE;
		
		//Create new image for grayscale
		$gray = imagecreate($this->picWidth, $this->picHeight);
		
		//Creates the 256 color palette
		for ($c=0;$c<256;$c++) 
		{
			$palette[$c] = imagecolorallocate($gray,$c,$c,$c);
		}
		
		//Reads original colors pixel by pixel
		for ($y=0;$y<$this->picHeight;$y++) {
			for ($x=0;$x<$this->picWidth;$x++) {
				$rgb = imagecolorat($this->picGDRes, $x, $y);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				
				//Convert to grayscale
				$gs = $this->yiq($r, $g, $b);
				imagesetpixel($gray, $x, $y, $palette[$gs]);
			}
		}
		
		if (!$gray) {
			imagedestroy($gray);
			return FALSE;
		}
		else {
			imagedestroy($this->picGDRes);
			$this->picGDRes = $gray;
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
	public function addSiteText($text, $color=CLR_WHITE) {
		//Precondition: A GD resource should exist, and text should not be empty
		//Postcondition: Add text on top of the image
		
		//Ensure the GD resource exists
		if (!isset($this->picGDRes) || is_null($this->picGDRes) || empty($this->picGDRes))
			return FALSE;
		
		//Ensure text is not blank
		if (!isset ($text) || is_null($text) || empty($text))
			return FALSE;
		
		//Create color
		switch($color) {
			case CLR_BLUE:
				$textcolor = imagecolorallocate($this->picGDRes, 0, 0, 255);
			break;
			case CLR_BLACK:
				$textcolor = imagecolorallocate($this->picGDRes, 0, 0, 0);
			break;
			case CLR_WHITE:
			default:
				$textcolor = imagecolorallocate($this->picGDRes, 255, 255, 255);
			break;
		}
		//Add text to picture
		if (!imagestring($this->picGDRes, 2, 5, 5, $text, $textcolor))
			return FALSE;
		else
			return TRUE;
	}
	
	/*
	 * save($fileLoc) function
	 * 
	 * $fileLoc => Defines where the file should be saved
	 * 
	 * This function allows the user to save a file to a specific location on the server.
	 * 
	 * Return TRUE on success, and FALSE on failure.
	 */
	public function save($fileLoc) {
		//Precondition: picType, picGDRes and fileLoc should be set
		//Postcondition: File is saved to fileLoc
		
		if (!isset($this->picType) || !isset($this->picGDRes) || !isset($fileLoc))
			return FALSE;
		
		switch ($this->picType) {
			case PT_JPG:
				imagejpeg($this->picGDRes, $fileLoc, 75);
			break;
			case PT_GIF:
				imagegif($this->picGDRes, $fileLoc);
			break;
			case PT_PNG:
				imagepng($this->picGDRes, $fileLoc, 75);
			break;
			default:
				return FALSE;
		}
		
		//Check that file was created
		if (!is_file($fileLoc))
			return FALSE;
		
		return TRUE;
	}
	
/*
	 * display() function
	 * 
	 * This function outputs the current image to the web browser
	 * 
	 * Return TRUE on success, and FALSE on failure.
	 */
	public function display() {
		//Precondition: picType and picGDResshould be set
		//Postcondition: File is displayed
		
		if (!isset($this->picType) || !isset($this->picGDRes))
			return FALSE;
		
		switch ($this->picType) {
			case PT_JPG:
				imagejpeg($this->picGDRes);
			break;
			case PT_GIF:
				imagegif($this->picGDRes);
			break;
			case PT_PNG:
				imagepng($this->picGDRes);
			break;
			default:
				return FALSE;
		}
		
		return TRUE;
	}
	
	/*
	 * yiq($r,$g,$b) function
	 * 
	 * $r => Red
	 * $g => Green
	 * $b => Blue
	 * 
	 * This function is used to change a color image to greyscale.
	 * 
	 * Returns the YIQ value, or FALSE on error
	 * 
	 * Reference: http://php.about.com/od/gdlibrary/ss/grayscale_gd.htm
	 */
	protected function yiq($r, $g, $b) {
		//Precondition: $r, $g, $b should be defined
		//Postcondition: Return the YIQ value, or FALSE on error
		
		if (!isset($r) || !isset($g) || !isset($b))
			return FALSE;
		
		return (($r*0.299)+($g*0.587)+($b*0.114));
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