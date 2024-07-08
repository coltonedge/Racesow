<?php

class Racenet_Controller_Plugin_FrontControllerCaching extends Zend_Controller_Plugin_Abstract
{
    protected $_cache;
    
    protected static $_noCache = false;
    
    protected $_key;
    
    /**
     * Constrcutor
     *
     */
    public function __construct()
    {
        Zend_Loader::loadClass('Zend_Cache');
        $this->_cache = Zend_Cache::factory(
            'Core',
            'File',
            array('lifetime' => 555,
                  'automatic_serialization' => true),
            array('cache_dir' => PATH_CACHE)
        );
    }
    
    /**
     * Start caching
     *
     * Determine if we have a cache hit. If so, return the response; else,
     * start caching.
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (!$request->isGet() || $request->getControllerName() == 'board') {
            self::$_noCache = true;
            return;
        }

       $path = $request->getPathInfo();
       $this->_key = sha1($path); // ($request->isXmlHttpRequest() ? 1 : 0);
       
        if (false !== ($response = $this->_loadCachedAction())) {
            
            $this->setResponse($response);
        }
    }

    /**
     * Store cache
     * 
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (self::$_noCache
            || $this->getResponse()->isRedirect()
            || (null === $this->_key)
        ) {
            return;
        }
        $this->_cache->save($this->getResponse(), $this->_key);    
    }
    
    /**
     * Enter description here...
     *
     * @return unknown
     */
    protected function _loadCachedAction()
    {
        if (false !== ($response = $this->_cache->load($this->_key))) {
          return $response;
        }
        return false;
    }
        
    
}
