<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category Zym
 * @package Zym_XmlRpc
 * @subpackage Server
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * Zym Cache
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_XmlRpc
 * @subpackage Server
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_XmlRpc_Server_Cache
{
    /**
     * Cache a file containing the dispatch list.
     *
     * Serializes the XMLRPC server callbacks array and stores the information
     * in Zend_Cache_Core
     *
     * @param string             $id
     * @param Zend_Cache_Core    $coreCache
     * @param Zend_XmlRpc_Server $server
     * @return bool
     */
    public static function save($id, Zend_Cache_Core $coreCache, Zend_XmlRpc_Server $server)
    {
        // Get function list
        $methods = $server->getFunctions();

        // Remove system.* methods
        foreach ($methods as $name => $method) {
            if ($method->system) {
                unset($methods[$name]);
            }
        }

        // Store
        return (bool) $coreCache->save(serialize($methods), $id, array(), null);
    }

    /**
     * Add dispatch table from a file
     *
     * Unserializes a stored dispatch table. Returns false if it
     * fails in any way, true on success.
     *
     * Useful to prevent needing to build the dispatch list on each XMLRPC
     * request. Sample usage:
     *
     * <code>
     * if (!Zym_XmlRpc_Server_Cache::get($id, $coreCache, $server)) {
     *     require_once 'Some/Service/Class.php';
     *     require_once 'Another/Service/Class.php';
     *
     *     // Attach Some_Service_Class with namespace 'some'
     *     $server->setClass('Some_Service_Class', 'some');
     *
     *     // Attach Another_Service_Class with namespace 'another'
     *     $server->setClass('Another_Service_Class', 'another');
     *
     *     Zym_XmlRpc_Server_Cache::save($id, $coreCache, $server);
     * }
     *
     * $response = $server->handle();
     * echo $response;
     * </code>
     *
     * @param string             $id
     * @param Zend_Cache_Core    $coreCache
     * @param Zend_XmlRpc_Server $server
     *
     * @return boolean
     */
    public static function get($id, Zend_Cache_Core $coreCache, Zend_XmlRpc_Server $server)
    {
        $dispatchArray = @unserialize($coreCache->load($id, false, true));

        try {
            $server->loadFunctions($dispatchArray);
        } catch (Zend_XmlRpc_Server_Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Remove a cache file
     *
     * @param string          $id
     * @param Zend_Cache_Core $coreCache
     * @return boolean
     */
    public static function delete($id, Zend_Cache_Core $coreCache)
    {
        return $coreCache->remove($id);
    }
}