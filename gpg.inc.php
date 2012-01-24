<?php
/**
 * Filename: gpg.inc.php
 * @author: J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide gpg support
 * @version: 0.0.1
 * File created: 24JAN2012
 * @package GCTools
 * @subpackage GPG
 *
 * Reference PECL Package: http://pecl.php.net/package/gnupg
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
	 *
	 * $gpgRes => Variable to hold the GnuPG module resource
	 */
	protected $gpgModule;
	private $gpgRes;

	public function GCGPG() {
		//Check if the GPG module is loaded, and try to load it.
		if (!extension_loaded('gnupg')) {
			//Extension not loaded, so load based on OS
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				//Load for windows
				throw new Exception("This feature has not been added for your operating system yet.");
			}
			else {
				if (!dl('gnupg.so'))
					$this->gpgModule = FALSE;
				else
					$this->gpgModule = TRUE;
			}
		}
		else
			$this->gpgModule = TRUE;

		if ($this->gpgModule) {
			//Init the GnuPG module resource
			$this->gpgRes = new gnupg();
		}
		else {
			//Unknown at this time. Will have to work on implementing the command line version of GPG.
			throw new Exception("This feature is not yet implemented.");
		}
	}

	//Encrypt
	//Decrypt
	//Sign
	//Validate
	//Pull key
	//Import key
}
?>
