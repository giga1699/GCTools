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
        $domain = explode(".", $domain);
        $tld = strtolower($domain[count($domain)-1]);
        
        return array_key_exists($tld, $this->servers);
    }
    
    public function Lookup($domain) {
        $domain = explode(".", $domain);
        $tld = strtolower($domain[count($domain)-1]);
        
        $link = fsockopen($this->servers[$tld], "43");
        
        if (!$link)
            return FALSE;
        
        fwrite($link, implode(".", $domain) . "\r\n");
        
        $data = "";
        
        while (!feof($link)) {
            $data .= fgets($link);
        }
        
        fclose($link);
        
        return $data;
    }
}
?>
