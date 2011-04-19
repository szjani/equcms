<?php
class Role_AdminController extends \Equ\AbstractController {

  public function autocompleteAction() {
    $role = $this->_getParam("role", null);
    $role = substr($role, 0, -1); //fix : remove * at the end of the ID.

    /* @var $em \Doctrine\ORM\EntityManager */
    $em = $this->_helper->serviceContainer('doctrine.entitymanager');
    $results = $em->createQueryBuilder()
      ->select('r.id, r.role')
      ->from('entities\Role', 'r')
      ->where("r.role LIKE :role")
      ->setParameter('role', '%'.$role.'%')
      ->setMaxResults(3)
      ->getQuery()
      ->getArrayResult();
    if ($role == null) {
      array_unshift($results, array('id' => 0, 'role' => ''));
    }
    $data = new Zend_Dojo_Data('id', $results);

    // Send our output
    $this->_helper->autoCompleteDojo($data);
  }

}