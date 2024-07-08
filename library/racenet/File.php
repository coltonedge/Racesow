<?php

class Racenet_File
{
    const DEL_RECURSIVE = 0x00000001;
    
    /**
     * Do not allow to instanciate ...
     *
     */
    private function __construct()
    {
    }
    
    /**
     * Read contents from a file
     *
     * @param integer $length
     * @param string $file
     * @return string
     */
    static public function read($path = null, $length = null)
    {
        if( is_file($path) )
        {
            return self::readFile($path, $length);
        }
        
        if( is_dir($path) )
        {
            return self::readDir($path, $length);
        }
        
        require_once 'Racenet/File/Exception.php';
        throw new Racenet_File_Exception( $path .' does not exist!' );
    }
    
    /**
     * Read content from a file
     *
     * @param string $path
     * @param string $length
     * @return string
     */
    static public function readFile($path, $length = null)
    {
        if( !is_file($path) )
        {
            require_once 'Racenet/File/Exception.php';
            throw new Racenet_File_Exception( $path .' does not exist or is not a file' );
        }
        
        if( !$handle = fopen($path, 'r') )
        {
            require_once 'Racenet/File/Exception.php';
            throw new Racenet_File_Exception( 'Can not open '. $path .'!' );
        }
        
        if( $length === null )
        {
            $length = filesize($path);
        }
        
        $content = fread($handle, $length);
        fclose($handle);
        return $content;
    }
    
    /**
     * Read files from a firectory
     *
     * @param string $path
     * @param integer $length
     * @return array List of files and folders found in the given directory
     */
    static public function readDir($path, $length = null)
    {
        if( !is_dir($path) )
        {
            require_once 'Racenet/File/Exception.php';
            throw new Racenet_File_Exception( $path .' does not exist or is not a directory' );
        }
        
        $count = 0;
        $content = array();
        $dirHandle = opendir($path);
        while( $file = readdir($dirHandle) )
        {
            if( $file == '.' || $file == '..' )
                continue;
            
            if( $length !== null && ++$count > $length)
                break;
            
            $content[] = $file;
        }
        closedir($dirHandle);
        return $content;
    }
    
    /**
     * Write content into a selected file
     *
     * @param string $content
     * @return Racenet_File
     */
    static public function write( $path, $content, $type = 'w' )
    {
        if( is_writable( $path ) )
        {
            if (!$handle = fopen($path, $type ) )
            {
                require_once 'Racenet/File/Exception.php';
                throw new Racenet_File_Exception( 'Can not open '. $path .'!' );
            }
            
            if( !fwrite( $handle, $content ) )
            {
                require_once 'Racenet/File/Exception.php';
                throw new Racenet_File_Exception( 'Can not write into '. $path .'!' );
            }
            
            fclose( $handle );
            return true;
        }
        else
        {
            require_once 'Racenet/File/Exception.php';
            throw new Racenet_File_Exception( $path .' is not writeable!' );
        }
        
        return false;
    }
    
    /**
     * Delete a file or a whole folder
     *
     * @param string $path
     * @param integer $flags
     * @return boolean
     */
    static public function delete($path, $flags = 0)
    {
        if( is_file($path) )
        {
            return unlink($path);
        }
        
        if( is_dir($path) )
        {
            $path = preg_replace("@[\\\/]+$@", "", $path);
            if( $flags & self::DEL_RECURSIVE )
            {
                $dirHandle = opendir($path);
                while( $file = readdir($dirHandle) )
                {
                    if( $file == '.' || $file == '..' )
                      continue;
                      
                    self::delete($path . DIRECTORY_SEPARATOR . $file, $flags);
                }
                closedir($dirHandle);
            }
            return @rmdir($path);
        }
    }
}
?>