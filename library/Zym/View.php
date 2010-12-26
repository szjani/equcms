<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category  Zym
 * @package   Zym_View
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license   http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_View_Abstract
 */
require_once 'Zym/View/Abstract.php';

/**
 * View component
 *
 * @author    Geoffrey Tran
 * @license   http://www.zym-project.com/license New BSD License
 * @category  Zym
 * @package   Zym_View
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View extends Zym_View_Abstract
{
    /**
     * Includes the view script in a scope with only public $this variables.
     *
     * @param string The view script to execute.
     */
    protected function _run()
    {
        if ($this->getStreamFlag()) {
            include $this->getStreamProtocol() . '://' . func_get_arg(0);
        } else {
            include func_get_arg(0);
        }
    }
}