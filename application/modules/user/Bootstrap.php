<?php
use modules\user\plugins\AclInitializer;
use modules\user\models\Anonymous;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    modules
 * @package     user
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class User_Bootstrap extends Zend_Application_Module_Bootstrap {

  protected function _initAnonymousUser() {
    Zend_Controller_Front::getInstance()->registerPlugin(new AclInitializer());
    $auth = Zend_Auth::getInstance();
    if (!$auth->hasIdentity()) {
      $auth->getStorage()->write(new Anonymous());
    }
  }

}