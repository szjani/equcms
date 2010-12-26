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
 * @subpackage Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_View_Helper_Abstract
 */
require_once 'Zym/View/Helper/Abstract.php';

/**
 * @see Zym_View_Filter_Interface
 */
require_once 'Zym/View/Filter/Interface.php';

/**
 * Abstract view filter
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @package Zym_View
 * @subpackage Filter
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
abstract class Zym_View_Filter_Abstract extends Zym_View_Helper_Abstract 
    implements Zym_View_Filter_Interface
{
    /**
     * Filter
     *
     * @param  string $buffer
     * @return string
     */
    //abstract public function filter($buffer);
}