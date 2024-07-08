<?php

/**
 * Controller for the Homepage
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class Admin_MapsController extends Racenet_Controller_Action
{
    /**
     * Define acl for the controller
     *
     */
    protected $_acl = array(
        "controller" => AclRacenet::ADMIN_MAPS,
        "forward" => array("index", "application")
    );

    /**
     * indexAction
     *
     */
    public function indexAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $this->layout->disableLayout();
        }
        
        
        //echo "temporarily disabled...";
        //$this->_helper->viewRenderer->setNoRender(true);
        
        // for levelshots
        $imgPath = PATH_HTDOCS . DS .'gfx' . DS . 'levelshots' . 'thumbs' . DS;
        $imgExt = '.jpg';
        
        $mapsModel = new MapAdminOld;
        
        // check number of items to display
        if (!is_numeric( $this->_getParam("num") ) || $this->_getParam("num") < 1) {
            $this->_setParam("num", 20);
        }
        
        // compute request
        $this->_setParam("skip", $skip = max(0, (integer)$this->_getParam("skip")));
        $this->_setParam("page", $page = max(0, (integer)$this->_getParam("page")));
        $search = $this->_getParam("search");
        $this->_setParam("hl", $highlight = ($search ? $search : $this->_getParam("hl")));
        
        $this->view->skip = $skip;

        // request
        $order = $this->_getParam("order");
        $dir = $this->_getParam("dir");
        $filter = $this->_getParam("filter");
        
        // status checkboxes logic
        
        if (!$this->_getParam("snew") &&
            !$this->_getParam("senabled") &&
            !$this->_getParam("sdisabled")) {
            
            $this->_setParam("snew", 1);
            $this->_setParam("senabled", 1);
            $this->_setParam("sdisabled", 1);
        }
        
        $stati = array();
        $availableStati = array('new', 'enabled', 'disabled');
        foreach ($availableStati as $status) {
            
            if ($this->_getParam("s". $status) == 1) {
                
                $stati[] = $status;
                
            } else if($this->_getParam("s". $status) == 0 && is_numeric($this->_getParam("s". $status))) {
                
                foreach ($availableStati as $otherStatus) {
                    
                    if ($this->_getParam("s". $otherStatus) != 0 || !is_numeric($this->_getParam("s". $otherStatus))) {
                        
                        $stati[] = $otherStatus;
                    }
                }
            }
        }
		
        foreach ($stati as $status) {
            
            $this->view->{"s". $status} = 1;
        }
        
        // race/freestyle filter
        
        if (!is_numeric($this->_getParam("trace")) &&
            !is_numeric($this->_getParam("tfs"))) {
            
            $this->_setParam("trace", 1);
            $this->_setParam("tfs", 1);
        }
        
        $freestyle = array();
        
        if ($this->_getParam("trace")) {
            
            $freestyle[] = '0';
            $freestyle[] = 'false';
        }
        
        if ($this->_getParam("tfs")) {
            
            $freestyle[] = '1';
            $freestyle[] = 'true';
        }
        
        foreach ($freestyle as $status) {
            
            if ($status == '1') {
                
                $this->view->tfs = 1;
                
            } else if ($status == '0') {
                
                $this->view->trace = 1;
            }
        }
        
        // get maps
        $items = $mapsModel->getItems($order, $dir, $filter, true, $freestyle, $stati);
        
        // find the page by matching mapnames when junmp
        if ($search && !$page) {
            $n = 0;
            $page = 0;
            $onPage = array();
            $matchedItems = 0;
            $matchedPages = 0;
            $lastMatchedPage = 0;

            foreach ($items as &$item) {
                
                $item = (array)$item;
                
                if (!($n++ % $this->_getParam("num"))) {
                    ++$page;
                }

                $item['isHighlighted'] = !empty($search) && preg_match( "/.*". $search .".*/i", $item['name'] );
                if ($item['isHighlighted']) {
                    
                    $matchedItems++;
                    
                    if (!isset($onPage[$page])) {
                        $onPage[$page] = 0;
                    }
                    
                    $onPage[$page]++;
                    
                    if ($matchedPages == $skip) {
                        
                        $this->view->page = $page;
                        $this->_setParam("page", $page);
                        
                    } else if( $matchedPages < $skip ) {
                        
                        $this->view->prevSkipPage = $page;
                        
                    } else if(!$this->view->nextSkipPage && $matchedPages > $skip) {
                        
                        $this->view->nextSkipPage = $page; 
                    }
                    
                    if( $page > $lastMatchedPage) {
                        
                        // the last matched page before the current one
                        $lastMatchedPage = $page;
                        $matchedPages++;
                    }
                    
                    // this will become the last matched page at all
                    $this->view->lastMatchedPage = $page;
                }
            }

            $from = 0;
            foreach ($onPage as $i => $num) {
                
                if ($i < $this->view->page) {
                    
                    $from += $num;
                }
            }
            
            $this->view->showFrom = $from + 1;      
            $this->view->showTo = $from + $onPage[$this->view->page];
            $this->view->matchedItems = $matchedItems;
        }
        
        $adapter = new Zend_Paginator_Adapter_Array($items);
        
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage($this->_getParam("num"));
        $paginator->setCurrentPageNumber($this->_getParam("page"));
        
        $maps = $paginator->getIterator();
        $position = max(0,$this->_getParam("num") *($this->_getParam("page")-1));
        
        foreach ($maps as &$map) {
            
            $map = (array)$map;
            
            if ($map['status'] == 'new') {
                
               $map['name'] = /*$map['file'] =*/ basename($map['name']);
            }
            
            $map['position'] = ++$position;
            
            if (!empty($highlight)) {
                
                $map['isHighlighted'] = preg_match( "/.*". $highlight .".*/i", $map['name'] );
            }
            
            $raceTime = new Racenet_Filter_Racetime;
            $raceTime->setFormat(Racenet_Filter_Racetime::FORMAT_SHORTTEXT)
                     ->setInputUnit(Racenet_Filter_Racetime::MS_MILLI)
                     ->setTrimZeros(true);
            
            $map['playtime'] = $raceTime->filter( $map['playtime'] );
            
            if( is_file( $imgPath . $map['name'] . $imgExt ) ) {
                
                $map['image'] = urlencode( $map['name'] );
                
            } else {
                
                $map['image'] = 'nolevelshot';
            }
            
            $mapItems = new MapItemsTableOld;

            $select = $mapItems->select()
                               ->from("map_item", "item")
                               ->where("map_id = ". $map["id"]);
            $map["items"] = $mapItems->fetchAll($select)
                                     ->toArray();
        }
        
        $this->view->search = $search;
        $this->view->filter = $filter;
        $this->view->highlight = $highlight;
        $this->view->paginator = $paginator;
    }
    
    /**
     * Saves a map
     *
     */
    public function saveAction()
    {
        $status = $this->_getParam('status');
        $oldStatus = $this->_getParam('oldStatus');
        $fs = $this->_getParam('freestyle');
        $mapId = (integer)$this->_getParam('id');
        
        if (!$mapId ||
            !$this->getRequest()->isPost() ||
            !in_array($status, array('new', 'enabled', 'disabled')) ||
            !in_array($fs, array('1', '0'))) {
                
            throw new Exception('Illegal call!');    
        }

        // ignore_user_abort(true);
        
        $map = Doctrine::getTable('Map')->find($mapId);
        
        // a freshly uploaded maps is added, so compute all required stuff
        if ($oldStatus == 'new') {

            if (!is_file($map->file)) {
                
                $map->file = PATH_HTDOCS . DS . 'upload' . DS . 'maps' . DS . $map->file;
            }
            $uploadPath = $map->file;
            $maps = $this->_getPk3MapInfo($map);
            
            foreach ($maps as $map) {
                
                $map['status'] = $status;
                $map['freestyle'] = $fs;
                
                if (isset($map['author'])) {
                    
                    if (!$mapper = Doctrine::getTable('Mapper')->findOneByName($map['author'])) {
                        
                        $mapper = new Mapper;
                        $mapper->name = $map['author'];
                        $mapper->created = new Doctrine_Expression('NOW()');
                        $mapper->save();
                    }
                    
                    $map['mapper_id'] = $mapper->id;
                }
                
                if (!$mapRecord = Doctrine::getTable('Map')->find($map['id'])) {
                    
                    $mapRecord = new Map;
                    
                }
                    
                $mapRecord->fromArray($map);
                   $mapRecord->save();
                   
                   // save map entities
                   if (count($map['items'])) {
                       
                       foreach ($map['items'] as $item) {
                          
                        if (!$mapItem = Doctrine::getTable('MapItem')->createQuery()->where('item = ?', $item)->andWhere('map_id = ?', $map['id'])) {
                        
                            $mapItem = new MapItem;
                            $mapItem->Map = $mapRecord;
                            $mapItem->item =  $item;
                            $mapItem->save();
                        }
                       }
                   }
            }
            
            $pk3Path = $this->config->path->warsow->pk3s . DS . $map['file'];
			$gamePath = $this->config->path->warsow->data . DS . $this->config->path->warsow->mod . DS . $map['file'];
			if ($pk3Path != $uploadPath) {
			
				exec("mv $uploadPath $pk3Path");
				exec("chown warsow:warsow $pk3Path");
				exec("ln -s $pk3Path $gamePath");
				exec("chown racesow:warsow $gamePath");
			}
        
        } else {
            
            if (!$map = Doctrine::getTable('Map')->find($mapId)) {

                throw new Exception("can not update map '". $mapId ."' as it does not exists");
            }

            /*
            // clear points on player_map when a map is beeing disabled (can be restored from races)
            if ($status == 'disabled') {
                
                Doctrine_Query::create()
                    ->delete()
                    ->from('PlayerMap')
                    ->where('map_id')
                    ->
            }
            */
            
            $map->status = $status;
            $map->freestyle = $fs;
            $map->save();
        }
        
        die(json_encode("ok"));
    }
    
    protected function _error($e, $v)
    {
    }
    
    /**
     * find mappers/authors, do not use :P
     *
     * @return void
     */
    public function recomputeAction()
    {
        // $map = Doctrine::getTable('Map')->find((integer)$this->_getParam('id'));
        
        $maps = Doctrine::getTable('Map')->createQuery()->where('id >= 600 AND id < 700')->execute();
        
        foreach ($maps as $map) {
        
            if (!is_file($map->file)) {
                
                $map->file = $this->config->path->warsow->data . $this->config->path->warsow->mod . DS . $map->file;
            }
            
            $maps = $this->_getPk3MapInfo($map);
            
            foreach ($maps as $map) {
                
                if (isset($map['author'])) {
                    
                    if (!$mapper = Doctrine::getTable('Mapper')->findOneByName($map['author'])) {
                        
                        $mapper = new Mapper;
                        $mapper->name = $map['author'];
                        $mapper->created = new Doctrine_Expression('NOW()');
                        $mapper->save();
                    }
                    
                    $map['mapper_id'] = $mapper->id;
                }
                
                if (!$mapRecord = Doctrine::getTable('Map')->find($map['id'])) {
                    
                    $mapRecord = new Map;
                }
                    
                $mapRecord->fromArray($map);
                   $mapRecord->save();
                   
                   // save map entities
                   if (count($map['items'])) {
                       
                       foreach ($map['items'] as $item) {
                          
                        if (!$mapItem = Doctrine::getTable('MapItem')->createQuery()->where('item = ?', $item)->andWhere('map_id = ?', $map['id'])) {
                        
                            $mapItem = new MapItem;
                            $mapItem->Map = $mapRecord;
                            $mapItem->item =  $item;
                            $mapItem->save();
                        }
                       }
                   }
            }
        }
        
        die("ok");
    }
    
    /**
     * Unpack a map
     *
     * @param Map $map
     * @return array
     */
    protected function _getPk3MapInfo($map)
    {
        if (!is_file($map->file)) {
        
			$map->file = $this->config->path->warsow->data . $this->config->path->warsow->mod . DS . basename($map->file);
		
			if (!is_file($map->file)) {
		
				echo '"'. $map->file .'" is no file'; flush();
				return false;
			}
        }
        
        if (function_exists('sys_get_temp_dir')) {
            
            $tempDir = sys_get_temp_dir();
        
        } else {
            
            $tempDir = '/tmp/';
        }
        
        $tempDir = preg_replace('/\/$/', '', $tempDir);
        $tempDir .= '/upload_'. substr(uniqid(), -6);
        
        mkdir($tempDir);
        
        $zip = new ZipArchive;
        if (true !== ($err = $zip->open($map->file))) {
            
            $this->_error($err, $map->file);
            echo 'could not open' . $map->file . '<hr>'; flush();
            return false;
        }
        
        if (true !== ($err = $zip->extractTo($tempDir))) {
            
            $this->_error($err, $map->file);
            echo 'could not extract ' .$map->file . '<hr>'; flush();
            return false;
        }

        
        // find maps in pk3
        $mapsDir = $tempDir . DS . 'maps';
        $scriptsDir = $tempDir . DS . 'scripts';
        $picsDir = $tempDir . DS . 'levelshots';
        
        $numMaps = 0;
        $maps = array();
        
        if (is_dir($mapsDir)) {
            
            $dirHandle = opendir($mapsDir);
            while ($file = readdir($dirHandle)) {
                
                if (substr($file, -4) == '.bsp' && is_object($map)) {
                    
                    $map = $map->toArray();
                    $map['name'] = preg_replace("/\.bsp$/", "", $file);
                    $map['file'] = basename($map['file']);

                    /*
                     * if there are multiple maps in one pk3, remove the ID from
                     * all maps without the first one. for insert/update decision.
                     * after upload the pk3 is always a single row in the mapsTable.
                     * after unpacking it may expand to multiple rows, analog to the
                     * number of maps found in the pk3
                     */ 
                    if ($numMaps++) {
                        $map['id'] = 0;
                    }
                    
                    // read entities from bsp
                    $map['items'] = array();
                    $bsp = new Racenet_File_Bsp( $mapsDir . DS . $file );
                    $bsp->setViewFilter("/^weapon_.+/");
                    if ($entities = $bsp->getData()) {
                        
                        foreach ($entities as $entity) {
                            
                               if (!in_array($entity->classname, $map['items'])) {
                                
                                $map['items'][] = $entity->classname;
                            }
                        }
                    }
                    
                    // read .defi or .arena file
                    $defiFile = null;
                    if (is_file($scriptsDir . DS . $map['name'] .'.defi')) {
                        
                        $defiFile =  $scriptsDir . DS . $map['name'] .'.defi';
                        
                    } else if (is_file($scriptsDir . DS . $map['name'] .'.arena')) {
                        
                        $defiFile = $scriptsDir . DS . $map['name'] .'.arena';
                    }

                    if ($defiFile) {
                        
                        $defiFile = new Racenet_File_Defi($defiFile);
                        $defiData = $defiFile->getData(); 
                        
                        if (isset($defiData['map'])) {
                            
                            $map['name'] = $defiData['map'];
                        }
                        
                        if (isset($defiData['longname'])) {
                            
                            $map['longname'] = $defiData['longname'];
                        }
                        
                        if (isset($defiData['author'])) {
                            
                            $map['author'] = $defiData['author'];
                        }
                    }
                    
                    /*
                     * levelshots
                     * FIXME: they should not be extracted from here,
                     * but the pk3 is already opened right here
                     */
                    if (is_dir($picsDir)) {
                        
                        $pic = null;
                        $picHandle = opendir($picsDir);
                        while ($file = readdir($picHandle)) {
                            
                            if (preg_match("/^". $map['name'] ."\..+$/i", $file)) {
                                
                                $pic = $file;
                                break;
                            }
                        }
                        
                        if ($pic) {
                            
                            $mediumPic = PATH_HTDOCS . 
                               DS .'gfx'.
                               DS .'levelshots'.
                               DS .'medium'.
                               DS . strtolower($map['name']) .'.jpg';
                      
                            if (!is_file($mediumPic)) {
                                $cmd = 'convert '. $picsDir . DS . $pic .' -resize 350x263 -compress JPEG '. $mediumPic. ' 2>&1';
                                `$cmd`;
                            }
                            
                            $thumbPic = PATH_HTDOCS . 
                               DS .'gfx'.
                               DS .'levelshots'.
                               DS .'thumbs'.
                               DS . strtolower($map['name']) .'.jpg';
                               
                            if (!is_file($thumbPic)) {
                                $cmd = 'convert '. $picsDir . DS . $pic .' -resize 75x56 -compress JPEG '. $thumbPic. ' 2>&1';
                                `$cmd`;
                            }
                        }
                    }
                    
                    $maps[] = $map;
                }
            }
            
            closedir($dirHandle);
        
        }
        
        Racenet_File::delete($tempDir, Racenet_File::DEL_RECURSIVE);

        return $maps;
    }
    
    /**
     * noacessAction
     *
     */
    public function noaccessAction()
    {
    }
}
