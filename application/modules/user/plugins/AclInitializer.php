<?php
namespace modules\user\plugins;
use modules\user\models\Anonymous;

/**
 * Add anonymous user to ACL
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @subpackage  plugin
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class AclInitializer extends \Zend_Controller_Plugin_Abstract {

  /**
   * @param Zend_Controller_Request_Abstract $request
   */
  public function routeStartup(\Zend_Controller_Request_Abstract $request) {
    if (\Zend_Auth::getInstance()->getIdentity() == Anonymous::NAME) {
      $container = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getContainer();
      $acl = $container->get('acl');
      $acl->addRole(Anonymous::NAME, 'Everybody');
    }
  }
}