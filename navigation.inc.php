<?php
/**
 * Filename: navigation.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide information about webpage
 * @version 0.2.0
 * File created: 03DEC10
 * @package GCTools
 * @subpackage Navigation
 */

//namespace GCTools/Navigation;

//Default page enable value
define("PENABLED", TRUE);

//Page class
class Page {
	/*
	 * Class Variables
	 * 
	 * $pageID => Defines a unique ID for the page
	 * $pageName => Defines the name of the page
	 * $pageTitle => Defines the title of the page
	 * $pageURL => Defines the full URL location of the page
	 * $pageContect => Defines the body content of the particular page
	 * $pageChildren => Points to another Navigation class that holds the children
	 * $pageLastMod => Sets the last modified time of the page
	 * $pageChangeFreq => Defines how often the page changes (used for sitemap)
	 * $pagePriority => Defines the priority of the page (used for sitemap)
	 */
	protected $pageID;
	protected $pageName;
	protected $pageTitle;
	protected $pageURL;
	protected $pageContent;
	protected $pageChildren;
	protected $pageLastMod;
	protected $pageChangeFreq;
	protected $pagePriority;
	protected $pageEnabled;
	protected $pagePHP;
	protected $pageSecurityCheck; //Callback function for page security
	
	//Constructor
	public function Page() {
		//Precondition: None
		//Postcondition: Class is fully initialized
		
		//Initialize the pageChildren array
		$this->pageChildren = array();
		
		//Default is defined above
		$this->pageEnabled = PENABLED;
		
		//By default all pages are NOT PHP pages
		$this->pagePHP = FALSE;
		
		return TRUE;
	}
	
	public function getPageID() {
		//Precondition: pageID should be defined
		//Postcondition: Return the page ID, or FALSE otherwise
		
		if (!isset($this->pageID))
			return FALSE;
		
		return $this->pageID;
	}
	
