<?php
class Parables_Session_SaveHandler_Doctrine 
    implements Zend_Session_SaveHandler_Interface
{
    const DATA_COLUMN           = 'dataColumn';
    const LIFETIME_COLUMN       = 'lifetimeColumn';
    const MODIFIED_COLUMN       = 'modifiedColumn';
    const PRIMARY_KEY_COLUMN    = 'primaryKeyColumn';

    const LIFETIME              = 'lifetime';
    const OVERRIDE_LIFETIME     = 'overrideLifetime';
    const TABLE_NAME            = 'tableName';

    /**
     * @var string
     */
    protected $_dataColumn = null;

    /**
     * @var string
     */
    protected $_lifetimeColumn = null;

    /**
     * @var string
     */
    protected $_modifiedColumn = null;

    /**
     * @var string
     */
    protected $_primaryKeyColumn = null;

    /**
     * @var int
     */
    protected $_lifetime = false;

    /**
     * @var boolean
     */
    protected $_overrideLifetime = false;

    /**
     * @var string
     */
    protected $_sessionName = null;

    /**
     * @var string
     */
    protected $_sessionSavePath = null;

    /**
     * @var string
     */
    protected $_tableName = null;

    /**
     * Constructor
     *
     * @param  Zend_Config|array $config
     * @return void
     * @throws Zend_Session_SaveHandler_Exception
     */
    public function __construct($config)
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        } elseif (!is_array($config)) {
            throw new Zend_Session_SaveHandler_Exception('Options must be an instance of Zend_Config or array.');
        }

        foreach ($config as $key => $value) {
            do {
                switch ($key) {
                    case self::DATA_COLUMN:
                        $this->_dataColumn = (string) $value;
                        break;
                    case self::LIFETIME_COLUMN:
                        $this->_lifetimeColumn = (string) $value;
                        break;
                    case self::MODIFIED_COLUMN:
                        $this->_modifiedColumn = (string) $value;
                        break;
                    case self::PRIMARY_KEY_COLUMN:
                        $this->setPrimaryKeyColumn($value);
                        break;
                    case self::LIFETIME:
                        $this->setLifetime($value);
                        break;
                    case self::OVERRIDE_LIFETIME:
                        $this->setOverrideLifetime($value);
                        break;
                    case self::TABLE_NAME:
                        $this->setTableName($value);
                        break;
                    default:
                        break 2;
                }
                unset($config[$key]);
            } while (false);
        }
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        Zend_Session::writeClose();
    }

    /**
     * Set session lifetime and optional whether or not the lifetime of an 
     * existing session should be overridden
     *
     * $lifetime === false resets lifetime to session.gc_maxlifetime
     *
     * @param int $lifetime
     * @param boolean $overrideLifetime (optional)
     * @return Parables_Session_SaveHandler_Table
     * @throws Zend_Session_SaveHandler_Exception
     */
    public function setLifetime($lifetime, $overrideLifetime = null)
    {
        if ($lifetime < 0) {
            throw new Zend_Session_SaveHandler_Exception('Session lifetime must be greater than zero.');
        } else if (empty($lifetime)) {
            $this->_lifetime = (int) ini_get('session.gc_maxlifetime');
        } else {
            $this->_lifetime = (int) $lifetime;
        }

        if ($overrideLifetime != null) {
            $this->setOverrideLifetime($overrideLifetime);
        }

        return $this;
    }

    /**
     * Retrieve session lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     * Set whether or not the lifetime of an existing session should be 
     * overridden
     *
     * @param boolean $overrideLifetime
     * @return Parables_Session_SaveHandler_Table
     */
    public function setOverrideLifetime($overrideLifetime)
    {
        $this->_overrideLifetime = (boolean) $overrideLifetime;
        return $this;
    }

    /**
     * Retrieve whether or not the lifetime of an existing session should be 
     * overridden
     *
     * @return boolean
     */
    public function getOverrideLifetime()
    {
        return $this->_overrideLifetime;
    }

    /**
     * Set primary key column
     *
     * @param string|array $key
     * @return Parables_Session_SaveHandler_Table
     * @throws Zend_Session_SaveHandler_Exception
     */
    public function setPrimaryKeyColumn($key = 'id')
    {
        if (is_string($key)) {
            $this->_primaryKeyColumn = $key;
        } else {
            throw new Zend_Session_SaveHandler_Exception('Invalid primary key column.');
        }

        return $this;
    }

    /**
     * Retrieve primary key column
     *
     * @return array
     */
    public function getPrimaryKeyColumn()
    {
        return $this->_primaryKeyColumn;
    }

    /**
     * Set session table name
     *
     * @param string $name
     * @return Parables_Session_SaveHandler_Table
     */
    public function setTableName($name = 'Session')
    {
        $this->_tableName = $name;
        return $this;
    }

    /**
     * Retrieve session table name
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }

    /**
     * Open Session
     *
     * @param string $savePath
     * @param string $name
     * @return boolean
     */
    public function open($savePath, $name)
    {
        $this->_sessionSavePath = $savePath;
        $this->_sessionName     = $name;
        return true;
    }

    /**
     * Close session
     *
     * @return boolean
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $id Session identifier
     * @return string Session data
     */
    public function read($id)
    {
        $return = '';

        $record = Doctrine::getTable($this->_tableName)->find($id);
        if (false !== $record) {
            if ($this->_getExpirationTime($record)) {
                $return = $record->{$this->_dataColumn};
            } else {
                $this->destroy($id);
            }
        }

        return $return;
    }

    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return boolean
     */
    public function write($id, $data)
    {
        $return = false;

        $session = Doctrine::getTable($this->_tableName)->find($id);
        if (false === $session) {
            $session = new Session();
            $session->{$this->_primaryKeyColumn} = $id;
        }

        $session->{$this->_dataColumn} = $data;
        $session->{$this->_lifetimeColumn} = $this->_lifetime;
        $session->{$this->_modifiedColumn} = time();

        if ($session->save()) {
            return true;
        }

        return $return;
    }

    /**
     * Destroy session
     *
     * @param string $id
     * @return boolean
     */
    public function destroy($id)
    {
        $return = false;

        $record = Doctrine::getTable($this->_tableName)->find($id);
        if (false !== $record) {
            if ($record->delete()) {
                $return = true;
            }
        }

        return $return;
    }

    /**
     * Garbage Collection
     *
     * @param int $maxlifetime
     * @return true
     */
    public function gc($maxlifetime)
    {
        $sessions = Doctrine::getTable($this->_tableName)->findAll();
        foreach ($sessions as $session) {
            $expiration = $session->{$this->modifiedColumn} + 
                $session->{$this->lifetimeColumn};
            if ($expiration < time()) {
                $session->delete();
            }
        }

        return true;
    }

    /**
     * Retrieve session lifetime
     *
     * @param Doctrine_Record $record
     * @return int
     */
    protected function _getLifetime(Doctrine_Record $record)
    {
        $return = $this->_lifetime;

        if (!$this->_overrideLifetime) {
            $return = (int) $record->{$this->_lifetimeColumn};
        }

        return $return;
    }

    /**
     * Retrieve session expiration time
     *
     * @param Doctrine_Record $record
     * @return int
     */
    protected function _getExpirationTime(Doctrine_Record $record)
    {
        return (int) $record->{$this->_modifiedColumn} + 
            $this->_getLifetime($record);
    }
}
