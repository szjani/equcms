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
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */

/**
 * @see Zym_View_Helper_NavigationAbstract
 */
require_once 'Zym/View/Helper/NavigationAbstract.php';

/**
 * Helper for printing sitemaps
 * 
 * @link http://www.sitemaps.org/protocol.php
 *
 * @category   Zym
 * @package    Zym_View
 * @subpackage Helper
 * @author     Robin Skoglund
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 * @license    http://www.zym-project.com/license    New BSD License
 */ 
class Zym_View_Helper_Sitemap extends Zym_View_Helper_NavigationAbstract
{
    /**
     * Namespace for the <urlset> tag
     *
     * @var string
     */
    const SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    
    /**
     * Schema URL
     *
     * @var string
     */
    const SITEMAP_XSD = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';
    
    /**
     * Sets maximum depth sitemap should be traversed
     *
     * @var int
     */
    protected $_maxDepth = null;
    
    /**
     * Whether XML output should be formatted
     *
     * @var bool
     */
    protected $_formatOutput = false;
    
    /**
     * Whether the XML declaration should be included in XML output
     *
     * @var bool
     */
    protected $_useXmlDeclaration = true;
    
    /**
     * Whether sitemap should be schema validated when generated
     *
     * @var bool
     */
    protected $_useSchemaValidation = false;
    
    /**
     * Whether sitemap should be validated using Zym_Validate_Sitemap_*
     *
     * @var bool
     */
    protected $_useSitemapValidators = true;
    
    /**
     * Server url
     *
     * @var string
     */
    protected $_serverUrl;
    
    /**
     * Retrieves helper and optionally sets container to operate on
     * 
     * The options array is an optional associative array of options to set.
     * Each key in the array corresponds to the according set*() method, and
     * each word is separated by underscores, e.g. the option 'format_output'
     * corresponds to setFormatOutput().
     * 
     * @param  Zym_Navigation_Container $container  [optional] container to
     *                                              operate on
     * @param  array                    $options    [optional] associative
     *                                              array of options to set
     * @return Zym_View_Helper_Sitemap
     */
    public function sitemap(Zym_Navigation_Container $container = null,
                            array $options = array())
    {
        if (null !== $container) {
            $this->setNavigation($container);
        }
        
        // set options
        foreach ($options as $option => $value) {
            if (is_string($key) && !empty($key)) {
                $method = 'set' . str_replace(' ', '',
                                    ucfirst(str_replace('_', ' ', $key)));
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }   
            }
            
        }
        
