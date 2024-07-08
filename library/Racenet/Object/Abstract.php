<?php

/**
 * Racenet Library, extension for Zend Framework
 *
 * LICENSE
 * 
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.warsow-race.net/library/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@warsow-race.net so we can send you a copy immediately.
 * 
 * @category   Racenet
 * @package    Racenet_Object
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */


/**
 * Racenet_Object_Abstract
 * 
 * @category   Racenet
 * @package    Racenet_Object
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */
class Racenet_Object_Abstract
{
    /**
     * Values
     *
     * @var array
     */
    protected $_props = array();

    /**
     * Check the properties visibility. Note, private and
     * protected properties will appear public if they do
     * not start with an underscore. Sense is to stricly
     * disallow setting undefined member variables.
     * 
     * @throws Racenet_Object_Exception
     * 
     * @example Default visibility notation
     *     public $var;
     *     private $_var; 
     *     proteceted $_var;
     * 
     * @example Force public Visibility notation
     *     private $var;
     *     protected $var;
     */
    protected function _checkVisibility($name)
    {
        if( !array_key_exists($name, $this->_props) )
        {
            require_once 'Racenet/Object/Exception.php';
            throw new Racenet_Object_Exception( $name. ' is not a property of '. get_class($this));
        }
    }

    /**
     * Magic setter. Only allow to setting listed properties
     *
     * @param string $name
     */
    final public function __set($name, $value)
    {
        $this->_checkVisibility($name);
        $this->_set($name, $value);
    }
    
    /**
     * Replacement for __set to be used in derived classes if needed
     *
     * @param string $name
     * @param mixed $value
     */
    protected function _set($name, $value)
    {
        $this->_props[$name] = $value;
    }
    
    /**
     * Magic getter. Only allow getting listed properties
     *
     * @param string $name
     */
    final public function __get($name)
    {
        $this->_checkVisibility($name);
        return $this->_get($name);
    }
    
    /**
     * Replacement for __get to be used in derived classes if needed
     *
     * @param string $name
     */
    protected function _get($name) 
    {
        return $this->_props[$name];
    }
    
    /**
     * Copy the objects values
     *
     * @param mixed $input
     * @return Racenet_Object_Abstract
     */
    public function copy($input)
    {
        if( !is_object($input) || get_class($this) != get_class($input))
        {
            require_once 'Racenet/Object/Exception.php';
            throw new Racenet_Object_Exception( 'cannot copy to '. get_class($this) );
        }
        $this->setProps($input->getProps());
        return $this;
    }
    
    /**
     * Paste the objects values
     *
     * @param mixed $output
     * @return Racenet_Object_Abstract
     */
    public function paste($output)
    {
        if( !is_object($output) || get_class($this) != get_class($output))
        {
            require_once 'Racenet/Object/Exception.php';
            throw new Racenet_Object_Exception( 'cannot copy to '. get_class($output) );
        }
       $output->setProps($this->getProps());
       return $this;
    }
    
    /**
     * Set properties
     *
     * @param array $props
     */
    public function setProps($props)
    {
        if( !is_array($props) )
        {
            require_once 'Racenet/Object/Exception.php';
            throw new Racenet_Object_Exception('setProps() requires an array');
        }
        
        foreach( $props as $prop => $value )
        {
            $this->$prop = $value;
        }
    }
    
    /**
     * Get properties
     *
     * @return unknown
     */
    public function getProps()
    {
        return $this->_props;
    }
    
    /**
     * Try to convert any input into an object of the given classname.
     * If no classname is given it will be determined using own class.
     * Returns the input itsself if it already meets this criteria.
     *
     * @param mixed $input
     * @return Racenet_Object_Abstract
     */
    static public function toInstance($input, $className)
    {
        if($input instanceof $className)
        {
            return $input;
        }
        else
        {
            return new $className($input);
        }
    }
}
