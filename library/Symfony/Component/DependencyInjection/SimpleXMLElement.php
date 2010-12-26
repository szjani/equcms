<?php

namespace Symfony\Component\DependencyInjection;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * SimpleXMLElement class.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class SimpleXMLElement extends \SimpleXMLElement
{
    public function getAttributeAsPhp($name)
    {
        return self::phpize($this[$name]);
    }

    public function phpizeConstants(SimpleXMLElement $xml)
    {
      if (!isset($xml->const)) {
        return $xml;
      }

      /* @var $dom DOMElement */
      $dom = dom_import_simplexml($xml);
      // Doing this the hard-way as foreach over a DOMNodeList won't work
      for ($i = 0, $length = $dom->childNodes->length; $i < $length; $i++) {
        $node = $dom->childNodes->item($i);
        // Is this a 'const' node?
        if (0 !== strcasecmp($node->localName, 'const')) {
          // Node, skip to next
          continue;
        }
        // Yes, process it

        $constantName = $node->getAttribute('name');
        if (!defined($constantName)) {
          $msg = 'Constant with name \'%s\' was not defined';
          $msg = sprintf($msg, $constantName);
          throw new DOMException($msg);
        }
        $constantValue = constant($constantName);

        // Create text node with the constantvalue as text
        $newNode = $dom->ownerDocument->createTextNode($constantValue);
        // Replace const-node with text-node containing the constantValue as text
        $dom->replaceChild($newNode, $node);
      }
      $xml = simplexml_import_dom($dom, __CLASS__);
      return $xml;
    }

    public function getArgumentsAsPhp($name)
    {
        $arguments = array();
        foreach ($this->$name as $arg) {
            $arg = $this->phpizeConstants($arg);
            $key = isset($arg['key']) ? (string) $arg['key'] : (!$arguments ? 0 : max(array_keys($arguments)) + 1);

            // parameter keys are case insensitive
            if ('parameter' == $name) {
                $key = strtolower($key);
            }

            switch ($arg['type']) {
                case 'service':
                    $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
                    if (isset($arg['on-invalid']) && 'ignore' == $arg['on-invalid']) {
                        $invalidBehavior = ContainerInterface::IGNORE_ON_INVALID_REFERENCE;
                    } elseif (isset($arg['on-invalid']) && 'null' == $arg['on-invalid']) {
                        $invalidBehavior = ContainerInterface::NULL_ON_INVALID_REFERENCE;
                    }
                    $arguments[$key] = new Reference((string) $arg['id'], $invalidBehavior);
                    break;
                case 'collection':
                    $arguments[$key] = $arg->getArgumentsAsPhp($name);
                    break;
                case 'string':
                    $arguments[$key] = (string) $arg;
                    break;
                case 'constant':
                    $arguments[$key] = constant((string) $arg);
                    break;
                default:
                    $arguments[$key] = self::phpize($arg);
            }
        }

        return $arguments;
    }

    static public function phpize($value)
    {
        $value = (string) $value;
        $lowercaseValue = strtolower($value);

        switch (true) {
            case 'null' === $lowercaseValue:
                return null;
            case ctype_digit($value):
                return '0' == $value[0] ? octdec($value) : intval($value);
            case 'true' === $lowercaseValue:
                return true;
            case 'false' === $lowercaseValue:
                return false;
            case is_numeric($value):
                return '0x' == $value[0].$value[1] ? hexdec($value) : floatval($value);
            case preg_match('/^(-|\+)?[0-9,]+(\.[0-9]+)?$/', $value):
                return floatval(str_replace(',', '', $value));
            default:
                return $value;
        }
    }
}
