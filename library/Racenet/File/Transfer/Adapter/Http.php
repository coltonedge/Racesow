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
 * @package    Racenet_File_Transfer
 * @copyright  Copyright (c) 2006-2008 The Warsow Racenet (http://www.warsow-race.net)
 * @license    http://www.warsow-race.net/library/LICENSE.txt     New BSD License
 */

require_once 'Zend/File/Transfer/Adapter/Http.php';

/**
 * File transfer adapter class for the HTTP protocol
 *
 * @category  Zend
 * @package   Racenet_File_Transfer
 * @copyright Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class Racenet_File_Transfer_Adapter_Http extends Zend_File_Transfer_Adapter_Http
{
    /**
     * Receive files from the client (Upload)
     * Applies filters BEFORE moving the files to their final destinations
     *
     * @param  string|array $files (Optional) Files to receive
     * @return bool
     */
    public function receive($files = null)
    {
        $this->_filter($files);

        if (!$this->isValid($files)) {
            return false;
        }

        $check = $this->_getFiles($files);
        foreach ($check as $file => $content) {
            $directory   = '';
            $destination = $this->getDestination($file);
            if ($destination !== null) {
                $directory = $destination . DIRECTORY_SEPARATOR;
            }

            // Should never return false when it's tested by the upload validator
            if (!move_uploaded_file($content['tmp_name'], ($directory . $content['name']))) {
                if (isset($this->_options['ignoreNoFile'])) {
                    continue;
                }

                return false;
            }
        }

        return true;
    }
    
    /**
     * Retrieve additional internal file informations for files
     *
     * @param  string $file (Optional) File to get informations for
     * @return array
     */
    public function getFileInfo($file = null)
    {
        return $this->_getFiles($file, false, true);
    }
}
