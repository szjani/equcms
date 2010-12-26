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
 * @package Zym_PhpUnit
 * @subpackage Framework
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license http://www.zym-project.com/license New BSD License
 */

/**
 * @see PHPUnit_Framework_TestSuite
 */
require_once 'PHPUnit/Framework/TestSuite.php';

/**
 * Zym testsuite
 *
 * @author Geoffrey Tran
 * @license http://www.zym-project.com/license New BSD License
 * @category Zym
 * @package Zym_PhpUnit
 * @subpackage Framework
 * @copyright Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_PhpUnit_Framework_TestSuite extends PHPUnit_Framework_TestSuite 
{
    /**
     * Construct
     *
     * @return void
     */
    public function __construct($currentFile)
    {
        $this->setName($this->_createName());
        
        $name  = substr($this->getName(), 0, -5);
        $path  = dirname($currentFile);
        $tests = array();
        
        $componentFile = $path . "/{$name}Test.php";
        if (file_exists($componentFile)) {
            $tests[] = $componentFile;
        }

        $componentDir = $path . '/' . $name;
        if (file_exists($componentDir)) {
            $iterator = new RecursiveDirectoryIterator($componentDir);
            foreach(new RecursiveIteratorIterator($iterator) as $file) {
                if ($file->isFile() && substr($file, -8) == 'Test.php') {
                    $tests[] = (string) $file;
                }
            }
        }
        
        $this->addTestFiles($tests);
    }
    
    /**
     * Get suite
     *
     * @return Zym_PhpUnit_Framework_TestSuite
     */
    public static function suite()
    {
        return new self(__FILE__);
    }
    
    /**
     * Create name
     *
     * @return string
     */
    protected function _createName()
    {
        $parts = explode('_', get_class($this));
        $name  = $parts[count($parts) - 1];
                
        return $name;
    }
}