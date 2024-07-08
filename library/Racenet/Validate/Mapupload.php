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
 * @package    Racenet_Validate
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */


/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';  


/**
 * Racenet_Validate_Mapupload
 * 
 * @category   Racenet
 * @package    Racenet_Validate
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */
class Racenet_Validate_Mapupload extends Zend_Validate_Abstract
{
    const ER_MAPS = 'pakfile_nomaps';
    const ER_NAME = 'max_exists';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        ZIPARCHIVE::ER_EXISTS => "File already exsists",
        ZIPARCHIVE::ER_INCONS => "File is an inconsistent archive",
        ZIPARCHIVE::ER_INVAL => "File is an invalid archive",
        ZIPARCHIVE::ER_MEMORY => "Some memory error :>",
        ZIPARCHIVE::ER_NOENT => "File is no ent?",
        ZIPARCHIVE::ER_NOZIP => "File is not a valid .pk3",
        ZIPARCHIVE::ER_OPEN => "Could not open the archive",
        ZIPARCHIVE::ER_READ => "Could not read the archive",
        ZIPARCHIVE::ER_SEEK => "Could not seek the archive",
        self::ER_MAPS => "The uploaded .pk3 does not contain a map",
        self::ER_NAME => "The map '%value%' already exists"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * @param  string $value
     * @param  array $context Dummy, for method-signature compatibility
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        if (!is_string($value) || !is_file($value)) {
            
            return false;
        }
        
        $tempDir = $this->_createTempDir();
        
        $zip = new ZipArchive;
        if (true !== ($err = $zip->open($value))) {
            
            $this->_error($err, $value);
            return false;
        }
        
        if (true !== ($err = $zip->extractTo($tempDir)))  {
            
            $this->_error($err, $value);
            return false;
        }
        
        $maps = array();
        $mapsDir = $tempDir . DIRECTORY_SEPARATOR . 'maps';
        if (is_dir($mapsDir)) {
            
            $dirHandle = opendir($mapsDir);
            while ($file = readdir($dirHandle)) {
                
                if (substr( $file, -4 ) == '.bsp') {
                    
                    $maps[$file] = true;
                }
            }
            closedir($dirHandle);
        }
        
        Racenet_File::delete($tempDir, Racenet_File::DEL_RECURSIVE);
        
        if(!count($maps)) {
            
            $this->_error(self::ER_MAPS, $value);
            return false;
        }

        foreach ($maps as $file => $null) {
            
            $mapName = preg_replace("/\..+$/", "", $file);
            if ($map = Doctrine::getTable('Map')->findOneByName($mapName)) {
                
				if (!empty($map->file)) {
                
					$this->_error(self::ER_NAME, $mapName);
					return false;
				}
            }
        }
        
        return true;
    }
    
    /**
     * Create a temp directory for extracting
     * and testing the zip/pk3 archive
     * 
     * @return string Path to the created directory
     */
    protected function _createTempDir()
    {
        if (function_exists('sys_get_temp_dir')) {
            
            $tempDir = sys_get_temp_dir();
            
        } else {
            
            $tempDir = '/tmp';
        }
        
        $tempDir = preg_replace('/\/$/', '', $tempDir);
        $tempDir .= '/racenet_upload_'. substr(uniqid(), -6);
        mkdir($tempDir);
        
        return $tempDir;
    }
}
