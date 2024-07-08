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
 * Racenet_File_Defi
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
class Racenet_File_Defi extends Racenet_File_Abstract
{
    /**
     * Parse the defi-file's content for values
     *
     * @param string $content .defi- or .arena-file contents
     */
    protected function _abstractData($content)
    {
        if (preg_match_all("/([^\s]+)\s+\"([^\"]*)\"/", $content, $hits)) {
            
            $this->_abstractData = array();
            foreach ($hits[1] as $index => $key) {
                
                $this->_abstractData[$key] = $hits[2][$index];
            }
            
            return $this->_abstractData;
        }
    }
    
    /*
{
map         "BardoK-Cpm"
longname "Made By BardoK"
style         "run"
cpm  "1"
vq3  "0"
author  "BardoK"
}

    */
}

?>