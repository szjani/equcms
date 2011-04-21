<?php
namespace Equ\Controller\Plugin;
use Equ\Controller\Exception\RuntimeException;

/**
 * Check that user have or don't have permission to view current page
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class MvcPermission extends \Zend_Controller_Plugin_Abstract {

  /**
   * @param \Zend_Controller_Request_Abstract $request
   */
  public function preDispatch(\Zend_Controller_Request_Abstract $request) {
    try {
      $auth = \Zend_Auth::getInstance();
      $user = $auth->hasIdentity() ? $auth->getIdentity() : null;
      $container = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getContainer();
      $resource = 'mvc:'.$request->getModuleName().'.'.$request->getControllerName().'.'.$request->getActionName();
      if (!$container->get('acl')->isAllowed($user, $resource)) {
        throw new RuntimeException("You don't have permission to view this page!");
      }
    } catch (RuntimeException $e) {
      $request
        ->setModuleName('index')
        ->setControllerName('error')
        ->setActionName('error');

      $error = new \Zend_Controller_Plugin_ErrorHandler();
      $error->type = \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER;
      $error->request = clone $request;
      $error->exception = $e;
      $request->setParam('error_handler', $error);

    }
  }
}