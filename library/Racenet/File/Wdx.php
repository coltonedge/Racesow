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
 * @see Racenet_File_Abstract
 */
require_once 'Racenet/File/Abstract.php';


/**
 * Racenet_File_Wdx
 * 
 * @category   Racenet
 * @package    Racenet_File
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 * 
 * Available params (setParam)
 *     filter_classname_regexp, regexp to filter entities by their classnames
 *     match_all, if true all regexps have to match, if false at least one has to, default false
 * 
 * 
 * @example 
 * 
 *     $path = '/path/to/mydemo.wd10';
 *     require_once 'Racenet/File/Wdx.php';
 *     $demo = new Racenet_File_Wdx($path);    
 *     if(preg_match("/\.wd(\d+)$/", $path, $v)) {
 *         $demo->setVersion((integer)$v[1]);
 *     }
 *     try {
 *         $data = $demo->getData();
 *     } catch( Racenet_File_Wdx_Exception $e ) {
 *         $errorMsg = $e->getMessage();
 *     }
 * 
 */
class Racenet_File_Wdx extends Racenet_File_Abstract
{
    /**
     * Version of the demo file to read from
     *
     * @var integer
     */
    private $_version = 10;
    
    /**
     * Set the demofile version
     *
     * @param integer $version
     * @return Racenet_File_Wdx
     */
    public function setVersion( $version )
    {
        $this->_version = (integer)$version;
        return $this;
    }
    
    /**
     * Parse the BSP content for entities
     *
     * @param string $content .bsp-file contents
     */
    protected function _abstractData( $content )
    {
        switch($this->_version)
        {
            case 9:
            case 10:
                $this->_abstractData = array(
                    "mapname" => "",
                    "races" => array(),
                    "nicknames" => array(),
                );
                  
                if( preg_match( "/cs 0 \"(.*?)\"/", $content, $data ) )
                {
                    $this->_abstractData['mapname'] = $data[1];
                }
                
                if( preg_match_all( "/cp \"Race finished\: (\d{2})\:(\d{2})\.(\d{3})/", $content, $data ) )
                {
                    foreach($data[0] as $key => $null)
                    {
                        $this->_abstractData['races'][$key] = array(
                            "min" => $data[1][$key],
                            "sec" => $data[2][$key],
                            "milli" => $data[3][$key],     
                       );
                    }
                }
                
                if( preg_match_all( "/cs \d+ \"\\\\name\\\\(.*?)\\\\hand\\\\(\d+)\\\\mt\\\\(\d+)\\\\color\\\\(\d+) (\d+) (\d+)\"/", $content, $data ) )
                {
                    foreach($data[0] as $key => $null)
                    {
                        $this->_abstractData['nicknames'][$key] = array(
                            "name" => $data[1][$key],
                            "simplified" => preg_replace("/(?<!\^)\^[^^]/", "", $data[1][$key]),
                            "hand" => $data[2][$key],
                            "mt" => $data[3][$key],
                            "color" => array( $data[4][$key], $data[5][$key], $data[6][$key]),
                        );
                    }
                }
                break;
            
            default:
                require_once 'Racenet/File/Wdx/Exception.php';
                throw new Racenet_File_Wdx_Exception('Version '. $this->_version .' is not implemented');
        }
    }
}
