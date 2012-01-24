<?php
/**
 * Filename: gpg.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide gpg support
 * @version: 0.0.1
 * File created: 24JAN2012
 * @package GCTools
 * @subpackage GPG
 */

//namespace GCTools/GPG;

/*
 * TODO: Implement GPG functions
 * Good reference http://devzone.zend.com/1278/using-gnupg-with-php/
 */

//Error codes

class GCGPG {
	/*
	 * $gpgModule => Defines if the PHP GPG module is being used, or not
	 */
	protected $gpgModule;

	public function GCGPG() {
		//Check if the GPG module is loaded, and try to load it.

		//If all else fails, set module to false.
		$this->gpgModule = FALSE;
	}

	//Encrypt
	//Decrypt
	//Sign
	//Validate
	//Pull key
}
?>
