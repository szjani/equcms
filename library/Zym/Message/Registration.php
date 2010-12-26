<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Message
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Message
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Message_Registration
{
    /**
     * @var object
     */
    protected $_observer = null;

    /**
     * @var string
     */
    protected $_callback = null;

    /**
     * Constructor
     *
     * @param object $observer
     * @param string $callback
     */
    public function __construct($observer, $callback)
    {
        $this->_observer = $observer;
        $this->_callback = $callback;
    }

    /**
     * Get the observer
     *
     * @return object
     */
    public function getObserver()
    {
        return $this->_observer;
    }

    /**
     * Get the name of the callback method
     *
     * @return string
     */
    public function getCallback()
    {
        return $this->_callback;
    }
}