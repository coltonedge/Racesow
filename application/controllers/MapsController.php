<?php

/**
 * MapsController
 *
 */
class MapsController extends Racenet_Controller_Action
{
    public function demoAction()
    {
         die($this->getUrl('http://1337demos.com/maps/'. urlencode(strtolower($this->_getParam('name'))) .'/best_demo.html'));
    }


    /**
     * List all maps
     *
     */
    public function indexAction()
    {
		$this->view->headTitle("Maps");
        if ($this->getRequest()->isXmlHttpRequest()) {

            $this->layout->disableLayout();
        }
        
        $this->mapsRanking();
    }

    /**
     * xmlAction - xml layout, no highlight
     *
     */
    public function xmlAction()
    {
		$this->view->headTitle("Maps");
        $this->layout->setLayout('xml');
        $this->mapsRanking();
    }

    public function mapsRanking()
    {
    	$ranking = MapRanking::getInstance()
            ->setPage($this->_getParam('page'))
            ->setItemsPerPage(min(100, max(0, (integer)$this->_getParam('num', 20))))
            ->setOrder($this->_getParam('order'))
            ->setDir($this->_getParam('dir'))
            ->setFilter($this->_getParam('filter'))
            ->setHighlight($this->_getParam('highlight'));


        if ($weapons = $this->_getParam('weapons')) {
            
            if (!is_array($weapons)) {
                
                $weapons = preg_split('/\W/', $weapons);
            }
            
            foreach ($weapons as $weapon) {
                
                $ranking->filterWeapon($weapon);
            }
        }
            
        if ($types = $this->_getParam('types')) {
            
            if (!is_array($types)) {
                
                $types = preg_split('/\W/', $types);
            }
            
            foreach ($types as $type) {
                
                $ranking->filterType($type);
            }
        }
            
        $this->view->ranking = $ranking->compute();
    }
    
