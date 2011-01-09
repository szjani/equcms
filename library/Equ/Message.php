<?php
namespace Equ;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class Message {

  const ERROR   = 'error';
  const SUCCESS = 'success';
  const WARNING = 'warning';

  /**
   * @var string
   */
  private $message;

  /**
   * @var string
   */
  private $type;

  /**
   * @var \Zend_Translate
   */
  private $translator;

  /**
   * @var boolean
   */
  private $translate = true;

  /**
   * @param string $message
   * @param string $type
   */
  public function __construct($message, $type = self::SUCCESS) {
    $this->setMessage($message);
    $this->setType($type);
  }

  /**
   * @return Zend_Translate
   */
  public function getTranslator() {
    if ($this->translator === null) {
      $this->translator = \Zend_Registry::get('Zend_Translate');
    }
    return $this->translator;
  }

  /**
   * @param boolean $translate
   * @return Message
   */
  public function setTranslate($translate) {
    $this->translate = (boolean)$translate;
    return $this;
  }

  /**
   * @param string $type
   * @return Message
   */
  public function setType($type) {
    $this->type = $type;
    return $this;
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @param string $message
   * @return Message
   */
  public function setMessage($message) {
    $this->message = (string)$message;
    return $this;
  }

  /**
   * @return string
   */
  public function getMessage() {
    if ($this->translate) {
      return $this->getTranslator()->translate($this->message);
    }
    return $this->message;
  }

  /**
   * @return array
   */
  public function toArray() {
    return array(
      'message' => $this->getMessage(),
      'type' => $this->type
    );
  }

}