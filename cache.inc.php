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
	 * $cacheFile => Defines the file to be cached
	 * $cachedFile => Defines the cached file
	 * $cacheContents => Defines the content of the current cache
	 */
	protected $cacheDir;
	protected $cacheFile;
	protected $cachedFile;
	protected $cacheContents;
	
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
}
?>