	public function setPageID($id) {
		//Precondition: $id should be defined
		//Postcondition: Set the page ID. Return TRUE on success, and FALSE otherwise
		
		if (!isset($id))
			return FALSE;
		
		$this->pageID = $id;
		
		return TRUE;
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
		
		if (isset($name) && !empty($name)) {
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
		//Precondition: Content should be set, and user should be able to access it
		//Postcondition: Returns content, or FALSE on failure
		
		//Check security
		if (isset($this->pageSecurityCheck)) {
			if (!is_callable($this->pageSecurityCheck))
				return FALSE;
			
			if (!call_user_func($this->pageSecurityCheck, $this->pageID))
				return FALSE;
		}
		
		if (isset($this->pageContent) && $this->pagePHP == FALSE)
			return $this->pageContent;
		elseif (isset($this->pageContent) && $this->pagePHP == TRUE) {
			//Get the page
			ob_start();
			require(substr($this->pageContent, (strpos($this->pageContent, "=")+1)));
			return ob_get_clean();
		}
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
	 * If the PHP flag is set, $content should be a valid file from
	 * which to include the give PHP code.
	 * 
	 * Return TRUE on success, and FALSE on failure
	 */
	public function setPageContent($content) {
		//Precondition: Valid content is provided
		//Postcondition: The content is set, or FALSE is returned
		
		//Remove previous content
		unset($this->pageContent);
		
		//Set content
		if ($this->pagePHP == TRUE) {
			if (!is_file($content))
				return FALSE;
			else
				$this->pageContent = "INCLUDE_FILE=" . $content;
		}
		else
			$this->pageContent = $content;
		
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
		
		if (count($this->pageChildren) > 0)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getChildren() function
	 * 
	 * No input
	 * 
	 * This function provides a copy of all page children to the user
	 * 
	 * Returns the children if set, and FALSE otherwise
	 */
	public function getChildren() {
		//Precondition: Page children should be set
		//Postcondition: Returns the children, or FALSE if unset
		
		if ($this->hasChildren())
			return $this->pageChildren;
		else
			return FALSE;
	}
	
	/*
	 * addChild($child) function
	 * 
	 * $children => Defines a copy of a Page class for the child
	 * 
	 * This function sets the pageChildren class variable, and ensures that the
	 * value being set is valid.
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function addChild($child) {
		//Precondition: A valid Page class is passed to function
		//Postcondition: The pageChildren variable is updated. Returns TRUE for success, and FALSE otherwise.
		
		//Check that $child is a Page class
		if (!is_a($child, "Page") || !$child->getPageName())
			return FALSE;
		
		$pageName = $child->getPageName();
		
		//Check if is set already
		if (isset($this->pageChildren[$pageName]))
			unset($this->pageChildren[$pageName]);
		
		$this->pageChildren[$pageName] = $child;
		
		if (isset($this->pageChildren[$pageName]))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getLastMod() function
	 * 
	 * No input
	 * 
	 * This function tells the user what the last modified time of the page was.
	 * 
	 * Returns the last modified time if set, or FALSE otherwise
	 */
	public function getLastMod() {
		//Precondition: $pageLastMod should have been set
		//Postcondition: Return the last modified time, or FALSE if not set
		
		if (isset($this->pageLastMod))
			return $this->pageLastMod;
		else
			return FALSE;
	}
	
	/*
	 * setLastMod($lastMod) function
	 * 
	 * $lastMod => Defines the last modified time of the page
	 * 
	 * This function sets the last modified time of the page.
	 * 
	 * Returns TRUE on success, and FALSE otherwise
	 */
	public function setLastMod($lastMod) {
		//Precondition: lastMod should be a valid date
		//Postcondition: $pageLastMod is set. Returns TRUE on success, and FALSE on failure.
		
		if (!isset($lastMod) || empty($lastMod))
			return FALSE;
		
		//Convert to time
		$lastMod = strtotime($lastMod);
		if (!$lastMod)
			return FALSE;
		
		//Convert to YYYY-MM-DD HH:MM:SS format
		$lastMod = date("Y-m-d H:i:s", $lastMod);
		
		//Remove previous last mod date
		unset($this->pageLastMod);
		
		$this->pageLastMod = $lastMod;
		
		if (isset($this->pageLastMod))
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * getChangeFreq() function
	 * 
	 * No input
	 * 
	 * This functions tells the user how often the page is supposed to change.
	 * 
	 * Returns the change freq if set, and FALSE otherwise
	 */
	public function getChangeFreq() {
		//Precondition: pageChangeFreq should have been set
		//Postcondition: Return the change freq, or FALSE
		
		if (isset($this->pageChangeFreq))
			return $this->pageChangeFreq;
		else
			return FALSE;
	}
	
	/*
	 * setChangeFreq($freq) function
	 * 
	 * $freq => Defines how often the page is supposed to change
	 * 
	 * This function sets the class variable $pageChangeFreq
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function setChangeFreq($freq) {
		//Precondition: $freq should be an acceptable value
		//Postcondition: Set the $pageChangeFreq variable, or return FALSE
		//Reference: http://www.sitemaps.org/protocol.php
		
		//Check if $freq is an acceptable value
		if (isset($freq) && !empty($freq) && !is_null($freq) && ($freq == "always" || $freq == "hourly" || $freq == "daily" || $freq == "weekly" || $freq == "monthly" || $freq == "yearly" || $freq == "never")) {
			$this->pageChangeFreq = $freq;
			return TRUE;
		}
		else
			return FALSE;
	}
	
	/*
	 * getPriority() function
	 * 
	 * No input
	 * 
	 * This function tells the user the priority of the page.
	 * 
	 * Returns the priority of the page, or FALSE otherwise.
	 */
	public function getPriority() {
		//Precondition: $pagePriority should have been set
		//Postcondition: Return the priority, or FALSE otherwise
		
		if (isset($this->pagePriority))
			return $this->pagePriority;
		else
			return FALSE;
	}
	
	/*
	 * setPriority($priority) function
	 * 
	 * $priority => Defines the priority of the page
	 * 
	 * This function sets the $pagePriority variable, which defines the priority of the page.
	 * 
	 * Returns TRUE on success, and FALSE on failure.
	 */
	public function setPriority($priority) {
		//Precondition: $priority should be a valid value
		//Postcondition: Sets the $pagePriority variable. Returns TRUE on success, and FALSE on failure.
		
		//Determine if $priority is valid
		if (isset($priority) && ($priority >= 0.0 && $priority <= 1.0)) {
			$this->pagePriority = $priority;
			return TRUE;
		}
		else
			return FALSE;
	}
	
	/*
	 * enablePage() function
	 * 
	 * No inputs
	 * 
	 * This function enables the page for viewing.
	 * 
	 * Returns TRUE if the page was enabled, and FALSE otherwise.
	 */
	public function enablePage() {
		//Precondition: None
		//Postcondition: pageEnabled is set to TRUE
		
		$this->pageEnabled = TRUE;
		
		if ($this->pageEnabled == TRUE)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * disablePage() function
	 * 
	 * No inputs
	 * 
	 * This function disables a page for viewing.
	 * 
	 * Returns TRUE if the page was disabled, and FALSE otherwise.
	 */
	public function disablePage() {
		//Precondition: None
		//Postcondition: Sets pageEnabled to FALSE
		
		$this->pageEnabled = FALSE;
		
		if ($this->pageEnabled == FALSE)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * isEnabled() function
	 * 
	 * No inputs
	 * 
	 * This function determines if the page is enabled or not.
	 * 
	 * Returns TRUE if page is enabled, and FALSE otherwise.
	 */
	public function isEnabled() {
		//Precondition: pageEnabled variable should be set
		//Postcondition: Returns TRUE if page is enabled, and FALSE otherwise
		
		if (!isset($this->pageEnabled) || empty($this->pageEnabled))
			return FALSE;
		
		if ($this->pageEnabled == TRUE)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * isPHP() function
	 * 
	 * No inputs
	 * 
	 * This function determines if the page is a PHP page, or not
	 * 
	 * Returns TRUE if the page is PHP, and FALSE otherwise
	 */
	public function isPHP() {
		//Precondition: None
		//Postcondition: Returns TRUE if the page is PHP, and FALSE otherwise
		
		if ($this->pagePHP === TRUE)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * setPHP() function
	 * 
	 * No input
	 * 
	 * This function sets the PHP page flag that is used when getting
	 * the contents of a particular page.
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function setPHP() {
		//Precondition: None
		//Postcondition: $pagePHP should be set to TRUE
		
		$this->pagePHP = TRUE;
		
		if ($this->pagePHP == TRUE)
			return TRUE;
		else
			return FALSE;
	}
	
	/*
	 * unsetPHP() function
	 * 
	 * No input
	 * 
	 * This function removes the PHP page flage that is used when getting
	 * the contents of a particular page.
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function unsetPHP() {
		//Precondition: None
		//Postcondition: $pagePHP flag is set to FALSE
		
		$this->pagePHP = FALSE;
		
		if ($this->pagePHP == FALSE)
			return TRUE;
		else
			return FALSE;
	}
	
	public function setSecurityCallback($callback) {
		//Precondition: $callback should be defined, and callable
		//Postcondition: Set the security callback. Return TRUE on success, and FALSE otherwise.
		
		if (!isset($callback) || !is_callable($callback))
			return FALSE;
		
		$this->pageSecurityCheck = $callback;
		
		return TRUE;
	}
	
	public function unsetSecurity() {
		//Precondition: None
		//Postcondition: Remove security from the page. Return TRUE on success, and FALSE otherwise
		
		if (!isset($this->pageSecurityCheck))
			return TRUE;
		
		unset($this->pageSecurityCheck);
		
		return TRUE;
	}
	
	public function hasSecurity() {
		//Precondition: None
		//Postcondition: Return TRUE if page has security enabled, and FALSE otherwise
		
		if (!isset($this->pageSecurityCheck))
			return TRUE;
		else
			return FALSE;
	}
	
	public function getSecurityCallback() {
		//Precondition: A security callback should be defined
		//Postcondition: Return the function to check the security of a page, or FALSE on failure.
		
		if (!$this->hasSecurity())
			return FALSE;
		else
			return $this->pageSecurityCheck;
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
		
		return TRUE;
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
		if (!is_a($page, "Page")) {
			if (!is_subclass_of($page, "Page"))
				return FALSE;
		}
		
		//Check if the page has a name
		if ($pageName = $page->getPageName()) {
			//Format page name
			$pageName = $this->formatPageName($pageName);
			if (!$pageName)
				return FALSE;
			
			$this->navPages[$pageName] = $page;
			
			//Sort the pages array
			ksort($this->navPages);
			
			if (isset($this->navPages[$pageName]))
				return TRUE;
		}
		else {
			//This page is the homepage
			
			//Remove any old homepage
			unset($this->navPages[""]);
			
			$this->navPages[""] = $page;
			
			//Sort the pages array
			ksort($this->navPages);
			
			if (isset($this->navPages[""]))
				return TRUE;
		}
		
		//If we haven't returned TRUE, something must have gone wrong so return FALSE
		return FALSE;
	}
	
	/*
	 * getPage($pageName) function
	 *
	 * $pageName => Defines the name of the page you are wanting to get
	 *
	 * This function returns a copy of the Page class with the same name as $pageName.
	 *
	 * Returns Page if found, and FALSE otherwise.
	 */
	public function getPage($pageName=NULL) {
		//Precondition: $pageName should be valid, and exist.
		//Postcondition: Returns the Page on success, and FALSE otherwise.
		//TODO: Add functionality for parents
		
		//Check homepage
		if (isset($this->navPages[""]) && (is_null($pageName) || empty($pageName) || !isset($pageName)))
			return $this->navPages[""];
		
		//Format page name
		$pageName = $this->formatPageName($pageName);
		if (!$pageName)
			return FALSE;
		
		if ($this->pageExists($pageName))
			return $this->navPages[$pageName];
		else
			return FALSE;
	}
	
	/*
	 * getHomePage() function
	 *
	 * No input
	 *
	 * This function returns the homepage information
	 *
	 * Returns the homepage on success, and FALSE otherwise
	 */
	public function getHomePage() {
		//Precondition: Home page should be set
		//Postcondition: Return homepage Page class, or FALSE
		
		if (!isset($this->navPages[""]))
			return FALSE;
		else
			return $this->navPages[""];
	}
	
	/*
	 * getSitemap() function
	 * 
	 * No input
	 * 
	 * This function creates a XML sitemap.
	 * 
	 * Returns the sitemap on success, and FALSE otherwise
	 */
	public function getSitemap() {
		//Precondition: Pages should have been added
		//Postcondition: A XML sitemap will be created, and returned on success. Returns FALSE otherwise.
		//Reference: http://www.sitemaps.org/protocol.php
		
		//Ensure there is at least one page
		if (count($this->navPages) == 0)
			return FALSE;
		
		//Begin creating the XML sitemap
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		
		foreach ($this->navPages as $page) {
			$addpage = $this->addPageToSitemap($page);
			if ($addpage != FALSE)
				$sitemap .= $addpage;
		}
		
		//End sitemap
		$sitemap .= "</urlset>";
		
		if (isset($sitemap) && !empty($sitemap))
			return $sitemap;
		else
			return FALSE;
	}
	
	/*
	 * addPageToSitemap($page) function
	 * 
	 * $page => A Page class that needs to be added to the sitemap
	 * 
	 * This function is used by getSitemap(), and itself, to add pages to the sitemap.
	 * 
	 * Returns data to be added to sitemap, or FALSE on failure.
	 */
	protected function addPageToSitemap($page) {
		//Precondition: $page should be a Page class, and page should be enabled.
		//Postcondition: Data to be added to the sitemap should be generated
		//Reference: http://www.sitemaps.org/protocol.php
		
		//Ensure $page is of a Page class
		if (!is_a($page, "Page")) {
			if (!is_subclass_of($page, "Page"))
				return FALSE;
		}
		
		//Make sure page in enabled
		if ($page->isEnabled() == FALSE || $page->hasSecurity() == TRUE)
			return FALSE;
		
		//Create XML to add
		$xml = "\t<url>\n\t\t<loc>".$page->getPageURL()."</loc>\n";
		
		//Check if anything else from the page could be added to the XML
		if ($lastmod = $page->getLastMod())
			$xml .= "\t\t<lastmod>".$lastmod."</lastmod>\n";
		
		if ($changefreq = $page->getChangeFreq())
			$xml .= "\t\t<changefreq>".$changefreq."</changefreq>\n";
		
		if ($priority = $page->getPriority())
			$xml .= "\t\t<priority>".$priority."</priority>\n";
		
		//End XML
		$xml .= "\t</url>\n";
		
		//Check if page has sub pages
		if ($page->hasChildren()) {
			//Get children navigation class
			$children = $page->getChildren();
			
			foreach ($children as $subpage) {
				$addsubpage = $this->addPageToSitemap($subpage);
				if ($addsubpage != FALSE)
					$xml .= $addsubpage;
			}
		}
		
		return $xml;
	}
	
	/*
	 * pageExists($pageName) function
	 *
	 * $pageName => Defines the name of the page the user is looking for.
	 *
	 * This function finds out if a particular page exists.
	 *
	 * Returns TRUE if page exists, and FALSE otherwise.
	 */
	public function pageExists($pageName) {
	  //Precondition: $pageName should be valid
	  //Postcondition: Returns TRUE if the page exists, and FALSE otherwise.
	  //TODO: Add functionality for parents
	  
	  //Ensure $pageName is valid
	  if (!isset($pageName))
		return FALSE;

	  //Format page name
	  $pageName = $this->formatPageName($pageName);
	  if (!$pageName)
		return FALSE;
	  
	  //Check if the page exists in the current navigation structure
	  if (isset($this->navPages[$pageName]))
		return TRUE;
	  
	  //Page must not exist
	  return FALSE;
	
	}
	
	/*
	 * formatPageName($name) function
	 *
	 * $name => Defines the name to properly format
	 *
	 * This function properly formats a page name for navigation uses
	 *
	 * Returns the formatted page name on success, and FALSE otherwise
	 */
	public function formatPageName($name) {
		//Precondition: $name should be given
		//Postcondition: Return properly formatted page name
		
		if (!isset($name) || is_null($name))
			return FALSE;
		
		//Check if any unauthorized characters exist (\x20 is the space character)
		if (preg_match("/[^a-zA-Z0-9_\x20-]/", $name) != 0)
			return FALSE;
		
		$name = str_replace(" ", "_", $name);
		$name = strtolower($name);
		
		return $name;
	}
	//TODO: Create any additional functions needed
}
?>
