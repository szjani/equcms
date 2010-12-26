<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Zym
 * @package    Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zym_View_Helper_Abstract
 */
require_once 'Zym/View/Helper/Abstract.php';

/**
 * Formats a date as the time since that date (e.g., “4 weeks”).
 *
 * This is useful for creating "Last updated 5 week and 4 days ago" strings
 *
 * @author     Geoffrey Tran
 * @license    http://www.zym-project.com/license New BSD License
 * @package    Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_View_Helper_TimeSince extends Zym_View_Helper_Abstract
{
    /**
     * Time chunks in seconds => string format
     *
     * Order is FIFO largest to smallest
     *
     * @var array
     */
    protected $_dateChucks = array(
        31536000 => 'year',
        2592000  => 'month',
        604800   => 'week',
        86400    => 'day',
        3600     => 'hour',
        60       => 'minute',
        1        => 'second'
    );

    /**
     * Formats a date as the time since that date (e.g., “4 weeks ago”).
     *
     * @param integer $timestamp
     * @param integer $time      Timestamp to use instead of time()
     */
    public function timeSince($timestamp, $time = null)
    {
        $output     = '';
        $translator = $this->getView()->getHelper('translate');

        if ($time === null) {
            $time = time();
        }

        // Seconds since
        $since = $time - $timestamp;

        foreach ($this->_dateChucks as $seconds => $name) {
            if (!isset($largestChunk)) {
                $ratio = $since / $seconds;
                $chunk = ($since < 0) ? -floor(abs($ratio)) : floor($ratio);
            }

            // Compute chunks
            if (isset($chunk) && $chunk != 0  && !isset($largestChunk)) {
                $largestChunk        = $chunk;
                $largestChunkName    = ($chunk == 1) ? $name : $name . 's';
                $largestChunkSeconds = $seconds;
            } else if (isset($chunk) && $chunk == 0 && !isset($largestChunk)) {
                // Handle if it 0 seconds
                $output = $translator->translate('less than a second');
            } else if (isset($largestChunk)) {
                $ratio = ($since - ($largestChunkSeconds * $largestChunk)) / $seconds;
                $chunk = ($since < 0) ? -floor(abs($ratio)) : floor($ratio);

                if ($chunk != 0) {
                    $secondChunk     = $chunk;
                    $secondChunkName = ($chunk == 1) ? $name : $name . 's';
                }

                break;
            }
        }

        if ($translator->getTranslator() === null) {
            if (isset($secondChunk)) {
                $output = sprintf("%d $largestChunkName and %d $secondChunkName", $largestChunk, $secondChunk);
            } else if (isset($largestChunk)) {
                $output = sprintf("%d $largestChunkName", $largestChunk);
            }
        } else {
            if (isset($secondChunk)) {
                $output = $translator->translate("%d $largestChunkName and %d $secondChunkName", $largestChunk, $secondChunk);
            } else if (isset($largestChunk)) {
                $output = $translator->translate("%d $largestChunkName", $largestChunk);
            }
        }

        return $output;
    }
}