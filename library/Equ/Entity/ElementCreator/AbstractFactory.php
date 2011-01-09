<?php
namespace Equ\Entity\ElementCreator;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
abstract class AbstractFactory implements Factory {

  /**
   * @var string
   */
  private $namespace = null;

  /**
   * HTML5 and DOJO supports it...
   *
   * @var boolean
   */
  private $usePlaceHolders = false;

  /**
   * @param string $ns
   * @return AbstractFactory
   */
  public function setNamespace($ns) {
    $this->namespace = $ns;
    return $this;
  }

  /**
   * @param boolean $use
   * @return AbstractFactory
   */
  public function usePlaceHolders($use = true) {
    $this->usePlaceHolders = (boolean)$use;
    return $this;
  }

  /**
   * @return boolean
   */
  public function isUsedPlaceHolders() {
    return $this->usePlaceHolders;
  }

  /**
   * @return string
   */
  public function getNamespace() {
    return $this->namespace;
  }
  
}