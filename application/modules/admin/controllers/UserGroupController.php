<?php
use Equ\Crud\Controller;

class Admin_UserGroupController extends Controller {

  protected $ignoredFields = array('lft', 'rgt', 'lvl', 'role');

	/**
   * @see Equ\Crud.Controller::getService()
   */
  protected function getService() {
//    /* @var $em \Doctrine\ORM\EntityManager */
//    $em = $this->_helper->serviceContainer('doctrine.entitymanager');
//    $res = $em->createQueryBuilder()
//      ->select('node')
//      ->from('\entities\Role', 'node')
//      ->getQuery()
//      ->getArrayResult();
//    var_dump($res, '---');
    return $this->_helper->serviceContainer('usergroup');
  }

}