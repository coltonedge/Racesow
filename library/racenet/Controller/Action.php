<?php

require_once( 'Zend/Controller/Action.php' );

/**
 * The Default Action which all Actions used in the Racenet have to be based on.
 *
 * @author            Andreas Linden, zlx@gmx.de
 * 
 * @name                 
 * @abstract
 * @package            Racenet
 * @subpackage    Racenet_Controller
 *  
 * @uses                Zend_Controller_Action
 * @uses                Zend_Controller_Action_HelperBroker
 * @uses                Zend_Loader
 * @uses                Zend_Registry
 * @uses                Zend_Layout
 */
abstract class Racenet_Controller_Action extends Zend_Controller_Action
{

    public $config;
    public $layout;
    protected $_acl;

    /**
     * Dispatch the requested action
     * 
     * @param string $action Method name of action
     * @return void
     */
    public function preDispatch()
    {        
        // Add Racenet ViewHelpers
        $this->view->addHelperPath('Racenet/View/Helper', 'Racenet_View_Helper');
        
        // Add Racenet ActionHelpers
        Zend_Controller_Action_HelperBroker::addPrefix('Racenet_Controller_Action_Helper');
        
        // Config
        $this->config = Zend_Registry::get('config');
        
        // Layout
        $this->layout = Zend_Layout::getMvcInstance();
        
        // Navigation        
        $this->layout->nav = NavigationTree::getInstance();
        
         // jQuery.history
        if( $this->getRequest()->isXmlHttpRequest() )
        {
            // always handle ajax request without the layout 
            $this->layout->disableLayout();
            
            // fix with pagination in firefox caused by clientside cache
            $this->getResponse()->setHeader('Cache-Control', 'no-store', true);
        }

        $this->user = RacenetAccount::getInstance();
        $this->_checkAcl();
        
    }
    
    /**
     * Check the acl and forward if required
     *
     */
    protected function _checkAcl()
    {
       if( isset( $this->_acl ) )
       {
            if( !is_array( $this->_acl ) || !isset($this->_acl["controller"]) && !is_array( $this->_acl["actions"] ) )
            {
                throw new Exception("invalid acl specified in ". $this->_request->getControllerName());
            }
            
            if( ( isset( $this->_acl["controller"] ) &&
                    !($this->user->racenet_flags & $this->_acl["controller"] ) ) ||
                    
                  isset( $this->_acl["actions"][$this->_request->getActionName()] ) &&
                    !($this->user->racenet_flags & $this->_acl["actions"][$this->_request->getActionName()] ) ) {  
                             
                if( is_array( $this->_acl["forward"] ) && count( $this->_acl["forward"] ) ) {
                    
                    switch( count( $this->_acl["forward"] ) ) {
                        case 1:
                            $this->_forward($this->_acl["forward"][0]);
                            break;
                            
                        case 2:
                            $this->_forward($this->_acl["forward"][0], $this->_acl["forward"][1]);
                            break;
                    }
                    
                } else {
                    
                     $this->_forward("noaccess");
                }
            }
        }
    }
}
