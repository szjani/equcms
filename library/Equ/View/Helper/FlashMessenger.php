<?php
namespace Equ\View\Helper;
use Equ\Message;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class FlashMessenger extends \Zend_View_Helper_Abstract {

  /**
   * @param string $namespace
   * @return string rendered HTML
   */
  public function flashMessenger($namespace = 'default') {
    $flashMessenger = \Zend_Controller_Action_HelperBroker::getStaticHelper('flashMessenger');
    $flashMessenger->setNamespace($namespace);
    $messages = $flashMessenger->getMessages() + $flashMessenger->getCurrentMessages();
    $flashMessenger->clearMessages();
    $flashMessenger->clearCurrentMessages();
    $viewTypes = array();
    foreach ($messages as $message) {
      if ($message instanceof Message) {
        if (!array_key_exists($message->getType(), $viewTypes)) {
          $viewTypes[$message->getType()] = array();
        }
        $viewTypes[$message->getType()][] = $message->getMessage();
      }
    }
    $this->view->messageTypes = $viewTypes;
    return $this->view->render('message.phtml');
  }

}