<?php
/**
 * IndexController
 *
 * @category   Factory
 * @package    Controller
 * @author     $$email$$
 * @version    $Id:$
 */

class IndexController extends Factory_Controller {
  public function indexAction() {
    /* @var $em Doctrine\ORM\EntityManager */
    $em = $this->getFrontController()->getInstance()->getParam('bootstrap')->getContainer()->get('doctrine.entitymanager');
//    $user = new Entity\User('szjani@gmail.com', '$password');
//    $em->persist($user);
//    $em->flush();
//    $formBuilder = new Default_Form_UserBuilder(
//    $user->accept($formBuilder);
    $formBuilder = new Equ\Entity\FormBuilder($em);
    $roleResource = new Entity\RoleResource();
//    $roleResource = $em->getRepository('Entity\RoleResource')->find(1);
    $roleResource->accept($formBuilder);
    $form = $formBuilder->getForm();
//    Doctrine\Common\Util\Debug::dump($roleResource);
    if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
      $entityBuilder = new Equ\Form\EntityBuilder($em, $roleResource);
      $form->accept($entityBuilder);
//      Doctrine\Common\Util\Debug::dump($roleResource);
      $em->persist($roleResource);
      $em->flush();
//      var_dump($roleResource);
    }

    $this->view->form = $form;
    /* @var $em Doctrine\ORM\EntityManager */
//    $em = $this->getFrontController()->getInstance()->getParam('bootstrap')->getContainer()->get('doctrine.entitymanager');
  }
}
