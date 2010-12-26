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
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_Timer
 */
require_once 'Zym/Timer.php';

/**
 * Timer manager component
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_Timer
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Timer_Manager implements Countable
{
    /**
     * Timer instances
     *
     * @var array
     */
    private $_timers = array();

    /**
     * Get a timer instance
     *
     * If one does not exist, create it
     *
     * @param string $name
     * @param string $group
     * @return Zym_Timer
     */
    public function getTimer($name, $group = null)
    {
        if (!$this->hasTimer($name, $group)) {
            $this->createTimer($name, $group);
        }

        return $this->_timers[$group][$name];
    }

    /**
     * Create a timer instance and add it to the manager
     *
     * @param string $name
     * @param string $group
     * @return Zym_Timer
     */
    public function createTimer($name, $group = null)
    {
        $timer = new Zym_Timer();
        $this->addTimer($name, $timer, $group);

        return $timer;
    }

    /**
     * Add a timer instance
     *
     * @throws Zym_Timer_Exception if one already exists
     *
     * @param string          $name
     * @param Zym_Timer_Timer $timer
     * @param string          $group
     *
     * @return Zym_Timer_Manager
     */
    public function addTimer($name, Zym_Timer $timer, $group = null)
    {
        if ($this->hasTimer($name, $group)) {
            /**
             * @see Zym_Timer_Manager_Exception
             */
            require_once 'Zym/Timer/Manager/Exception.php';
            throw new Zym_Timer_Manager_Exception(sprintf(
                'Cannot add timer because timer exists in group "%s" named "%s"',
            $name, $group));
        }

        // Set timer
        $this->_timers[$group][$name] = $timer;

        return $this;
    }

    /**
     * Set a timer replacing any existing timer
     *
     * @param string    $name
     * @param Zym_Timer $timer
     * @param string    $group
     *
     * @return Zym_Timer_Manager
     */
    public function setTimer($name, Zym_Timer $timer, $group = null)
    {
        $this->_timers[$group][$name] = $timer;

        return $this;
    }

    /**
     * Check if timer exists
     *
     * @param string $name
     * @param string $group
     * @return boolean
     */
    public function hasTimer($name, $group = null)
    {
        return isset($this->_timers[$group][$name]);
    }

    /**
     * Get all timer instances
     *
     * @return array
     */
    public function getTimers()
    {
        return $this->_timers;
    }

    /**
     * Get runtime of all registered timers
     *
     * @return integer
     */
    public function getRun()
    {
        $runTime = 0;

        foreach ($this->getTimers() as $timers) {
            foreach ($timers as $timer) {
               $runTime += $timer->getRun();
            }
        }

        return $runTime;
    }

    /**
     * Get runtime of a group of timers
     *
     * @param string $group
     * @return integer
     */
    public function getGroupRun($group = null)
    {
        $runTime = 0;
        $timers  = $this->getTimers();

        if (!isset($timers[$group])) {
            return $runTime;
        }

        foreach ($timers[$group] as $timer) {
            $runTime += $timer->getRun();
        }

        return $runTime;
    }

    /**
     * Clear all timer instances
     *
     * @return Zym_Timer_Manager
     */
    public function clearTimers()
    {
        // Clear timers
        $this->_timers = array();

        return $this;
    }

    /**
     * Get timer instances count
     *
     * @return integer
     */
    public function count()
    {
        $count = 0;

        foreach ($this->getTimers() as $timers) {
        	$count += count($timers);
        }

        return $count;
    }
}