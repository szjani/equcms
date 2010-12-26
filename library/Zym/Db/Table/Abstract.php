<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @author     Jurrien Stutterheim
 * @category   Zym
 * @package    Zym_Db
 * @subpackage Table
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license New BSD License
 */

/**
 * @see Zend_Db_Table_Abstract
 */
require_once 'Zend/Db/Table/Abstract.php';

/**
 * @author     Jurrien Stutterheim
 * @license    http://www.zym-project.com/license New BSD License
 * @category   Zym
 * @package    Zym_Db
 * @subpackage Table
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Zym_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    /**
     * Check if the column is set as identity
     *
     * @param string $column
     * @return boolean
     */
    public function isIdentity($column)
    {
        if (!array_key_exists($column, $this->_metadata)) {
            /**
             * @see Zym_Db_Table_Exception
             */
            require_once 'Zym/Db/Table/Exception.php';

            $message = sprintf('Specified column "%s" is not in the table', $column);

            throw new Zym_Db_Table_Exception($message);
        }

        return (bool) $this->_metadata[$column]['IDENTITY'];
    }

    /**
     * Add a reference to the reference map
     *
     * @param string $ruleKey
     * @param string|array $columns
     * @param string $refTableClass
     * @param string|array $refColumns
     * @param string $onDelete
     * @param string $onUpdate
     * @return Zend_Db_Table_Abstract
     */
    public function addReference($ruleKey, $columns, $refTableClass, $refColumns, $onDelete = null, $onUpdate = null)
    {
        $reference = array(self::COLUMNS         => $columns,
                           self::REF_TABLE_CLASS => $refTableClass,
                           self::REF_COLUMNS     => $refColumns);

        if (!empty($onDelete)) {
            $reference[self::ON_DELETE] = $onDelete;
        }

        if (!empty($onUpdate)) {
            $reference[self::ON_UPDATE] = $onUpdate;
        }

        $this->_referenceMap[$ruleKey] = $reference;

        return $this;
    }
}