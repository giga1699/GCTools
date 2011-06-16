<?php
/**
 * Filename: autoload.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide automatic loading of the required *.inc.php
 * files when trying to load a particular class.
 * @version 0.0.1
 * File created: 16JUN2011
 * @package GCTools
 * @subpackage Autoload
 */

function __autoload($class_name) {
	//Check for the class name
	switch ($class_name) {
		case "Cache":
			require_once("cache.inc.php");
		break;
		
		case "MySQL":
		case "MSSQL":
		case "PGSQL":
		case "SQLITE":
			require_once("database.inc.php");
		break;
		
		case "Error":
		case "GCErrorHandler":
			require_once("error.inc.php");
		break;
		
		case "File":
			require_once("file.inc.php");
		break;
		
		case "LDAP":
		case "ActiveDirectory":
			require_once("ldap.inc.php");
		break;
		
		case "EMail":
			require_once("mail.inc.php");
		break;
		
		case "Page":
		case "Navigation":
			require_once("navigation.inc.php");
		break;
		
		case "Picture":
			require_once("photo.inc.php");
		break;
		
		case "Session":
			require_once("session.inc.php");
		break;
		
		case "User":
			require_once("user.inc.php");
		break;
	}
}

?>