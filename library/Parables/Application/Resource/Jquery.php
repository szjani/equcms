<?php
class Parables_Application_Resource_Jquery 
    extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var ZendX_JQuery_View_Helper_JQuery_Container
     */
    protected $_jquery;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return  ZendX_JQuery_View_Helper_JQuery_Container
     */
    public function init()
    {
        return $this->getJquery();
    }

    /**
     * Retrieve JQuery view helper
     *
     * @return ZendX_JQuery_View_Helper_JQuery_Container
     */
    public function getJquery()
    {
        if (null === $this->_jquery) {
            $this->getBootstrap()->bootstrap('view');
            $view = $this->getBootstrap()->getResource('view');

            ZendX_JQuery::enableView($view);
            
            $options = array_change_key_case($this->getOptions(), CASE_LOWER);
            foreach ($options as $key => $value) {
                switch ($key)
                {
                    case 'cdnssl':
                        $view->jQuery()->setCdnSsl($value);
                        break;

                    case 'enable':
                        if ($value) {
                            $view->jQuery()->enable();
                        }
                        break;

                    case 'javascriptfiles':
                        foreach ($value as $name => $path) {
                            $view->jQuery()->addJavascriptFile($path);
                        }
                        break;

                    case 'localpath':
                        $view->jQuery()->setLocalPath($value);
                        break;

                    case 'rendermode':
                        $view->jQuery()->setRenderMode($value);
                        break;

                    case 'stylesheets':
                        foreach ($value as $name => $path) {
                            $view->jQuery()->addStylesheet($path);
                        }
                        break;

                    case 'uienable':
                        if ($value) {
                            $view->jQuery()->uiEnable();
                        }
                        break;

                    case 'uilocalpath':
                        $view->jQuery()->setUiLocalPath($value);
                        break;

                    case 'uiversion':
                        $view->jQuery()->setUiVersion($value);
                        break;

                    case 'version':
                        $view->jQuery()->setVersion($value);
                        break;
                }
            }

            $this->_jquery = $view->jQuery();
        }

        return $this->_jquery;
    }
}
