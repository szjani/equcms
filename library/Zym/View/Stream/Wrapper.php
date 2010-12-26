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
 * @subpackage Stream
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * View stream wrapper component
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_View
 * @subpackage Stream
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Stream_Wrapper
{
    /**
     * View object
     *
     * @var Zym_View_Abstract
     */
    protected static $_view;

    /**
     * Current stream position.
     *
     * @var integer
     */
    protected $_pos = 0;

    /**
     * Data for streaming.
     *
     * @var string
     */
    protected $_data;

    /**
     * Stream stats.
     *
     * @var array
     */
    protected $_stat = array();

    /**
     * File handle
     *
     * @var resource
     */
    protected $_fileHandle;

    /**
     * File path
     *
     * @var string
     */
    protected $_path;

    /**
     * Open
     *
     * @param string $path
     * @param string $mode
     * @param int $options
     * @param string $opened_path
     * @return boolean
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        // Get host part from path (scheme://host)
        preg_match('#(?:[\w\d]+://)?(.+)#', $path, $matches);
        $this->_path = $matches[1];

        // Search include path for relative urls?
        $useIncludePath = $this->_checkFlag($options, STREAM_USE_PATH);

        // Trigger errors?
        $triggerErrors  = $this->_checkFlag($options, STREAM_REPORT_ERRORS);

        // Get contents
        $this->_fileHandle = $triggerErrors ? fopen($this->_path, $mode, $useIncludePath)
                                            : @fopen($this->_path, $mode, $useIncludePath);

        // If reading the file failed, update our local stat store
        // to reflect the real stat of the file, then return on failure
        if ($this->_fileHandle === false) {
            return false;
        } else {
            $this->_data = fread($this->_fileHandle, filesize($this->_path));
        }

        // Path opened
        if ($useIncludePath) {
            $opened_path = $this->_path;
        }

        // Process data
        $this->_data = $this->_filter($this->_data);

        // file_get_contents() won't update PHP's stat cache, so performing
        // another stat() on it will hit the filesystem again.  Since the file
        // has been successfully read, avoid this and just fake the stat
        // so include() is happy.
        $this->_stat = array(
            'mode' => 0100777,
            'size' => strlen($this->_data)
        );

        return true;
    }

    /**
     * Close file handle
     *
     */
    public function stream_close()
    {
        fclose($this->_fileHandle);
    }

    /**
     * Read
     *
     * @param integer $count
     * @return string
     */
    public function stream_read($count)
    {
        $return = substr($this->_data, $this->_pos, $count);
        $this->_pos += strlen($return);

        return $return;
    }

    /**
     * Write
     *
     * @param string $data
     * @return integer
     */
    public function stream_write($data)
    {
        return fwrite($this->_fileHandle, $data);
    }

    /**
     * End of stream indicator
     *
     * @return boolean
     */
    public function stream_eof()
    {
        $isEof = ($this->_pos >= strlen($this->_data));
        return $isEof;
    }

    /**
     * Current position
     *
     * @return integer
     */
    public function stream_tell()
    {
        return $this->_pos;
    }

    /**
     * Seek in stream
     *
     * @return boolean
     */
    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->_data) && $offset >= 0) {
                    $this->_pos = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->_pos += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen($this->_data) + $offset >= 0) {
                    $this->_pos = strlen($this->_data) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

    /**
     * Flush
     *
     * @return boolean
     */
    public function stream_flush()
    {
        return fflush($this->_fileHandle);
    }

    /**
     * Stream statistics
     *
     * @return array
     */
    public function stream_stat()
    {
        return $this->_stat;
    }

    /**
     * Stat url
     *
     * @param string $path
     * @param string $flags
     * @return array
     */
    public function url_stat($path, $flags = null)
    {
        // Get host part
        preg_match('#(?:[\w\d]+://)?(.+)#', $path, $matches);
        $host = $matches[1];

        // Trigger errors?
        $noErrors = $this->_checkFlag($flags, STREAM_URL_STAT_QUIET);

        // Stat symlinks
        if ($this->_checkFlag($flags, STREAM_URL_STAT_LINK)) {
            return $noErrors ? @lstat($host) : lstat($host);
        }

        return $noErrors ? @stat($host) : stat($host);
    }

    /**
     * Set View
     *
     * @param Zym_View_Abstract $view
     */
    public static function setView(Zym_View_Abstract $view)
    {
        self::$_view = $view;
    }

    /**
     * Get view
     *
     * @return Zym_View_Abstract
     */
    public static function getView()
    {
        return self::$_view;
    }


    /**
     * Applies the filter callback to a buffer.
     *
     * @param string $buffer The buffer contents.
     * @return string The filtered buffer.
     */
    protected function _filter($buffer)
    {
        $view = self::getView();
        $streamFilters = $view->getStreamFilters();

        // Loop through each filter class
        foreach ($streamFilters as $name) {
            // Load and apply the filter class
            $filter = $view->getFilter($name);
            $buffer = call_user_func(array($filter, 'filter'), $buffer);
        }

        // Done!
        return $buffer;
    }

    /**
     * Validate bitwise flags
     *
     * @param integer $values
     * @param integer $flag
     * @return boolean
     */
    protected function _checkFlag($values, $flag)
    {
         $flag = (int) $flag;
         $values = (int) $values;
         return (($values & $flag) == $flag);
    }
}