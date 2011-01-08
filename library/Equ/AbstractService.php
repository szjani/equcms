<?php
namespace Equ;

/**
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        $Link$
 * @since       0.1
 * @version     $Revision$
 * @author      Szurovecz János <szjani@szjani.hu>
 */
class AbstractService {

  /**
   *
   * @var \Zend_Log
   */
  private $log = null;

  /**
   * @var EntityManager
   */
  private $entityManager = null;

  /**
   * @return \Zend_Log
   */
  public function getLog() {
    if ($this->log === null) {
      $this->log = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
        ->getContainer()->get('log');
    }
    return $this->log;
  }

  /**
   * @param \Zend_Log $log
   * @return AbstractService
   */
  public function setLog(\Zend_Log $log) {
    $this->log = $log;
    return $this;
  }

  /**
   * @return EntityManager
   */
  public final function getEntityManager() {
    if ($this->entityManager === null) {
      $this->entityManager = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
        ->getContainer()->get('doctrine.entitymanager');
    }
    return $this->entityManager;
  }

  /**
   * @param EntityManager $em
   * @return AbstractService
   */
  public final function setEntityManager(EntityManager $em) {
    $this->entityManager = $em;
    return $this;
  }

}