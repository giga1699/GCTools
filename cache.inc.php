<?php
/**
 * Filename: cache.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide cache functionality.
 * @version 0.0.1
 * File created: 28APR2011
 * File modified: 28APR2011
 * @package GCTools
 * @subpackage Cache
 * 
 * Changelog:
 * Began to utilize the File class
 * Added getters/setters for cacheDir
 */

//Declare the namespace
//namespace GCTools/Cache;

//Requires the use of the File class
require_once("file.inc.php");

//Cache class
class Cache {
	/*
	 * $cacheDir => Defines the directory to store cached files
	 * $cacheFiles => An array of files for caching
	 */
	protected $cacheDir;
	protected $cacheFiles;
	
	//Constructor
	public function Cache($cacheDir) {
		//Precondition: The $cacheDir should be defined
		//Postcondition: A cache of the file should be created, or return FALSE on failure
		
		//Check preconditions
		if (!isset($cacheDir))
			return FALSE;
		
		//Set the initial conditions
		$this->cacheDir = $cacheDir;
		
		//Create the array
		$this->cacheFiles = array();
		
		return TRUE;
	}
	
	/*
	 * getCacheDir() function
	 * 
	 * No inputs
	 * 
	 * This function tells the user where the defined cache directory is
	 * 
	 * Returns the cache directory, if set, and FALSE otherwise.
	 */
	public function getCacheDir() {
		//Precondition: $cacheDir should be set
		//Postcondition: Returns the $cacheDir, or FALSE otherwise
		
		if (isset($this->cacheDir))
			return $this->cacheDir;
		else
			return FALSE;
	}
	
	/*
	 * setCacheDir($dir) function
	 * 
	 * $dir => Defines the directory to store cache files
	 * 
	 * This fuction changed the directory to store cache files
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setCacheDir($dir) {
		//Precondition: $dir should be set, and a writeable directory
		//Postcondition: Return TRUE on success, and FALSE otherwise
		
		$this->cacheDir = $dir;
		
		if ($this->cacheDir == $dir)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * createCache($file) function
	 * 
	 * $file => Defines the file to be cached
	 * 
	 * This function creates a cache of a given file, and stores it
	 * in the $cacheDir folder.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function createCache($file) {
		//Precondition: $file should be a vaild file
		//Postconditoin: Create a cache of the file, and return TRUE on success or FALSE on failure
		
		//Check that $file is a valid file
		if (!is_file($file))
			return FALSE;
		
		//Create file ID
		$fileID = md5($file);
		
		//Load the file into the array
		$this->cacheFiles[$fileID] = new File($file);
		
		//Determine type of file
		switch ($this->cacheFiles[$fileID]->getMIMEType()) {
			case "text/x-php":
				//PHP File
				ob_start();
				require $file;
				$fileCache = ob_get_clean();
			break;
		}
		
		//Write the $fileCache to the cache directory
		$cacheFile = fopen($this->cacheDir . $this->cacheFiles[$fileID]->getFileName . ".cache.html", "w");
		
		//Check that we created the file okay
		if (!$cacheFile)
			return FALSE;
		
		fputs($cacheFile, $fileCache, strlen($fileCache));
		fclose($cacheFile);
		
		return TRUE;
	}
}
?>