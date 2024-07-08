<?php

error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 'on' );
ini_set('max_execution_time',0);
date_default_timezone_set( 'Europe/Berlin' );

require '../config/init.php';

// Database
$db = Zend_Db::factory($config->database);
$db->setFetchMode(Zend_Db::FETCH_OBJ);
Zend_Db_Table_Abstract::setDefaultAdapter($db);

if( function_exists('sys_get_temp_dir') )
{
    $tempDir = sys_get_temp_dir();
}
else
{
    $tempDir = "/tmp/";
}

// TODO:
$config = Zend_Registry::get('config');
$basewsw = $config->path->warsow->data . $config->path->warsow->mod;

$maps = Doctrine_Query::create()
	->from('Map')
	->where("status = 'enabled'");
	->andWhere('file');
	->execute();
	
foreach ($maps as $map) {
    
    $src = $basewsw . $map->file;
    if (!is_file($src)) {
        
       echo '<span style="color: red; font-weight: bold;">Not found: '. $src . '</span><br/>';
       continue;
    }
            
    $tempDir = 'upload_'. substr(uniqid(), -6);
    echo "make temp dir: " . $tempDir . '<br>'; 
    mkdir($tempDir);
        
    $zip = new ZipArchive;
    if( true !== ( $err = $zip->open($src) ) )
    {
       echo 'could not open: ' . $src . '('. $err . ')<hr/>';
    }
    if( true !== ( $err = $zip->extractTo($tempDir) ) )
    {
        echo 'could not extract to: '. $tempDir . '('. $err . ')<hr/>';
    }
        
    $maps = array();
    $mapsDir = $tempDir . DIRECTORY_SEPARATOR . 'maps';
    if(is_dir($mapsDir))
    {
        $dirHandle = opendir($mapsDir);
        while( $file = readdir($dirHandle) )
        {
            if( substr( $file, -4 ) == '.bsp' )
            {
                $file = new Racenet_File_Bsp($mapsDir . DIRECTORY_SEPARATOR . $file );
                $file->setViewFilter("/^weapon_.+/");
                echo $map->file . ': <pre>';
                $items = $file->getData();
                if (is_array($items)) {
                    
                    foreach ($items as $item) {
                        
                        if (Doctrine_Query::create()
								->from('MapItem')
								->where('map_id = ?', $map->id)
								->andWhere('item = ?', $item->classname)
								->count())
                           continue;
                        
						$mapItem = new MapItem;
						$mapItem->map_id = $map->id;
						$mapItem->item = $item->classname;
						$mapItem->save();
                    }
                }
                echo '</pre><hr/>';
            }
        }
        closedir($dirHandle);
    }
    
    Racenet_File::delete($tempDir, Racenet_File::DEL_RECURSIVE);
}
