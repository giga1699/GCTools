<?php
/**
 * Filename: navigation.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide information about webpage
 * @version 0.0.5
 * File created: 03DEC10
 * File updated: 04DEC10
 * @package GCTools
 * @subpackage Navigation
 * 
 * Change log:
 * 
 * 04DEC10:
 * Renamed page.navigation.inc.php to navigation.inc.php
 * Created Navigation class
 * Created navigation constructor
 * Created addPage function
 */

//namespace GCTools/Navigation;

//Page class
class Page {
	/*
	 * Class Variables
	 * 
	 * $pageName => Defines the name of the page
	 * $pageTitle => Defines the title of the page
	 * $pageURL => Defines the full URL location of the page
	 * $pageContect => Defines the body content of the particular page
	 * $pageChildren => Points to another Navigation class that holds the children
	 */
	protected $pageName;
	protected $pageTitle;
	protected $pageURL;
	protected $pageContent;
	protected $pageChildren;
	
	//Constructor
	public function Page() {
		//TODO: Write any initilization
	}
	
	/*getPageName() function
	 * 
	 * No inputs
	 * 
	 * This function returns the page name, if set.
	 * 
	 * Returns page name on success, and FALSE if the
	 * name is not set.
	 */
	public function getPageName() {
		//Precondition: Page name should be set
		//Postcondition: Page name is returned, or FALSE if not set.
		if (isset($this->pageName))
			return $this->pageName;
		else
			return FALSE;
	}
	
	/*
	 * setPageName($name) function
	 * 
	 * $name => Defines the name of a given page
	 * 
	 * This function sets the name of the page this
	 * class is being utilized for.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setPageName($name) {
		//Precondition: A page name is given
		//Postcondition: The page name is set
		
		if (isset($name) && !is_null($name) && !empty($name)) {
			$this->pageName = $name;
			return TRUE;
		}
		else
			return FALSE;
	}
	
	/*
	 * getPageTitle() function
	 * 
	 * No input
	 * 
	 * This function provides a copy of the page title to the user
	 * 
	 * Returns the page title if it is set, and FALSE otherwise
	 */
	public function getPageTitle() {
		//Precondition: Title should be set
		//Postcondition: Title is returned, or FALSE if unset
		
		if (isset($this->pageTitle))
			return $this->pageTitle;
		else
			return FALSE;
	}
	
	/*
	 * setPageTitle($title) function
	 * 
	 * $title => Defines what the page title should be set to
	 * 
	 * This function sets the class variable for the page title
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function setPageTitle($title) {
		//Precondition: A valid title is given
		//Postcondition: The title is set
		
		//Remove any previous title
		unset($this->pageTitle);
		
		$this->pageTitle = $title;
		
		if (isset($this->pageTitle))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getPageURL() function
	 * 
	 * No input
	 * 
	 * This function provides a copy of the URL to the user.
	 * 
	 * Returns the URL if set, and FALSE otherwise.
	 */
	public function getPageURL() {
		//Precondition: URL should be set
		//Postcondition: URL is returned, or FALSE if unset
		
		if (isset($this->pageURL))
			return $this->pageURL;
		else
			return FALSE;
	}
	
	
	/*
	 * setPageURL($url) function
	 * 
	 * $url => Defines the valid URL to the page
	 * 
	 * This function sets the pageURL class variable for the page
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function setPageURL($url) {
		//Precondition: A valid URL is given
		//PostconditioN: The URL is set, or FALSE is returned on failure
		
		//Check the URL for validity
		if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url))
			return FALSE;
		
		//Unset previous URL
		unset($this->pageURL);
		
		//Set URL
		$this->pageURL = $url;
		
		if (isset($this->pageURL))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getPageContent() function
	 * 
	 * No input
	 * 
	 * This function provides the user a copy of the content of the page
	 * 
	 * Returns the content if set, and FALSE otherwise
	 */
	public function getPageContent() {
		//Precondition: Content should be set
		//Postcondition: Returns content, or FALSE on failure
		
		if (isset($this->pageContent))
			return $this->pageContent;
		else
			return FALSE;
	}
	
	/*
	 * setPageContent($content) function
	 * 
	 * $content => Defines the page content that is in the page
	 * 
	 * This function sets the class variable $pageContent, which holds
	 * the content of a given page.
	 * 
	 * Return TRUE on success, and FALSE on failure
	 */
	public function setPageContent($content) {
		//Precondition: Valid content is provided
		//Postcondition: The content is set, or FALSE is returned
		
		//Remove previous content
		unset($this->pageContent);
		
		//Set content
		$this->pageContent;
		
		if (isset($this->pageContent))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * hasChildren() function
	 * 
	 * No input
	 * 
	 * This function determines if the page has children.
	 * 
	 * Returns TRUE if children exist, and FALSE otherwise
	 */
	public function hasChildren() {
		//Precondition: None
		//Postcondition: Returns TRUE if page has children, and FALSE otherwise
		
		if (isset($this->pageChildren))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getPageChildren() function
	 * 
	 * No input
	 * 
	 * This function provides a copy of all page children to the user
	 * 
	 * Returns the children if set, and FALSE otherwise
	 */
	public function getPageChildren() {
		//Precondition: Page children should be set
		//Postcondition: Returns the children, or FALSE if unset
		
		if (isset($this->pageChildren))
			return $this->pageChildren;
		else
			return FALSE;
	}
	
	/*
	 * setPageChildren($children) function
	 * 
	 * $children => Defines a copy of a Navigation class for the children
	 * 
	 * This function sets the pageChildren class variable, and ensures that the
	 * value being set is valid.
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function setPageChildren($children) {
		//Precondition: A valid Navigation class is passed to function
		//Postcondition: The pageChildren variable is set. Returns TRUE for success, and FALSE otherwise.
		
		//Check that $children is a Navigation class
		if (!is_a($children, "Navigation"))
			return FALSE;
		
		//Remove previous children
		unset($this->pageChildren);
		
		$this->pageChildren = $children;
		
		if (isset($this->pageChildren))
			return TRUE;
		else
			return FALSE;
	}
}

//Navigation class
class Navigation {
	/*
	 * Class variables
	 * 
	 * $navPages => Array that stores the pages
	 */
	protected $navPages;
	
	//Class constructor
	public function Navigation() {
		//Precondition: None
		//Postcondition: Class is initilized
		
		$this->navPages = array();
	}
	
	/*
	 * addPage($page) function
	 * 
	 * $page => A Page class that is to be added to the array of pages for
	 * the navigation structure
	 * 
	 * This function adds a given page to the navigation structure. A page
	 * with no name set is assumed to be the homepage of the site.
	 * 
	 * Returns TRUE if the page was added, and FALSE otherwise
	 */
	public function addPage($page) {
		//Precondition: A Page class is passed to the function
		//Postcondition: The page is added to the array of pages, or FALSE is returned
		
		//Ensure that the page is of the Page class
		if (!is_a($page, "Page"))
			return FALSE;
		
		//Check if the page has a name
		if ($pageName = $page->getPageName()) {
			$this->navPages[$pageName] = $page;
			
			if (isset($this->navPages[$pageName]))
				return TRUE;
		}
		else {
			//This page is the homepage
			
			//Remove any old homepage
			unset($this->navPages[""]);
			
			$this->navPages[""] = $page;
			
			if (isset($this->navPages[""]))
				return TRUE;
		}
		
		//If we haven't returned TRUE, something must have gone wrong so return FALSE
		return FALSE;
	}
	//TODO: Create any additional functions needed
}
?>