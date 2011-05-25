<?php
/**
 * Filename: computer.inc.php
 * @author J. "Giga" Murphy <giga1699@gmail.com>
 * Purpose: Provide the ability to abstract a computer system
 * @version 0.0.1
 * File created: 25MAY2011
 * @package GCTools
 * @subpackage Computer
 */

class Computer {
	protected $id; //A unique computer ID
	protected $name; //The computer's name
	protected $ip; //The IPv4 address of the computer
	protected $ip6; //The IPv6 address of the computer
}