<?php
namespace Equ\Controller\Plugin;

class Navigation extends \Zend_Controller_Plugin_Abstract {

  public function routeStartup(\Zend_Controller_Request_Abstract $request) {
    $container = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getContainer();
    $cache = $container->get('cache.system');
    if ($navContainerArray = ($cache->load('navigation'))) {
      $navContainer = new \Zend_Navigation($navContainerArray);
      $container->set('navigation', $navContainer);
    } else {
      $navContainer = $container->get('navigation');
      /* @var $em \Doctrine\ORM\EntityManager */
      $em = $container->get('doctrine.entitymanager');
      $leaves = $em->getRepository('\entities\Mvc')->getLeafs();
      /* @var $mvc \entities\Mvc */
      foreach ($leaves as $mvc) {
        $parent = $mvc->getParent();
        while ($parent !== null) {
          if (false !== strpos((string)$mvc->getNavigationPage()->getResource(), 'update')) {
            $mvc->getNavigationPage()->setVisible(false);
          }
          $parent->getNavigationPage()->addPage($mvc->getNavigationPage());
          $mvc = $parent;
          $parent = $mvc->getParent();
        }
        $navContainer->addPage($mvc->getNavigationPage());
      }
      $cache->save($navContainer->toArray(), 'navigation');
    }

    $view = \Zend_Layout::getMvcInstance()->getView();
    $view->getHelper('navigation')->setContainer($container->get('navigation'));
//    $view->getHelper('navigation')->setAcl($container->get('acl'));
//    $view->getHelper('navigation')->setRole('szjani@szjani.hu');
  }

}