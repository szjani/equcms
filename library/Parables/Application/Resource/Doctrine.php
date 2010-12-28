<?php

class Parables_Application_Resource_Doctrine 
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Doctrine_Cli
     */
    protected $_cli = null;

    /**
     *
     * @var array
     */
    protected $_paths = array();

    /**
     * Build DSN string from an array
     *
     * @param   array $dsn
     * @return  string
     */
    protected function _buildDsnFromArray(array $dsn)
    {
        $options = null;
        if (array_key_exists('options', $dsn)) {
            $options = http_build_query($dsn['options']);
        }

        return sprintf('%s://%s:%s@%s/%s?%s',
            $dsn['adapter'],
            $dsn['user'],
            $dsn['pass'],
            $dsn['hostspec'],
            $dsn['database'],
            $options);
    }

    /**
     * Set attributes for a Doctrine_Configurable instance
     *
     * @param   Doctrine_Configurable $object
     * @param   array $attributes
     * @return  void
     * @throws  Zend_Application_Resource_Exception
     */
    protected function _setAttributes(Doctrine_Configurable $object, array $attributes)
    {
        $reflect = new ReflectionClass('Doctrine_Core');
        $doctrineConstants = $reflect->getConstants();

        $attributes = array_change_key_case($attributes, CASE_UPPER);

        foreach ($attributes as $key => $value) {
            if (!array_key_exists($key, $doctrineConstants)) {
                throw new Zend_Application_Resource_Exception("Invalid attribute $key.");
            }

            $attrIdx = $doctrineConstants[$key];
            $attrVal = $value;

            if (Doctrine_Core::ATTR_QUERY_CACHE == $attrIdx) {
                $attrVal = $this->_getCache($value);
            } elseif (Doctrine_Core::ATTR_RESULT_CACHE == $attrIdx) {
                $attrVal = $this->_getCache($value);
            } else {
                if (is_string($value)) {
                    $value = strtoupper($value);
                    if (array_key_exists($value, $doctrineConstants)) {
                        $attrVal = $doctrineConstants[$value];
                    }
                }
            }

            $object->setAttribute($attrIdx, $attrVal);
        }
    }

    /**
     * Set connection listeners
     *
     * @param   Doctrine_Connection_Common $conn
     * @param   array $options
     * @return  void
     * @throws  Zend_Application_Resource_Exception
     */
    protected function _setConnectionListeners(Doctrine_Connection_Common $conn, array $options)
    {
        foreach ($options as $alias => $class) {
            $listener = $this->_getListenerInstance($class);
            $conn->addListener($listener, $alias);
        }
    }

    /**
     *
     * @param array|string $options
     * @return object
     */
    protected function _getListenerInstance($options)
    {
        if (is_array($options)) {
            if(isset($options[0])) {
                $class = $options[0];
                if (isset($options[1])) {
                    $params = $options[1];
                } else {
                    $params = array();
                }
            } else {
                throw new Zend_Application_Resource_Exception("invalid options for listener");
            }
        } else {
            $class = $options;
            $params = array();
        }
        try {
            @Zend_Loader::loadClass($class);
        } catch (Zend_Exception $e) {
            throw new Zend_Application_Resource_Exception("$class does not exist.");
        }

        $refClass = new ReflectionClass($class);
        if (empty($params)) {
            $listener = $refClass->newInstance();
        } else {
            $listener = $refClass->newInstanceArgs($params);
        }
        return $listener;
    }

    /**
     *
     * @param Doctrine_Connection_Common $conn
     * @param array $options
     */
    protected function _setConnectionRecordListeners(Doctrine_Connection_Common $conn, array $options)
    {
        foreach ($options as $alias => $class) {
            $listener = $this->_getListenerInstance($class);
            $conn->addRecordListener($listener, $alias);
        }
    }

    /**
     * Retrieve a Doctrine_Cache instance
     *
     * @param   array $options
     * @return  Doctrine_Cache
     * @throws  Zend_Application_Resource_Exception
     */
    protected function _getCache(array $options)
    {
        if (!array_key_exists('driver', $options)) {
            throw new Zend_Application_Resource_Exception('Undefined cache driver.');
        }

        switch ($options['driver'])
        {
            case 'apc':
                return new Doctrine_Cache_Apc();

            case 'db':
                if (!array_key_exists('options', $options)) {
                    throw new Zend_Application_Resource_Exception('Undefined db cache options.');
                }

                if (empty($options['options'])) {
                    throw new Zend_Application_Resource_Exception('Invalid db cache options.');
                }

                if (!array_key_exists('dsn', $options['options'])) {
                    throw new Zend_Application_Resource_Exception("Undefined db cache DSN.");
                }

                if (empty($options['options']['dsn'])) {
                    throw new Zend_Application_Resource_Exception("Invalid db cache DSN.");
                }

                if (!array_key_exists('tableName', $options['options'])) {
                    throw new Zend_Application_Resource_Exception("Undefined db cache table name.");
                }

                if (empty($options['options']['tableName'])) {
                    throw new Zend_Application_Resource_Exception("Invalid db cache table name.");
                }

                $dsn = (is_array($options['options']['dsn']))
                    ? $this->_buildDsnFromArray($options['options']['dsn'])
                    : $options['options']['dsn'];

                $cacheConn = Doctrine_Manager::connection($dsn);

                $cache = new Doctrine_Cache_Db(array(
                    'connection' => $cacheConn,
                    'tableName' => $options['options']['tableName'],
                ));

                return $cache;

            case 'memcache':
                if (!array_key_exists('options', $options)) {
                    throw new Zend_Application_Resource_Exception('Undefined memcache options.');
                }

                if (empty($options['options'])) {
                    throw new Zend_Application_Resource_Exception('Invalid memcache options.');
                }

                return new Doctrine_Cache_Memcache($options['options']);

            case 'xcache':
                return new Doctrine_Cache_Xcache();

            default:
                try {
                    @Zend_Loader::loadClass($options['driver']);
                } catch (Zend_Exception $e) {
                    throw new Zend_Application_Resource_Exception("Unsupported cache driver: " . $options['driver']);
                }
                $refClass = new ReflectionClass($options['driver']);
                if (!empty($options['options']) && is_array($options['options'])) {
                    $driver = $refClass->newInstanceArgs($options['options']);
                } else {
                    $driver = $refClass->newInstance();
                }
                return $driver;
        }
    }

    /**
     * Set manager level attributes
     *
     * @param   array $options
     * @return  Parables_Application_Resource_Doctrine
     */
    public function setupManager(array $options)
    {
        $options = array_change_key_case($options, CASE_LOWER);

        if (array_key_exists('attributes', $options)) {
            $this->_setAttributes(
                Doctrine_Manager::getInstance(),
                $options['attributes']
            );
        }

        return $this;
    }

    /**
     * Set connections and connection level attributes
     *
     * @param   array $options
     * @return  Parables_Application_Resource_Doctrine
     * @throws  Zend_Application_Resource_Exception
     */
    public function setupConnections(array $options)
    {
        $options = array_change_key_case($options, CASE_LOWER);

        foreach($options as $key => $value) {
            if (!is_array($value)) {
                throw new Zend_Application_Resource_Exception("Invalid connection on $key.");
            }
                
            if (!array_key_exists('dsn', $value)) {
                throw new Zend_Application_Resource_Exception("Undefined DSN on $key.");
            }

            if (empty($value['dsn'])) {
                throw new Zend_Application_Resource_Exception("Invalid DSN on $key.");
            }

            $dsn = (is_array($value['dsn']))
                ? $this->_buildDsnFromArray($value['dsn'])
                : $value['dsn'];

            $conn = Doctrine_Manager::connection($dsn, $key);

            if (array_key_exists('attributes', $value)) {
                $this->_setAttributes($conn, $value['attributes']);
            }

            if (array_key_exists('listeners', $value)) {
                $this->_setConnectionListeners($conn, $value['listeners']);
            }

            if (array_key_exists('recordListeners', $value)) {
                $this->_setConnectionRecordListeners($conn, $value['recordListeners']);
            }
            
            if (array_key_exists('charset', $value)) {
                $charsetListener = new Parables_Doctrine_EventListener_Charset($value['charset']);
                $conn->addListener($charsetListener, 'charset');
            }
        }

        return $this;
    }

    /**
     * Initialize Doctrine paths
     *
     * @param   array $options
     * @return  Parables_Application_Resource_Doctrine
     * @throws  Zend_Application_Resource_Exception
     */
    protected function setupPaths(array $options)
    {
        $options = array_change_key_case($options, CASE_LOWER);
        foreach ($options as $key => $value) {
            if (!is_array($value)) {
                throw new Zend_Application_Resource_Exception("Invalid paths on $key.");
            }
            $this->_paths[$key] = array();
             foreach ($value as $subKey => $subVal) {
                if (!empty($subVal)) {
                    $path = realpath($subVal);

                    if (!is_dir($path)) {
                        throw new Zend_Application_Resource_Exception("$subVal does not exist.");
                    }
                    $this->_paths[$key][$subKey] = $path;
                }
            }
        }
        return $this;
    }

    /**
     * Retrieve paths
     *
     * @return  array
     */
    public function getPaths()
    {
        return $this->_paths;
    }

    /**
     * Initializes cli
     * @param array $options
     */
    protected function setupCli(array $options)
    {
        if (!isset($options['config'])) {
            throw new Zend_Application_Resource_Exception('No config for cli found');
        }
        $config = $options['config'];

        $this->_cli = new Doctrine_Cli($config);

        if (isset($options['registryKey'])) {
            Zend_Registry::set($options['registryKey'], $this->_cli);
        }
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return  Parables_Application_Resource_Doctrine
     * @throws  Zend_Application_Resource_Exception
     */
    public function init()
    {
        if (isset($this->_options['compiled'])) {
            require $this->_options['compiled'];
        }
        
        if (isset($this->_options['paths'])) {
            $this->setupPaths($this->_options['paths']);
        }

        if (isset($this->_options['manager'])) {
            $this->setupManager($this->_options['manager']);
        }
        
        if (isset($this->_options['connections'])) {
            $this->setupConnections($this->_options['connections']);
        }

        if (isset($this->_options['cli'])) {
            $this->setupCli($this->_options['cli']);
        }
        return $this;
    }

    public function getCli()
    {
        return $this->_cli;
    }
}
