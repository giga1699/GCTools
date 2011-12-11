<?php
/**
 * Filename: whois.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide information about domains
 * @version 0.0.1
 * File created: 11DEC11
 * @package GCTools
 * @subpackage Whois
 */
 
class Whois {
	protected $servers;
	
	public function Whois() {
		//Set-up servers for lookups
		$this->servers = array(
            "com"=>"whois.verisign-grs.com",
            "org"=>"whois.publicinterestregistry.net",
            "net"=>"whois.verisign-grs.com",
            "biz"=>"whois.biz",
            "info"=>"whois.afilias.net",
            "us"=>"whois.nic.us",
            "co"=>"whois.nic.co",
            "me"=>"whois.nic.me",
            "mobi"=>"whois.dotmobiregistry.net",
            "tv"=>"tvwhois.verisign-grs.com",
            "tel"=>"whois.nic.tel"
            //"name"=>"whois.nic.name"
        );
	}
	
	public function ValidDomain($domain) {
		//Precondition: A domain should be given
		/*
		 * Postcondition: If a given Top Level Domain (TLD) is in
		 * the server listing, then we can look up the domain
		 * information.
		 */
        $domain = explode(".", $domain);
        $tld = strtolower($domain[count($domain)-1]);
        
        return array_key_exists($tld, $this->servers);
    }
    
    public function Lookup($domain) {
    	//Precondition: A valid domain should be provided
    	//Postcondition: Return the domain whois data, or FALSE otherwise
    	
    	if (!$this->ValidDomain($domain))
    		return FALSE;
    	
    	//Get TLD
        $domain = explode(".", $domain);
        $tld = strtolower($domain[count($domain)-1]);
        
        //Open up WHOIS server
        $link = fsockopen($this->servers[$tld], "43");
        
        //If we couldn't connect, then return FALSE
        if (!$link)
            return FALSE;
        
        //Write the lookup domain to the WHOIS server
        fwrite($link, implode(".", $domain) . "\r\n");
        
        //Prepare return variable
        $data = "";
        
        //Get all the WHOIS data
        while (!feof($link)) {
            $data .= fgets($link);
        }
        
        //Close link to WHOIS server
        fclose($link);
        
        //Return the data
        return $data;
    }
    
    public function getCreateDate($data) {
    	//Precondition: Some kind of data should be given
    	/*
    	 * Postcondition: Attempt to find the creation date of the domain.
    	 * Return the creation date on success, and FALSE otherwise.
    	 */
    	
    	//Find create date
        if (preg_match("/Created on:[\s]{0,}[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{1,4}/i", $data, $matches)) {
            preg_match("/[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}/", $matches[0], $test);
            $created = $test[0];
        }
        else if (preg_match("/Creation date:[\s]{0,}[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{1,4}/i", $data, $matches)) {
            preg_match("/[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}/", $matches[0], $test);
            $created = $test[0];
        }
        else if (preg_match("/Domain Registration Date:[\s]{0,}[A-Za-z]{3}\s[A-Za-z]{3}\s[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{1,3}\s[0-9]{2,4}/i", $data, $matches)) {
            preg_match("/[A-Za-z]{3}\s[A-Za-z]{3}\s[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{1,3}\s[0-9]{2,4}/", $matches[0], $test);
            $created = $test[0];
        }
        else if (preg_match("/Domain Create Date:[\s]{0,}[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{3}/i", $data, $matches)) {
            preg_match("/[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{3}/", $matches[0], $test);
            $created = $test[0];
        }
        else {
        	//Couldn't find it.
            $created = FALSE;
        }
        
        //Return creation date
        return $created;
    }
    
    public function getExpireDate($data) {
    	//Precondition: Some kind of data should be given
    	/*
    	 * Postcondition: Attempt to find the expiration date of the domain.
    	 * Return the expiration date on success, and FALSE otherwise.
    	 */
    	
    	//Find expire date
        if (preg_match("/Expires on:[\s]{0,}[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{1,4}/i", $data, $matches)) {
            preg_match("/[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}/", $matches[0], $test);
            $expires = $test[0];
        }
        else if (preg_match("/Expiration date:[\s]{0,}[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{1,4}/i", $data, $matches)) {
            preg_match("/[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}/", $matches[0], $test);
            $expires = $test[0];
        }
        else if (preg_match("/Domain Expiration Date:[\s]{0,}[A-Za-z]{3}\s[A-Za-z]{3}\s[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{1,3}\s[0-9]{2,4}/i", $data, $matches)) {
            preg_match("/[A-Za-z]{3}\s[A-Za-z]{3}\s[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{1,3}\s[0-9]{2,4}/", $matches[0], $test);
            $expires = $test[0];
        }
        else if (preg_match("/Domain Expiration Date:[\s]{0,}[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{3}/i", $data, $matches)) {
            preg_match("/[0-9]{1,2}-[A-Za-z]{1,3}-[0-9]{2,4}\s[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\s[A-Z]{3}/", $matches[0], $test);
            $expires = $test[0];
        }
        else
            $expires = FALSE;
        
        return $expires;
    }
}
?>
