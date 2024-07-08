<?php

/**
 * Racenet_Model_Abstract
 *
 */
class Racenet_Model
{
    const ACL_NONE  = 0x0000;
    const ACL_READ  = 0x0001;
    const ACL_WRITE = 0x0002;
    const ACL_FULL  = 0x0003;
    
    /**
     * The object's data
     *
     * @var array
     */
    private $_properties = array();
    
    /**
     * Throw exceptions?
     *
     * @var boolean
     */
    private $_throwExceptions = true;
    
    /**
     * Model is initialized after setDefaults has been called
     *
     * @var boolean
     */
    private $_isDefined = false;
    
    /**
     * Constructor
     *
     * @param unknown_type $options
     */
    public final function __construct($properties = null)
    {
        // set the default properties
        $this->setModelDefinition();
        $this->_isDefined = true;
        
        // if it's an object convert it to an array
        if ($properties instanceof Racenet_Model) {
            
            $properties = $properties->toArray();
        
        } else if (is_object($properties)) {
            
            $properties = (array)$properties;
        }
        
        // if it's an array, set own values from it
        if(is_array($properties)) {
            
            $this->fromArray($properties);
        }    
    }
    
    /**
     * Throw Exceptions?
     *
     * @param boolean $status
     * @return boolean|Racenet_Model
     */
    public function throwExceptions($status = null)
    {
        if ($status === null) {
            
            return $this->_throwExceptions;
        }
        
        $this->_throwExceptions = (boolean)$status;
        return $this;
    }
    
    /**
     * Does the model require a schema?
     *
     * @return boolean
     */
    public function requiresModelDefinition()
    {
        return __CLASS__ != get_class($this);
    }
    
    /**
     * Set the default values
     *
     */
    public function setModelDefinition()
    {
        if ($this->requiresModelDefinition()) {
            
            throw new Racenet_Model_Exception('No model definition set');
        }
    }
    
    /**
     * Set properties from an array
     *
     * @param array $arr
     * @return Racenet_Model_Ranking_Options
     */
    public final function fromArray(array $arr)
    {
        foreach ($arr as $key => $value) {
          
            $this->$key = $value;
        }
        return $this;
    }
    
    /**
     * Set properties from a config object
     *
     * @param integer $page
     */
    public function fromConfig(Zend_Config $config)
    {
        foreach ($config->toArray() as $key => $value) {
          
            $this->$key = $value;
        }
        
        return $this;
    }
    
    /**
     * Set properties from a request object
     *
     * @param integer $page
     */
    public function fromRequest(Zend_Controller_Request_Abstract $request)
    {
        $params = $request->getParams();
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        
        foreach ($params as $key => $value) {
          
            $this->$key = $value;
        }
        
        return $this;
    }
    
    /**
     * Get an array of proterty values
     *
     * @return array
     */
    public final function toArray()
    {
        return $this->_properties;
    }
    
    /**
     * Magic getter
     *
     * @param string $property
     * @return mixed
     */
    public final function __get($property)
    {
        $property = strtolower($property);
        
        if ($this->_throwExceptions && $this->_isDefined && $this->requiresModelDefinition() && !array_key_exists($property, $this->_properties)) {
            
            throw new Racenet_Model_Exception('Trying to get non existant property '. get_class($this) .'->'. $property);
        }
        
        return isset($this->_properties[$property]) ? $this->_properties[$property] : null;
    }
    
    /**
     * Magic setter
     *
     * @param string $property
     * @param mixed $value
     */
    public final function __set($property, $value)
    {
        
        $property = strtolower($property);
        
        // by default we throw exceptions when trying to access an undefined or otherwise unaccessable property
        if ($this->_throwExceptions &&
            $this->_isDefined &&
            $this->requiresModelDefinition() &&
            !array_key_exists($property, $this->_properties)) {
        
            throw new Racenet_Model_Exception('Trying to set non existant property '. get_class($this) .'->'. $property);
        }
        
        // apply the filter to the property if it is defined  
        if ($this->_isDefined && method_exists($this, 'filter'. $property)) {
            
            $value = $this->{'filter'. $property}($value);
        }
        
        $this->_properties[$property] = $value;
    }
    
    /**
     * Magic caller
     *
     * @param string $fn
     * @param array $attr
     * @return mixed|Racenet_Model_Ranking_Options
     */
    public final function __call($fn, $attr)
    {
        if (preg_match('/^((?:un)?[sg]et|actAs|is|has|add)([A-Z]\w*$)$/', $fn, $ref)) {
            
            $action = $ref[1];
            $property = strtolower($ref[2]);
            $value = isset($attr[0]) ? $attr[0] : null;
            
            switch ($action) {
                
                // setOptionName
                case 'set':
                    $this->$property = $value;
                    if (isset($attr[1])) {
                       
                        $this->_properties[$property]->access = $attr[1];
                    }
                    return $this;
                    break;
                
                // getOptionName
                case 'get':
                    return $this->$property;
                    
                // addOptionName
                case 'add':
                    if (is_numeric($this->$property) && is_numeric($value)) {
                        
                        $this->$property += $value;

                    } else if(is_string($this->$property)) {
                        
                        $this->$property .= $value;
                        
                    } else if(is_array($this->$property)) {
                        
                           $this->$property = array_merge($this->$property, $value);
                    }
                    return $this;
                    break;
                
                // isOptionName, actAsOptionName
                case 'is':
                case 'actAs':
                case 'has':
                    // setter
                    if (count($attr)) {
                        
                        $this->$property = (boolean)$value;
                        return $this;
                    
                    // getter
                    } else {
                    
                           return (boolean)$this->$property;
                    }
            }
        }

        if ($this->_throwExceptions) {
        
            throw new Racenet_Model_Exception('Called non existant method '. get_class($this) .'::'. $fn .'('. implode(', ', $attr) .')');
        }
        
        return $this;
    }
}