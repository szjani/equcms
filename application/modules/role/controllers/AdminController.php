<?php
class Role_AdminController extends Factory_Controller {

  public function autocompleteAction() {
    $this->_helper->viewRenderer->setNoRender();
    $this->_helper->layout->disableLayout();
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
//    $results = array_push($results, array('id' => 0, 'role' => '---'));
//    $results[] = array('id' => 0, 'role' => '---');
    if ($role == null) {
      array_unshift($results, array('id' => 0, 'role' => ''));
    }
//    var_dump($results);
//    exit;
//    var_dump($results);
    $data = new Zend_Dojo_Data('id', $results);

    // Send our output
    $this->_helper->autoCompleteDojo($data);
  }

}