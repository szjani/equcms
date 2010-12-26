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
 * @package    Zym_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * @see Zend_Locale
 */
require_once 'Zend/Locale.php';

/**
 * Helper for Zend_Translate
 *
 * @category   Zym
 * @package    Zym_Controller
 * @subpackage Action_Helper
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */
class Zym_Controller_Action_Helper_Translator
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Translator object
     *
     * @var Zend_Translate_Adapter
     */
    protected $_translator;

    /**
     * Creates the action helper, optionally setting translator to use
     *
     * @param Zend_Translate|Zend_Translate_Adapter $translator instance to set
     */
    public function __construct($translator = null)
    {
        if (empty($translator) === false) {
            $this->setTranslator($translator);
        }
    }

    /**
     * Translates a message
     *
     * The method accepts multiple params or an array of params.
     *
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageid id of the message to translate
     * @return string translated message
     */
    public function translate($messageid = null)
    {
        if ($messageid === null) {
            return $this;
        }

        $translator = $this->getTranslator();
        if ($translator === null) {
            return $messageid;
        }

        $options = func_get_args();
        array_shift($options);

        $count  = count($options);
        $locale = null;
        if ($count > 0) {
            if (Zend_Locale::isLocale($options[($count - 1)]) !== false) {
                $locale = array_pop($options);
            }
        }

        if ((count($options) === 1) and (is_array($options[0]) === true)) {
            $options = $options[0];
        }

        $message = $translator->translate($messageid, $locale);
        if ($count === 0) {
            return $message;
        }

        return vsprintf($message, $options);
    }

    /**
     * Translates a message (shorthand)
     *
     * The method accepts multiple params or an array of params.
     *
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageid id of the message to translate
     * @return string translated message
     */
    public function _($messageid = null)
    {
        $args = func_get_args();

        return call_user_func_array(array($this, 'translate'), $args);
    }

    /**
     * Sets a translation Adapter for translation
     *
     * @param  Zend_Translate|Zend_Translate_Adapter $translator  instance
     * @throws Zym_Controller_Action_Helper_Exception  on invalid $translator
     * @return Zym_Controller_Action_Helper_Translator
     */
    public function setTranslator($translator)
    {
        if ($translator instanceof Zend_Translate_Adapter) {
            $this->_translator = $translator;
        } else if ($translator instanceof Zend_Translate) {
            $this->_translator = $translator->getAdapter();
        } else {
            /**
             * @see Zym_Controller_Action_Helper_Exception
             */
            require_once 'Zym/Controller/Action/Helper/Exception.php';
            $msg = '$translator must be an instance of Zend_Translate or Zend_Translate_Adapter';
            throw new Zym_Controller_Action_Helper_Exception($msg);
        }

        return $this;
    }

    /**
     * Retrieve translation object
     *
     * If none is currently registered, attempts to pull it from the registry
     * using the key 'Zend_Translate'.
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
        if ($this->_translator === null) {
            /**
             * @see Zend_Registry
             */
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $translator = Zend_Registry::get('Zend_Translate');

                if ($translator instanceof Zend_Translate ||
                    $translator instanceof Zend_Translate_Adapter) {
                    $this->setTranslator($translator);
                }
            }
        }

        return $this->_translator;
    }

    /**
     * Set's an new locale for all further translations
     *
     * @param  string|Zend_Locale $locale New locale to set
     * @throws Zym_Controller_Action_Helper_Exception  if helper has no translator
     * @return Zym_Controller_Action_Helper_Translator
     */
    public function setLocale($locale = null)
    {
        $translator = $this->getTranslator();
        if ($translator === null) {
            /**
             * @see Zym_Controller_Action_Helper_Exception
             */
            require_once 'Zym/Controller/Action/Helper/Exception.php';
            $msg = 'You must set an instance of Zend_Translate or Zend_Translate_Adapter';
            throw new Zym_Controller_Action_Helper_Exception($msg);
        }

        $translator->setLocale($locale);
        return $this;
    }

    /**
     * Returns the set locale for translations
     *
     * @throws Zym_Controller_Action_Helper_Exception  if helper has no translator
     * @return string|Zend_Locale
     */
    public function getLocale()
    {
        $translator = $this->getTranslator();
        if ($translator === null) {
            /**
             * @see Zym_Controller_Action_Helper_Exception
             */
            require_once 'Zym/Controller/Action/Helper/Exception.php';
            $msg = 'You must set an instance of Zend_Translate or Zend_Translate_Adapter';
            throw new Zym_Controller_Action_Helper_Exception($msg);
        }

        return $translator->getLocale();
    }

    /**
     * Strategy pattern: Return instance or call translate()
     *
     * The method accepts multiple params or an array of params.
     *
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageId  [optional] message id to translate, or null
     *                                       to return the action helper
     * @return Zym_Controller_Action_Helper_Translator|string  instance or
     *                                                         translated string
     */
    public function direct($messageId = null)
    {
        if ($messageId === null) {
            return $this;
        }

        $args = func_get_args();
        return call_user_func_array(array($this, 'translate'), $args);
    }
}
