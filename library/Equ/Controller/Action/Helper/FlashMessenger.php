<?php
namespace Equ\Controller\Action\Helper;
use Equ\Message;

class FlashMessenger extends \Zend_Controller_Action_Helper_FlashMessenger {
  
  public function direct($message, $type = Message::SUCCESS, $namespace = 'default', $translate = true) {
    return $this->addMessage($message, $type, $namespace, $translate);
  }
  
  /**
   * Add a message object to flashmessenger
   *
   * @param string|Exception $message
   * @param string $type
   * @param string $namespace
   * @param boolean $translate
   * @return FlashMessenger
   */
  public function addMessage($message, $type = Message::SUCCESS, $namespace = 'default', $translate = true) {
    parent::setNamespace($namespace);
    $messageObj = null;
    if ($message instanceof \Exception) {
      $messageObj = new Message($message->getMessage(), Message::ERROR);
    } else {
      $messageObj = new Message($message, $type);
    }
    $messageObj->setTranslate($translate);
    return parent::addMessage($messageObj);
  }
  
}