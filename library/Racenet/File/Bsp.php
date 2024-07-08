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
 * Racenet_File_Bsp
 * 
 * @category   Racenet
 * @package    Racenet_File
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 * 
 * Available params (setParam)
 *     filter_classname_regexp, regexp to filter entities by their classnames
 *     match_all, if true all regexps have to match, if false at least one has to, default false
 */
class Racenet_File_Bsp extends Racenet_File_Abstract
{
    /**
     * Regular expression to filter the entitie's to be shown
     *
     * @var string
     */
    protected $_filterRegExp;

    /**
     * Use the regexp to include or to exclude
     *
     * @var boolean
     */
    protected $_filterInclude;

    /**
     *  Entity definitions
     *
     * @var Racenet_Config_Quaked|null
     */
    protected $_config;
    
    /**
     * Set an entity definition config
     *
     * @param Racenet_Config_Quaked $config
     */
    public function setConfig( Racenet_Config_Quaked $config )
    {
        $this->_config = $config;
    }
    
    /**
     * Set a regular expression to filter entities
     *
     * @param string $regRxp
     * @param boolean $include
     */
    public function setViewFilter( $regExp, $include = true )
    {
        $this->_filterInclude = $include;
        $this->_filterRegExp = $regExp;
    }
    
    /**
     * Validate the BSP's entities
     *
     */
    public function isValid()
    {
        if( $this->_config === null )
        {
            return true;
        }
            
        $isValid = true;
        foreach( $this->_abstractData as $entity )
        {
            $isValid &= $entity->isValid($this->_config);
        }
        return $isValid;
    }
    
    /**
     * Parse the BSP content for entities
     *
     * @param string $content .bsp-file contents
     */
    protected function _abstractData( $content )
    {
        if( !preg_match_all("/\{([a-zA-Z0-9\r\n\-\_\" ]+)\}/", $content, $result) )
        {
            return false;
        }

        foreach( $result[1] as $entityString )
        {
            if( preg_match_all( "/\"(.*?)\" \"(.*?)\"/", $entityString, $pairs ) )
            {
                require_once 'Racenet/File/Bsp/Entity.php';
                $entity = new Racenet_File_Bsp_Entity($this);
               
                foreach( $pairs[1] as $index => $prop )
                {
                    $entity->$prop = $pairs[2][$index];
                       if( $prop == 'classname' && !empty( $this->_filterRegExp ) )
                       {
                        if( $this->_filterInclude != preg_match( $this->_filterRegExp, $pairs[2][$index] ) )
                           {
                            $entity = null;
                            unset($entity);
                            continue 2;
                        }
                    }
                }
                $this->_abstractData[$entity->classname] = $entity;
            }
        }
        return true;
    }
}

?>