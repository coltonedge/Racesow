<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Ini.php 11206 2008-09-03 14:36:32Z ralph $
 */


/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';


/**
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Racenet_Config_Quaked extends Zend_Config
{
    /**
     * Loads the config file $filename for access
     * facilitated by nested object properties.
     *
     * The $options parameter may be provided as either a boolean or an array.
     * If provided as a boolean, this sets the $allowModifications option of
     * Zend_Config. If provided as an array, there are two configuration
     * directives that may be set. For example:
     *
     * $options = array(
     *     'allowModifications' => false,
     *     'nestSeparator'      => '->'
     *      );
     *
     * @param  string        $filename
     * @param  boolean|array $options
     * @throws Zend_Config_Exception
     * @return void
     */
    public function __construct($filename, $options = false)
    {
        if (empty($filename)) {
            /**
             * @see Zend_Config_Exception
             */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception('Filename is not set');
        }

        $allowModifications = false;
        if (is_bool($options)) {
            $allowModifications = $options;
        } elseif (is_array($options)) {
            if (isset($options['allowModifications'])) {
                $allowModifications = (bool) $options['allowModifications'];
            }
        }

        $dataArray = array();
        $section = str_replace(".def", "", basename($filename));
        $preProcessedArray[$section] = array();
        
        Zend_Loader::loadClass('Racenet_File');
        $content = Racenet_File::readFile($filename);
        if( preg_match_all( "/\/*QUAKED .*?\*\//s", $content, $result ) )
        {
            foreach( $result[0] as $entity )
            {
                $entity = $this->_parseEntity($entity);
                $dataArray[ $entity["classname"] ] = $entity;
            }
        }
        
        parent::__construct($dataArray, $allowModifications);
    }
    
    /**
     * Enter description here...
     *
     * @param string $def
     */
    protected function _parseEntity( $def )
    {
        $entity = array(
           "classname" => null,
           "keys" => array(),
           "spawnflags" => array(),
           "notes" => null
        );
        
        if( preg_match( "/^QUAKED ([^ ]+)/", $def, $result ) )
        {
            $entity["classname"] = $result[1];
        }
        
        if( preg_match_all( "/(([a-z]+) : (.+))+/", $def, $result ) )
        {
            foreach( $result[2] as $index => $key )
            {
                $entity["keys"][$key] = $result[3][$index];
            }
        }
        
        if( preg_match_all( "/(([A-Z]+) : (.+))+/", $def, $result ) )
        {
            foreach( $result[2] as $index => $spawnflag )
            {
                $entity["spawnflags"][$spawnflag] = $result[3][$index];
            }
        }
        return $entity;
    }
}
