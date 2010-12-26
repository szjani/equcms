<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Acl
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zend_Acl_Assert_Interface
 */
require_once 'Zend/Acl/Assert/Interface.php';

/**
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Acl
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Acl_Assert_Ip implements Zend_Acl_Assert_Interface
{
    /**
     * IP address whitelist
     *
     * @var array
     */
    protected $_addresses = array();

    /**
     * The wildcard
     *
     * @var string
     */
    protected $_wildcard = '*';

    /**
     * IP address separator
     *
     * @var string
     */
    protected $_separator = '.';

    /**
     * Regex to match IP ranges
     *
     * @var string
     */
    protected $_rangeRegex = '/(\d-\d)/';

    /**
     * Range start and end characters
     *
     * @var string|array
     */
    protected $_rangeSentinels = array('(', ')');

    /**
     * Range delimiter
     *
     * @var string
     */
    protected $_rangeDelimiter = '-';

    /**
     * Constructor
     *
     * @param array $addresses
     */
    public function __construct(array $addresses = array())
    {
        $this->_addresses = $addresses;
    }

    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Zend_Acl                    $acl
     * @param  Zend_Acl_Role_Interface     $role
     * @param  Zend_Acl_Resource_Interface $resource
     * @param  string                      $privilege
     * @return boolean
     */
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null,
                           Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        return $this->_isCleanIP($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Check if the the IP is in the whitelist
     *
     * @param string $ip
     * @return boolean
     */
    protected function _isCleanIP($ip)
    {
        foreach ($this->_addresses as $ipAddress) {
            if ($ip == $ipAddress) {
                return true;
            } else if (strpos($ipAddress, $this->_wildcard) !== false) {
                $wildcardIp = str_replace($this->_wildcard, '', $ipAddress);

                if (strpos($ip, $wildcardIp) === 0) {
                    return true;
                }
            } else if (preg_match($this->_rangeRegex, $ipAddress) == 1) {
                $exploded = explode($this->_separator, $ipAddress);

                $range = array_pop($exploded);

                $range = str_replace($this->_rangeSentinels, '', $range);

                $ipStart = implode($this->_separator, $exploded);

                if (strpos($ip, $ipStart) === 0) {
                    list($rangeStart, $rangeEnd) = explode($this->_rangeDelimiter, $range);

                    for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
                        $checkIp = implode($this->_separator, array($ipStart, $i));

                        if ($ip == $checkIp) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}