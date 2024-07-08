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
 * Racenet_File_Abstract
 * 
 * @category   Racenet
 * @package    Racenet_File
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */
abstract class Racenet_File_Abstract
{
    /**
     * Source of the file. Absolute path and filename
     *
     * @var string
     */
    private $_source = null;

    /**
     * Parameters to be used while parsing
     *
     * @var unknown_type
     */
    protected $_params = array();
    
    /**
     * Raw data read from a file
     *
     * @var string
     */#
    protected $_fileContents;
    
    /**
     * Abstracted datastructure of the files content-type
     *
     * @var mixed
     */
    protected $_abstractData = null;
    
    /**
     * Constructor
     *
     */
    final public function __construct($source, $options = array())
    {
        if( !is_string($source) )
        {
            require_once 'Racenet/File/Exception.php';
            throw new Racenet_File_Exception('given source is not a string');
        }
        
        $this->_source = $source;
        
        // TODO: "create"-flag in options
        // TODO: "overwrite"-flag in options
        if( !is_file( $this->_source ) )
        {
            require_once 'Racenet/File/Exception.php';
            throw new Racenet_File_Exception('"'. $source .' is not a file');
        }
        
        $this->_init();
        $this->_read($options);
    }
    
    /**
     * Replacement for constructor
     *
     */
    protected function _init()
    {
    }
    
    /**
     * Get the source path and filename
     *
     * @return unknown
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * Read data from file
     *
     * @param unknown_type $source
     * @param unknown_type $options
     */
    private function _read( $options = array() )
    {
        $length = null;
        if( isset( $options['length'] ) )
        {
            $length = $options['length'];
        }
        
        require_once 'Racenet/File.php';
        $this->_fileContents = Racenet_File::read( $this->_source, $length );
        return $this;
    }
    
    
    
    /**
     * Abstracted view on the file
     *
     * @return mixed 
     */
    final public function getData()
    {
        $this->_abstractData($this->_fileContents);
        if( is_object( $this->_abstractData ) )
        {
            return clone $this->_abstractData;
        }
        return $this->_abstractData;
    }
    
    /**
     * Read the passed content and abcstract it in the
     * way you need the file's data beeing represented
     *
     * @param string $data The content to parse values from
     * @return boolean Return if something was parsed or not
     */
    abstract protected function _abstractData( $data );
}

?>