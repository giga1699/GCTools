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
 */

//Declare the namespace
//namespace GCTools/Cache;

//Cache class
class Cache {
	/*
	 * $cacheDir => Defines the directory to store cached files
	 */
	protected $cacheDir;
	
	//Constructor
	public function Cache($cacheDir) {
		//Precondition: The $cacheDir should be defined
		//Postcondition: A cache of the file should be created, or return FALSE on failure
		
		//Check preconditions
		if (!isset($cacheDir))
			return FALSE;
		
		//Set the initial conditions
		$this->cacheDir = $cacheDir;
		
		return TRUE;
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
			return TRUE;
		
		//Determine what type of file it is
	}
}
?>