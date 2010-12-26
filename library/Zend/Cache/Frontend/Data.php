<?php
class Zend_Cache_Frontend_Data extends Zend_Cache_Core
{

    private $_idStack = array();

    /**
     * Constructor
     *
     * @param  array $options Associative array of options
     * @return void
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $this->_idStack = array();
    }

    /**
     * Start the cache
     *
     * @param  string  $id                     Cache id
     * @param  array $params
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return mixed
     */
    public function start($id, array $params = array(), $doNotTestCacheValidity = false)
    {
        if (!empty($params)) {
          $id .= '_' . md5(var_export($params, true));
        }
        $data = $this->load($id, $doNotTestCacheValidity);
        $this->_idStack[] = $id;
        if ($data !== false) {
          return $data;
        }
        return false;
    }

    /**
     * Stop the cache
     *
     * @param  mixed   $data
     * @param  array   $tags             Tags array
     * @param  int     $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @param  int     $priority         integer between 0 (very low priority) and 10 (maximum priority) used by some particular backends
     * @return void
     */
    public function end($data, $tags = array(), $specificLifetime = false, $priority = 8)
    {
        $id = array_pop($this->_idStack);
        if ($id === null) {
            Zend_Cache::throwException('use of end() without a start()');
        }
        $this->save($data, $id, $tags, $specificLifetime, $priority);
        return $data;
    }
}