        return $this;
    }
    
    /**
     * Sets maximum depth sitemap should be traversed
     *
     * @param  int $maxDepth  [optional] maximum level (depth) to traverse,
     *                        defaults to null, which will set no maximum
     * @return void
     */
    public function setMaxDepth($maxDepth = null)
    {
        if (null === $maxDepth) {
            $this->_maxDepth = $maxDepth;
        } else {
            $this->_maxDepth = (int)$maxDepth;
        }
    }
    
    /**
     * Returns maximum depth sitemap should be traversed
     *
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->_maxDepth;
    }
    
    /**
     * Sets whether XML output should be formatted
     *
     * @param bool $formatOutput  format XML output
     */
    public function setFormatOutput($formatOutput)
    {
        $this->_formatOutput = (bool)$formatOutput;
    }
    
    /**
     * Returns true if XML output should be formatted 
     *
     * @return bool
     */
    public function getFormatOutput()
    {
        return $this->_formatOutput;
    }
    
    /**
     * Sets whether the XML declaration should be used in output
     *
     * @param bool $useXmlDecl  whether XML delcaration should be used
     */
    public function setUseXmlDeclaration($useXmlDecl)
    {
        $this->_useXmlDeclaration = (bool) $useXmlDecl;
    }
    
    /**
     * Returns true if the XML declaration should be used in output 
     *
     * @return bool
     */
    public function getUseXmlDeclaration()
    {
        return $this->_useXmlDeclaration;
    }
    
    /**
     * Sets whether sitemap should be validated using Zym_Validate_Sitemap_*
     *
     * @param bool $useSitemapValidators  whether to use sitemap validators
     */
    public function setUseSitemapValidators($useSitemapValidators)
    {
        $this->_useSitemapValidators = (bool)$useSitemapValidators;
    }
    
    /**
     * Returns true if sitemap should be validated using Zym_Validate_Sitemap_*
     *
     * @return bool
     */
    public function getUseSitemapValidators()
    {
        return $this->_useSitemapValidators;
    }
    
    /**
     * Sets whether sitemap should be schema validated when generated
     *
     * @param bool $schemaValidation  whether sitemap should be schema validated
     */
    public function setUseSchemaValidation($schemaValidation)
    {
        $this->_useSchemaValidation = (bool)$schemaValidation;
    }
    
    /**
     * Returns true if sitemap should be schema validated when generated
     *
     * @return bool
     */
    public function getUseSchemaValidation()
    {
        return $this->_useSchemaValidation;
    }
    
    /**
     * Sets server url (scheme and host-related stuff without request URI)
     * 
     * E.g. http://www.example.com
     *
     * @param  string $serverUrl  server URL to set (only scheme and host)
     * @throws Zend_Uri_Exception  if invalid server url
     */
    public function setServerUrl($serverUrl)
    {
        $uri = explode(':', $serverUrl, 2);
        $scheme = strtolower($uri[0]);
        
        switch ($scheme) {
            case 'http':
            case 'https':
                break;
            default:
                require_once 'Zend/Uri/Exception.php';
                throw new Zend_Uri_Exception("Invalid scheme: '$scheme'");
        }

        // TODO: rewrite to use Zend_Uri_Http directly when possible
        require_once 'Zend/Uri.php';
        $uri = Zend_Uri::factory($serverUrl);
        $uri->setFragment('');
        $uri->setPath('');
        $uri->setQuery('');
        
        if ($uri->valid()) {
            $this->_serverUrl = $uri->getUri();
        } else {
            require_once 'Zend/Uri/Exception.php';
            throw new Zend_Uri_Exception("Invalid URI: '$serverUrl'");
        }
    }
    
    /**
     * Returns server URL
     *
     * @return string
     */
    protected function _getServerUrl()
    {
        if (!isset($this->_serverUrl)) {
            $this->_serverUrl = $this->getView()->serverUrl();
        }
        
        return $this->_serverUrl;
    }
    
    /**
     * Escapes string for XML usage
     *
     * @param string $string
     */
    protected function _xmlEscape($string)
    {
        // Do not encode existing HTML entities
        // From PHP 5.2.3 this functionality is built-in, otherwise use a regex
        if (version_compare(PHP_VERSION, '5.2.3', '>=')) {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
        } else {
            $string = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $string);
            $string = str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#39;', '&quot;'), $string);
            return $string;
        }
    }
    
    /**
     * Returns an absolute URL for the given page
     *
     * @param  Zym_Navigation_Page $page  page to get URL from
     * @return string
     */
    protected function _getUrl(Zym_Navigation_Page $page)
    {
        $href = $page->getHref();
        
        if (!isset($href{0})) {
            return '';
        } elseif ($href{0} == '/') {
            $url = $this->_getServerUrl() . $href;
        } elseif (@preg_match('/^https?:\\/\//m', $href)) {
            $url = $href;
        } else {
            $url = $this->_getServerUrl()
                 . rtrim($this->getView()->url(), '/') . '/'
                 . $href;
            //exit("got url '$url'\n");
        }

        return $this->_xmlEscape($url);
    }
    
    /**
     * Returns a DOMDocument containing the Sitemap XML for the given container
     *
     * @param  Zym_Navigation_Container $container  [optional] container to get
     *                                              breadcrumbs from, defaults
     *                                              to what is registered in the
     *                                              helper
     * @return DOMDocument
     * @throws DomainException  if schema validation is on and the sitemap
     *                          is invalid according to the sitemap schema, or
     *                          of sitemap validators are used and the loc
     *                          element fails validation
     */
    public function getDomSitemap(Zym_Navigation_Container $container = null)
    {
        if (null === $container) {
            $container = $this->getNavigation();
        }
        
        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($this->_maxDepth)) {
            $iterator->setMaxDepth($this->_maxDepth);
        }
        
        // check if we should validate using our own validators
        if ($this->getUseSitemapValidators()) {
            require_once 'Zym/Validate/Sitemap/Changefreq.php';
            require_once 'Zym/Validate/Sitemap/Lastmod.php';
            require_once 'Zym/Validate/Sitemap/Loc.php';
            require_once 'Zym/Validate/Sitemap/Priority.php';
            
            // create validators
            $locValidator        = new Zym_Validate_Sitemap_Loc();
            $lastmodValidator    = new Zym_Validate_Sitemap_Lastmod();
            $changefreqValidator = new Zym_Validate_Sitemap_Changefreq();
            $priorityValidator   = new Zym_Validate_Sitemap_Priority();    
        }
        
        // create document
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = $this->_formatOutput;
        
        // ...and urlset (root) element
        $urlSet = $dom->createElementNS(self::SITEMAP_NS, 'urlset');
        $dom->appendChild($urlSet);
        
        // iterate navigation
        foreach ($iterator as $page) {
            if (!$this->_accept($page)) {
                // page is not accepted
                continue;
            }
            
            // get absolute url from page
            if (!$url = $this->_getUrl($page)) {
                // skip page if it has no url (rare case)
                continue;
            }
            
            // create url node for this page
            $urlNode = $dom->createElementNS(self::SITEMAP_NS, 'url');
            $urlSet->appendChild($urlNode);
          
            if ($this->getUseSitemapValidators() &&
                !$locValidator->isValid($url)) {
                $msg = "Invalid sitemap URL: '$url'";
                throw new DomainException($msg);
            }
            
            // put url in 'loc' element
            $urlNode->appendChild($dom->createElementNS(self::SITEMAP_NS,
                                                        'loc', $url));
            
            // add 'lastmod' element if a valid lastmod is set in page
            if (isset($page->lastmod)) {
                $lastmod = strtotime((string)$page->lastmod);
                
                // prevent 1970-01-01...
                if ($lastmod !== false) {
                    $lastmod = date('c', $lastmod);
                }
                
                if (!$this->getUseSitemapValidators() ||
                    $lastmodValidator->isValid($lastmod)) {
                    $urlNode->appendChild(
                        $dom->createElementNS(self::SITEMAP_NS, 'lastmod',
                                              $lastmod)
                    );
                }
            }
            
            // add 'changefreq' element if a valid changefreq is set in page
            if (isset($page->changefreq)) {
                $changefreq = $page->changefreq;
                if (!$this->getUseSitemapValidators() ||
                    $changefreqValidator->isValid($changefreq)) {
                    $urlNode->appendChild(
                        $dom->createElementNS(self::SITEMAP_NS, 'changefreq',
                                              $changefreq)
                    );
                }
            }
            
            // add 'priority' element if a valid priority is set in page
            if (isset($page->priority)) {
                $priority = $page->priority;
                if (!$this->getUseSitemapValidators() ||
                    $priorityValidator->isValid($priority)) {
                    $urlNode->appendChild(
                        $dom->createElementNS(self::SITEMAP_NS, 'priority',
                                              $priority)
                    );
                }
            }
        }
        
        // validate using schema if specified
        if ($this->getUseSchemaValidation()) {
            if (!@$dom->schemaValidate(self::SITEMAP_XSD)) {
                $msg = 'Sitemap is invalid according to ' . self::SITEMAP_XSD;
                throw new DomainException($msg);
            }
        }
        
        return $dom;
    }
    
    /**
     * Renders a sitemap for a navigation container
     *
     * @param  Zym_Navigation_Container $container  [optional] container to get
     *                                              sitemap from, defaults
     *                                              to what is registered in the
     *                                              helper
     * @return string
     */
    public function renderSitemap(Zym_Navigation_Container $container = null)
    {
        $dom = $this->getDomSitemap($container);
        return $this->_useXmlDeclaration ?
               $dom->saveXML() :
               $dom->saveXML($dom->documentElement) . "\n";
    }
    
    /**
     * Renders the registered container as an XML Sitemap
     * 
     * @param  string|int $indent  [optional] this is ignored
     * @return string
     */
    public function toString($indent = null)
    {
        return $this->renderSitemap(null);
    }
    
    /**
     * Tostring
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}