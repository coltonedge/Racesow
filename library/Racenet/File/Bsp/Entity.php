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
 * @package    Racenet_File
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */


/**
 * Racenet_File_Bsp_Entity
 * 
 * @category   Racenet
 * @package    Racenet_File
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */
class Racenet_File_Bsp_Entity
{
    /**
     * The entities classname
     *
     * @var unknown_type
     */
    protected $_classname;

    /**
     * The entities keys
     *
     * @var Array
     */
    protected $_data = array();
    
    /**
     * Magic setter
     *
     * @param string $key
     * @param mixed $value
     * @return Racenet_File_Bsp_Entity
     */
    public function __set( $key, $value )
    {
        if( !is_string( $key ) )
        {
            require_once 'Racenet/File/Bsp/Exception.php';
            throw new Racenet_File_Bsp_Exception('property name must be a string');
        }
        
        switch( $key )
        {
            // TODO: do more properties need special treatment?
            
            case 'classname':
                 $this->_classname = (string)$value;
                 break;
            
            case 'origin':
                require_once 'Racenet/Object/Coordinate.php';
                $this->_data[$key] = new Racenet_Object_Coordinate($value);
                break;
                
            default:
                $this->_data[$key] = (string)$value;
                break;
        }
        
        
        return $this;
    }
    
    /**
     * Magic getter
     *
     * @param string $key
     * @return mixed
     */
    public function __get( $key )
    {
        if( $key == 'classname' )
        {
           return $this->_classname;
        }
        else if( array_key_exists($key, $this->_data) )
        {
            return $this->_data[$key];
        }
        
        return null;
    }
    
    /**
     * Validate the Entity using the given config
     *
     * @param Racenet_Config_Quaked $config
     * @return boolean
     */
    public function isValid( Racenet_Config_Quaked $config )
    {
        if( !isset( $config->{$this->classname} ) )
        {
            return false;
        }
        
        $config = $config->{$this->classname};
        
        foreach( $this->_data as $key => $value )
        {
            // origin is always possible but rarely defined in the entities.def
            if( $key == 'origin' )
               continue;
            
            if( !isset( $config->keys->$key ) )
            {
                echo "$key not defeined for $this->classname<br>";
                return false;
            }
        }
        
        return true;
    }
}

?>