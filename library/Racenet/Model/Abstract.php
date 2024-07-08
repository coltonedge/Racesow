<?php
/**
 * Racenet_Model_Abstract
 * 
 * @category   Racenet
 * @package    Racenet_Model
 */

abstract class Racenet_Model_Abstract
{
    protected $_db = null;
    protected $_dbRegistryKey = 'db';

    /**
     * Enter description here...
     *
     */
    final public function __construct( $props = array() )
    {
        foreach ($props as $key => $value) {
            
            if (property_exists( $this, '_'. $key )) {
                
                $this->{'_'. $key} = $value;
            
            } else {
                
                require_once 'Racenet/Model/Exception.php';
                throw new Racenet_Model_Exception("The property '\$_$key' (passed by props array) does not exist in ". get_class($this) .".");
            }
        }
        
        // try to get DB from registry if not passed as prop
        if (null === $this->_db) {
            
            Zend_Registry::isRegistered($this->_dbRegistryKey);
            $this->_db = Zend_Registry::get($this->_dbRegistryKey);
        }
        
        // if we dont have a db conenction here somesthing went wrong
        if (!$this->_db) {
            
            require_once 'Racenet/Model/Exception.php';
            throw new Racenet_Model_Exception("No databse connecttion");
        }
        
        $this->init();
    }
    
    /**
     * Constructor replacement for children classes
     *
     */
    public function init()
    {
    }
}
