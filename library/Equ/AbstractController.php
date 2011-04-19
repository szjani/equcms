<?php
namespace Equ;

abstract class AbstractController extends \Zend_Controller_Action {

  public function init() {
    $this->view->messages = array();
  }

  /**
   * Add a message object to flashmessenger
   *
   * @param string|Exception $message
   * @return Controller
   */
  public function addMessage($message, $namespace = 'default', $type = Message::SUCCESS, $translate = true) {
    $flashMessenger = $this->_helper->getHelper('flashMessenger');
    $flashMessenger->setNamespace($namespace);
    $messageObj = null;
    if ($message instanceof Exception) {
      $messageObj = new Message($message->getMessage(), Message::ERROR);
    } else {
      $messageObj = new Message($message, $type);
    }
    $messageObj->setTranslate($translate);
    $flashMessenger->addMessage($messageObj);
    return $this;
  }

}