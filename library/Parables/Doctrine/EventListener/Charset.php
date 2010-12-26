<?php
class Parables_Doctrine_EventListener_Charset extends Doctrine_EventListener
{
    /**
     * @var string
     */
    protected $_charset = 'utf-8';
    
    /**
     *
     * @param string $charset
     */
    public function __construct($charset = 'utf-8') {
        $this->_charset = $charset;
    }

    /**
     * Sets charset on connection after connect
     *
     * @param Doctrine_Event $event
     */
    public function postConnect(Doctrine_Event $event)
    {
        $event->getInvoker()->setCharset($this->_charset);
    }

    /**
     * Set used charset
     * @param string $charset
     * @return Parables_Doctrine_EventListener_Charset *Provides Fluent Interface*
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
        return $this;
    }

    /**
     * Get used charset
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->_charset;
    }
}