<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category Zym
 * @package Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Controller_Action_HelperBroker
 */
require_once 'Zend/Controller/Action/HelperBroker.php';

/**
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Helper_FlashMessenger
{
    /**
     * Retrieve the FlashMessenger action helper instance with the ability
     * to set the namespace for simplicity
     *
     * @param string $namespace
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function flashMessenger($namespace = null)
    {
        // Retrieve instance
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

        // Set namespace to retrieve
        if ($namespace !== null) {
            $flashMessenger->setNamespace($namespace);
        }

        return $flashMessenger;
    }
}
