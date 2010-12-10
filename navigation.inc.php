<?php
/**
 * Filename: navigation.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide information about webpage
 * @version 0.1.0
 * File created: 03DEC10
 * File updated: 09DEC10
 * @package GCTools
 * @subpackage Navigation
 * 
 * Change log:
 *
 * 09DEC10:
 * Updated the sitemap functions
 * Changed how subpages are handled. Made it with the factory pattern, like the Navigation
 * Included the additional items to the XML of the sitemap.
 * Added pageExists($pageName) function.
 * Added getPage($pageName) function.
 * 
 * 05DEC10:
 * Created getSitemap function
 * Added to the Page class: $pageLastMod, $pageChangeFreq, $pagePriority
 * Created getters, and setters, for new Page variables
 * Added to addPage function to sort array, by page, after adding a page
 * Fixed $pagename in addPage function to account for spaces in page names
 * Created protected function addPageToSitemap($page)
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
	 * $pageLastMod => Sets the last modified time of the page
	 * $pageChangeFreq => Defines how often the page changes (used for sitemap)
	 * $pagePriority => Defines the priority of the page (used for sitemap)
	 */
	protected $pageName;
	protected $pageTitle;
	protected $pageURL;
	protected $pageContent;
	protected $pageChildren;
	protected $pageLastMod;
	protected $pageChangeFreq;
	protected $pagePriority;
	
	//Constructor
	public function Page() {
		//Precondition: None
		//Postcondition: Class is fully initialized
		
		$this->pageChildren = array();
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
		
		if (count($this->pageChildren) > 0)
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
		
		if ($this->hasChildren())
			return $this->pageChildren;
		else
			return FALSE;
	}
	
	/*
	 * addPageChild($child) function
	 * 
	 * $children => Defines a copy of a Page class for the child
	 * 
	 * This function sets the pageChildren class variable, and ensures that the
	 * value being set is valid.
	 * 
	 * Returns TRUE on success, and FALSE on failure
	 */
	public function addPageChild($child) {
		//Precondition: A valid Page class is passed to function
		//Postcondition: The pageChildren variable is updated. Returns TRUE for success, and FALSE otherwise.
		
		//Check that $child is a Page class
		if (!is_a($children, "Page"))
			return FALSE;
		
		$pageTitle = $child->getPageTitle();
		
		//Check if is set already
		if (isset($this->pageChildren[$pageTitle]))
			unset($this->pageChildren[$pageTitle]);
		
		$this->pageChildren[$pageTitle] = $child;
		
		if (isset($this->pageChildren[$pageTitle]))
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
		
		if (!isset($lastMod) || empty($lastMod) || is_null($lastMod))
			return FALSE;
		
		//TODO: Add date validation
		
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
		if (isset($priority) && !empty($priority) && !is_null($priority) && ($priority >= 0.0 && $priority <= 1.0)) {
			$this->pagePriority = $priority;
			return TRUE;
		}
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
			//Account for spaces in page names
			$pageName = str_replace(" ", "_", $pageName);
			
			//Force lowercase
			$pageName = strtolower($pageName);
			
			$this->navPages[$pageName] = $page;
			
			//Sort the pages array
			ksort($this->navPages);
			echo "added page: \"".$pageName."\"<br>";
			
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
	public function getPage($pageName) {
		//Precondition: $pageName should be valid, and exist.
		//Postcondition: Returns the Page on success, and FALSE otherwise.
		//TODO: Add functionality for parents
		
		//Account for spaces in page names
	    $pageName = str_replace(" ", "_", $pageName);
		
		if ($this->pageExists($pageName))
			return $this->navPages[$pageName];
		else
			return FALSE;
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
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
		
		foreach ($this->navPages as $page) {
			$sitemap .= $this->addPageToSitemap($page);
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
		//Precondition: $page should be a Page class
		//Postcondition: Data to be added to the sitemap should be generated
		//Reference: http://www.sitemaps.org/protocol.php
		
		//Ensure $page is of a Page class
		if (!is_a($page, "Page"))
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
				$xml .= $this->addPageToSitemap($subpage);
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
	  if (!isset($pageName) || is_null($pageName))
		return FALSE;

	  //Account for spaces in page names
	  $pageName = str_replace(" ", "_", $pageName);
	  
	  echo "looking for page \"".$pageName."\"<br>";
	  //Check if the page exists in the current navigation structure
	  if (isset($this->navPages[$pageName]))
		return TRUE;
	  
	  //Page must not exist
	  return FALSE;
	
	}
	//TODO: Create any additional functions needed
}
?>