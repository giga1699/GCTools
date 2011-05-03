
Table of Contents
Section 1: To-Do List	3
Section 2: Files	4
cache.inc.php	4
Class Variables	4
Class Functions	4
Class example	5
database.inc.php	6
file.inc.php	6
mail.inc.php	6
navigation.inc.php	6
photo.inc.php	6
security.inc.php	6
session.inc.php	6
user.inc.php	6
Credits	7
Code Contributors	7
Change Log	8

Section 1: To-Do List
Write up all the items left to-do ;-)

Section 2: Files
cache.inc.php
Class Variables
1.) cacheDir – This defines the directory to store cached files. It must exist, and be writable.
Class Functions
 1. Cache($cacheDir)
(a) $cacheDir defines the directory to store cached files
(b) Pre/Post Conditions:
 i. Precondition: The $cacheDir should be defined.
 ii. Postcondition: The cache class is initialized.
(c) This function initializes the Cache class. It ensures that the cache directory exists, and is writable. If the directory does not exist, or is not writable, an exception is thrown.
 2. getCacheDir()
(a) Pre/Post Conditions:
 i. Precondition: $cacheDir should be set
 ii. Postcondition: Returns the $cacheDir, or FALSE otherwise
(b) This function provides the user with the location of the cache directory.
 3. setCacheDir($dir)
(a) $dir defines the new location of the directory to store cached files.
(b) Pre/Post Conditions:
 i. Precondition: $dir should be set, and a writable directory
 ii. Postcondition: Return TRUE on success, and FALSE otherwise
(c) This function allows the user to change the location of the cache directory after the class has already been initialized.
 4. createCache($file)
(a) $file defines the path to the file to be cached
(b) Pre/Post Conditions:
 i. Precondition: $file should be a valid file
 ii. Postcondition: Create a cache of the file and return TRUE on success, or FALSE on failure
(c) This function allows the user to create a cache of a file.
Class example
<?php
require_once(“cache.inc.php”);

try {
	$cache = new Cache(“/path/to/my/cache/directory”)
}
catch (Exception $e) {
	//There was some kind of error, so handle it
}

if (!$cache->createCache(“/path/to/my/file”)) {
	//There was an error creating the cache
}

//We're done. The cached file is /path/to/my/cache/directory/file.cache.html
?>
database.inc.php
file.inc.php
mail.inc.php
navigation.inc.php
photo.inc.php
security.inc.php
session.inc.php
user.inc.php

Credits
Code Contributors
1.) M. “Beanyhead” Parker

Change Log
This is in the works