    /**
     * Try to load an url using curl
     *
     * @param string $url
     * @return String
     */
    public function getUrl($url)
    {
        if (!function_exists('curl_init')) {
        	
        	return 'The page you were looking for doesn\'t exist';
        }
        
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.warsow-race.net/");
        curl_setopt($ch, CURLOPT_USERAGENT, "warsow-race.net");
        curl_setopt($ch, CURLOPT_HEADER, 0); // 0 = yes
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    /**
     * Single map details
     *
     */
    public function detailsAction()
    {
        $mapId = (integer)$this->_getParam("id");
        if (!$map = Doctrine::getTable('Map')->find($mapId)) {
             
            throw new Exception("The requested map does not exist or is disabled.");
        }
       
		$this->view->headTitle("Map")
			->headTitle($map->name);
	   
        $ratings = Doctrine::getTable('MapRating')
            ->createQuery()
            ->where('map_id = ?', $map->id)
            ->andWhere('user_id = ?', RacenetAccount::getInstance()->user_id)
            ->execute();
            
        if ($ratings->count()) {
            
            $this->view->userRating = $ratings->getFirst()->value;
        }

        $this->view->map = $map;
        
        $this->rankingAction();
    }
    
    /**
     * rankingAction
     *
     */
    public function rankingAction()
    {
    	$mapId = (integer)$this->_getParam("id");
    	$ranking = MapPlayerRanking::getInstance($mapId)
            ->setPage($this->_getParam('page'))
            ->setItemsPerPage($this->_getParam('num', 20))
            ->setOrder($this->_getParam('order'))
            ->setDir($this->_getParam('dir'))
            ->setFilter($this->_getParam('filter'))
            ->setHighlight($this->_getParam('hl'));
        
        $this->view->ranking = $ranking->compute();
    }

    /**
     * uploadAction
     *
     */
    public function uploadAction()
    {
        switch ($this->_getParam('msg')) {
             
            case 'done';
				$this->view->messageColor = '#00ff00';
				$this->view->message = 'Your map was uploaded. An admin is going to test and add your map to the pool.';
				break;

            case 'err';
				$this->view->messageColor = '#ff0000';
				$this->view->message = 'An unknown error occured. Please try again or contact and admin.';
				break;
        }

        $props = array(
            "uploadDestination" => PATH_HTDOCS . DS .'upload'. DS .'maps',
        );
        
        $form = new Form_Mapupload($props);
        $form->setAction('/maps/upload/')
        ->setMethod('post');
         
        if ($this->getRequest()->isPost()) {
             
            if ($form->isValid($this->_getAllParams())) {
             
                if ($form->pk3edmap->receive()) {
				
					if (function_exists('sys_get_temp_dir')) {
						
						$tempDir = sys_get_temp_dir();
						
					} else {
						
						$tempDir = '/tmp';
					}
					
					$tempDir = preg_replace('/\/$/', '', $tempDir);
					$tempDir .= '/racenet_upload_'. substr(uniqid(), -6);
					mkdir($tempDir);
        
					$zip = new ZipArchive;
					if (true !== ($err = $zip->open($form->pk3edmap->getFileName('pk3edmap')))) {
						
						$this->_redirect('/maps/upload/msg/err/');
					}
					
					if (true !== ($err = $zip->extractTo($tempDir)))  {
						
						$this->_redirect('/maps/upload/msg/err/');
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
						
						$this->_redirect('/maps/upload/msg/err/');
					}

					$updated = false;
					foreach ($maps as $file => $null) {
						
						$mapName = preg_replace("/\..+$/", "", $file);
						if ($map = Doctrine::getTable('Map')->findOneByName($mapName)) {
							
							if (empty($map->file)) {
							
								$map->file = $form->pk3edmap->getFileName('pk3edmap');
								$map->save();
								$updated = true;
							}
						}
					}
				
					if (!$updated) {
					
						$map = new Map;
						$map->file =  $form->pk3edmap->getFileName('pk3edmap');
						$map->freestyle = $form->getValue("freestyle");
						$map->status = 'new';
						$map->save();
						
						chmod($map->file, 0775);
					}
				
                    // TODO: send email to map-admins
                    // $mapAdmins = AclRacenet::getUsers(AclRacenet::ADMIN_MAPS);

                     $this->_redirect('/maps/upload/msg/done/');

                } else {

                    $this->_redirect('/maps/upload/msg/err/');
                }
                
            } else {
            
                // $this->_redirect('/maps/upload/msg/formerr/');
                echo "SHIT";
            }
            
            
        }

        $this->view->form = $form;
    }

    /**
     * Action for upload Progress
     *
     */
    public function uploadprogressAction() {
         
        die(json_encode(uploadprogress_get_info($this->_getParam("uploadId"))));
    }
     
    /**
     * Action for downloading maps
     *
     */
    public function logdownloadAction()
    {
        $mapId = (integer)$this->_getParam("id");
        if(!$map = Doctrine::getTable('Map')->find($mapId)) {
            
            throw new Racenet_Exception("no map found with id ". $mapId);
        }

		$map->downloads++;
		$map->save();
		
        $log = new LogDownload;
        $log->map_id = $map->id;
        $log->created = new Doctrine_Expression("NOW()");
        $log->save();		

        exit;
    }

    /**
     * Action for submitting ratings
     * User must be logged in and must have played the map.
     *
     */
    public function rateAction()
    {
        $user = RacenetAccount::getInstance();
        
        if (!(integer)$user->user_id || $user->user_id == -1) {

            $result = array("status" => false, "message" => "You need to be logged in to rate a map.");

        } else {

            $mapId = (integer)$this->_getParam("id");
            if (!$map = Doctrine::getTable('Map')->find($mapId)) {

                $result = array("status" => false, "message" => "The map you want to rate was not found.");
                 
            } else {
        
                $playCheck = Doctrine::getTable('PlayerMap')
                    ->createQuery()
                    ->where('player_id = ?', $user->IngameLinkage->Player->id)
                    ->addWhere('map_id = ?', $map->id)
                    ->addWhere('playtime > 0')
                    ->limit(1)
                    ->execute();
                
                if (!$playCheck->count()) {
            
                    $result = array("status" => false, "message" => "race on $map->name before rating it!");

                } else {
                     
                    $result = array();
            
                    $value = min(5, max(1, (integer)$this->_getParam("value")));

                    $rateCheck = Doctrine::getTable('MapRating')
                        ->createQuery()
                        ->where('map_id = ?', $map->id)
                        ->addWhere('user_id = ?', $user->user_id)
                        ->limit(1)
                        ->execute();

                    if ($rateCheck->count()) {
                         
                        $rating = $rateCheck->getFirst();
                        $rating->changed = new Doctrine_Expression("NOW()");
                        $rating->value = $value;
                        $rating->save();
                        
                        $result["message"] = "Changed your rating for $map->name";

                    } else {

                        $rating = new MapRating;
                        $rating->value = $value;
                        $rating->map_id = $map->id;
                        $rating->user_id = $user->user_id;
                        $rating->created = new Doctrine_Expression("NOW()");
                        $rating->save();
                         
                        $result["message"] = "Added your rating for $map->name";
                    }

                    $mapRating = Doctrine::getTable('MapRating')
                       ->createQuery()
                       ->select("COUNT(user_id) AS ratings, AVG(value) AS rating")
                       ->where('map_id = ?', $map->id)
                       ->limit(1)
                       ->execute()
                       ->getFirst();

                    $map->ratings = $mapRating->ratings;
                    $map->rating = $mapRating->rating;
                    $map->save();

                    $result["status"] = true;
                    $result["html"] = $this->view->partial("rating-out.phtml", array("name" => str_replace('#', '23', $map->name), "rating" => round($map->rating) * 4, "disabled" => true, "info" => "($map->ratings rating". ($map->ratings != 1 ? 's' : '') .")"));
                }
            }
        }
        
        if ($this->getRequest()->isXmlHttpRequest()) {

            die(json_encode((object)$result));
             
        } else if (!$result['status']) {

            throw new Exception($result['message']);
             
        } else {
             
            $this->_redirect($this->view->url(array("action" => "details", "id" => $map->id, "value" => null)));
        }
    }
}