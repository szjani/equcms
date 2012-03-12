<?php
namespace modules\user\plugins;
use
  modules\user\models\Anonymous,
  Equ\Auth\AuthenticatedUserStorage,
  Zend_Acl;

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
   * @var AuthenticatedUserStorage
   */
  private $storage;
  
  /**
   *
   * @var Zend_Acl
   */
  private $acl;
  
  /**
   * @param AuthenticatedUserStorage $storage
   * @param Zend_Acl $acl
   */
  public function __construct(AuthenticatedUserStorage $storage, Zend_Acl $acl) {
    $this->storage = $storage;
    $this->acl     = $acl;
  }
  
  /**
   * @param Zend_Controller_Request_Abstract $request
   */
  public function routeStartup(\Zend_Controller_Request_Abstract $request) {
    $user = $this->storage->getAuthenticatedUser();
    if (!$this->acl->hasRole($user)) {
      $this->acl->addRole($user, 'Everybody');
    }
  }
}