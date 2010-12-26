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
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_View_Abstract
 */
require_once 'Zend/View/Abstract.php';

/**
 * @see Zym_View_Stream_Wrapper
 */
require_once 'Zym/View/Stream/Wrapper.php';

/**
 * View component
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_View
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
abstract class Zym_View_Abstract extends Zend_View_Abstract
{
    /**
     * Stack of Zend_View_Filter names to apply as stream filters.
     *
     * @var array
     */
    private $_streamFilter = array();

    /**
     * Flag to indicate whether to use streams
     *
     * @var boolean
     */
    private $_streamFlag   = true;

    /**
     * Stream protocol to use
     *
     * @var string
     */
    private $_streamProtocol = 'view';

    /**
     * PHP stream wrapper for views
     *
     * @var string
     */
    private $_streamWrapper = 'Zym_View_Stream_Wrapper';

    /**
     * Constructor.
     *
     * @param array $config Configuration key-value pairs.
     */
    public function __construct(array $config = array())
    {
        // Disable streams
        if (array_key_exists('streamFlag', $config)) {
            $this->setStreamFlag($config['streamFlag']);
        }

        // Stream protocol
        if (array_key_exists('streamProtocol', $config)) {
            $this->setStreamProtocol($config['streamProtocol']);
        }

        // Stream wrapper
        if (array_key_exists('streamWrapper', $config)) {
            $this->setStreamWrapper($config['streamWrapper']);
        }

        // User-defined stream filters
        if (array_key_exists('streamFilter', $config)) {
            $this->addStreamFilter($config['streamFilter']);
        }

        // Call parent
        parent::__construct($config);
    }

    /**
     * Return array of all currently active filters
     *
     * Returns array of strings if filters have not been
     * instantiated
     *
     * @return array
     */
    public function getStreamFilters()
    {
        return $this->_streamFilter;
    }

    /**
     * Add one or more stream filters to the stack in FIFO order.
     *
     * @param string|array One or more filters to add.
     * @return Zym_View_Abstract
     */
    public function addStreamFilter($name)
    {
        foreach ((array) $name as $val) {
            $this->_streamFilter[] = $val;
        }

        return $this;
    }

    /**
     * Resets the stream filter stack.
     *
     * To clear all filters, use Zend_View::setFilter(null).
     *
     * @param string|array One or more filters to set.
     * @return Zym_View_Abstract
     */
    public function setStreamFilter($name)
    {
        $this->_streamFilter = array();
        $this->addStreamFilter($name);

        return $this;
    }

    /**
     * Set stream flag
     *
     * Whether to disable or enable use of stream wrappers
     *
     * @param boolean $flag
     * @return Zym_View_Abstract
     */
    public function setStreamFlag($flag)
    {
        $this->_streamFlag = (bool) $flag;

        return $this;
    }

    /**
     * Get stream flag
     *
     * Whether streams are enabled or not
     *
     * @return boolean
     */
    public function getStreamFlag()
    {
        return $this->_streamFlag;
    }

    /**
     * Set view stream wrapper class
     *
     * Protocol must be alphanumeric
     *
     * @param string $protocol
     * @return Zym_View_Abstract
     */
    public function setStreamProtocol($protocol)
    {
        if (empty($protocol)) {
            /**
             * @see Zym_View_Exception
             */
            require_once 'Zym/View/Exception.php';
            throw new Zym_View_Exception(
                'Stream protocol "' . $protocol . '" cannot be empty'
            );
        }

        $this->_streamProtocol= (string) $protocol;

        return $this;
    }

    /**
     * Get stream protocol
     *
     * @return string
     */
    public function getStreamProtocol()
    {
        return $this->_streamProtocol;
    }

    /**
     * Set view stream wrapper class
     *
     * @param string $class
     * @return Zym_View_Abstract
     */
    public function setStreamWrapper($class)
    {
        $this->_streamWrapper = (string) $class;

        return $this;
    }

    /**
     * Get stream wrapper class
     *
     * @return string
     */
    public function getStreamWrapper()
    {
        return $this->_streamWrapper;
    }

    /**
     * Processes a view script and returns the output.
     *
     * @param string $name The script script name to process.
     * @return string The script output.
     */
    public function render($name)
    {
        // Revert to no stream
        if (!$this->getStreamFlag()) {
            return parent::render($name);
        }

        // Get stream class
        $stream         = $this->getStreamWrapper();
        $streamProtocol = $this->getStreamProtocol();

        // Do extra work if something already registered our protocol
        $previousWrapperExists = false;

        // Unregister existing wrapper
        if (in_array($streamProtocol, stream_get_wrappers())) {
            stream_wrapper_unregister($streamProtocol);
            $previousWrapperExists = true;
        }

        // Load stream wrapper
        $this->_loadStreamWrapper($stream);

        // Register wrapper
        stream_wrapper_register($streamProtocol, $stream);

        // Render!
        $return = parent::render($name);

        // Unregister wrapper
        if (in_array($streamProtocol, stream_get_wrappers())) {
            stream_wrapper_unregister($streamProtocol);
        }

        // Register any old wrapper
        if ($previousWrapperExists) {
            @stream_wrapper_restore($streamProtocol);
        }

        return $return;
    }

    /**
     * Retrieve plugin loader for a specific plugin type
     *
     * @param  string $type
     * @return Zend_Loader_PluginLoader
     */
    public function getPluginLoader($type)
    {
        $type   = strtolower($type);
        $loader = parent::getPluginLoader($type);

        // Add Zym Prefix
        $pType  = ucfirst($type);
        $prefix = 'Zym_View_' . $pType;
        $path   = 'Zym/View/' . $pType;

        if (!$loader->getPaths($prefix)) {
            switch ($type) {
                case 'filter':
                case 'helper':
                default:
                    $loader->addPrefixPath($prefix, $path);
                    break;
            }
        }

        return $loader;
    }

    /**
     * Load and setup stream wrapper
     *
     * @param string $stream
     */
    protected function _loadStreamWrapper($stream)
    {
        // Load and enforce inheritance
        Zend_Loader::loadClass($stream);
        if ($stream != 'Zym_View_Stream_Wrapper' && !is_subclass_of($stream, 'Zym_View_Stream_Wrapper')) {
            /**
             * @see Zym_View_Exception
             */
            require_once 'Zym/View/Exception.php';
            throw new Zym_View_Exception(
                'The stream wrapper provided is not a subclass of "Zym_View_Stream_Wrapper"'
            );
        }

        // Setup Wrapper
        call_user_func(array($stream, 'setView'), $this);
    }
}