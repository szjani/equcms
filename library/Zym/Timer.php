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
 * @package Zym_Timer
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * Timer component
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Timer
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Timer
{
    /**
     * Start time
     *
     * @var integer
     */
    private $_start;

    /**
     * Total time
     *
     * @var array
     */
    private $_totalTime = array();

    /**
     * Start the timer
     *
     */
    public function start()
    {
        $this->_start = microtime(true);
    }

    /**
     * Stop the timer
     *
     * @return integer Time elapsed for this run
     */
    public function stop()
    {
        if ($this->_start == false) {
            /**
             * @see Zym_Timer_Exception
             */
            require_once 'Zym/Timer/Exception.php';
            throw new Zym_Timer_Exception('Timer already stopped');
        }

        $spentTime          = microtime(true) - $this->_start;
        $this->_totalTime[] = $spentTime;
        $this->_start       = null;

        return $spentTime;
    }

    /**
     * Amount of times this timer was started
     *
     * @return integer
     */
    public function getCalls()
    {
        $calls = count($this->_totalTime);
        if ($this->_start !== null) {
            $calls++;
        }

        return $calls;
    }

    /**
     * Time elapsed (including time if currently running)
     *
     * @return integer
     */
    public function getElapsed()
    {
        $elapsedTime = array_sum($this->_totalTime);

        // No elapsed time or currently running? take/add current running time
        if ($this->_start !== null) {
            $elapsedTime += microtime(true) - $this->_start;
        }

        return $elapsedTime;
    }

    /**
     * Get elapsed average of all starts and stops (including w/ stopping)
     *
     * @return integer
     */
    public function getElapsedAverage()
    {
        $calls = $this->getCalls();
        if ($calls == 0) {
            // @todo do we throw an exception or return 0?
            /**
             * @see Zym_Timer_Exception
             */
            require_once 'Zym/Timer/Exception.php';
            throw new Zym_Timer_Exception(
                'Cannot get average time because timer has not been started'
            );
        }

        $averageTime = $this->getElapsed() / $calls;
        return $averageTime;
    }

    /**
     * Get runtimes of complete start and stop's
     *
     * @return integer
     */
    public function getRun()
    {
        return array_sum($this->_totalTime);
    }

    /**
     * Get runtime average
     *
     * @return integer
     */
    public function getRunAverage()
    {
        $calls = $this->getCalls();
        if ($calls == 0) {
            // @todo do we throw an exception or return 0?
            /**
             * @see Zym_Timer_Exception
             */
            require_once 'Zym/Timer/Exception.php';
            throw new Zym_Timer_Exception(
                'Cannot get average time because timer has not been started'
            );
        }

        $averageTime = $this->getRun() / $calls;
        return $averageTime;
    }

    /**
     * Get runtimes of complete start and stop's
     *
     * @return array
     */
    public function getRunAsArray()
    {
        return $this->_totalTime;
    }

    /**
     * Whether the timer is running
     *
     * @return boolean
     */
    public function isStarted()
    {
        return (bool) $this->_start;
    }

    /**
     * Reset object
     *
     * @return Zym_Timer
     */
    public function reset()
    {
        $this->_start     = null;
        $this->_totalTime = array();

        return $this;
    }
}