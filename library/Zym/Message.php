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
class Zym_Message
{
    /**
     * Message name
     *
     * @var string
     */
    protected $_name;

    /**
     * The object that sent the message
     *
     * @var object
     */
    protected $_sender;

    /**
     * Optional objects
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Constructor
     *
     * @param string $name
     * @param object $sender
     * @param array $data
     */
    public function __construct($name, $sender, array $data = array())
    {
        $this->_name   = $name;
        $this->_sender = $sender;
        $this->_data   = $data;
    }

    /**
     * Get message name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get the object that sent the message
     *
     * @return object
     */
    public function getSender()
    {
        return $this->_sender;
    }

    /**
     * Get the optional information
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
}