<?php
namespace Equ\Controller\Plugin;

class Navigation extends \Zend_Controller_Plugin_Abstract {

  public function routeShutdown(\Zend_Controller_Request_Abstract $request) {
    if ($request->getParam('format') == 'ajax') {
      return;
    }
    $container = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getContainer();
    $cache = $container->get('cache.system');
    if ($navContainer = ($cache->load('navigation'))) {
      $container->set('navigation', $navContainer);
    } else {
      $navContainer = $container->get('navigation');
      /* @var $em \Doctrine\ORM\EntityManager */
      $em = $container->get('doctrine.entitymanager');
      $query = $em->createQuery('SELECT m FROM entities\Mvc m ORDER BY m.lvl');
      foreach ($query->getResult() as $mvc) {
        if (false !== strpos((string)$mvc->getNavigationPage()->getResource(), 'update')) {
          $mvc->getNavigationPage()->setVisible(false);
        }
        $parentNav = $mvc->getParent() ? $mvc->getParent()->getNavigationPage() : $navContainer;
        $parentNav->addPage($mvc->getNavigationPage());
      }
      $cache->save($navContainer, 'navigation');
    }

    $view = \Zend_Layout::getMvcInstance()->getView();
    $view->getHelper('navigation')->setContainer($container->get('navigation'));
    $view->getHelper('navigation')->setAcl($container->get('acl'));
    $view->getHelper('navigation')->setRole(\Zend_Auth::getInstance()->getIdentity());
  }

}