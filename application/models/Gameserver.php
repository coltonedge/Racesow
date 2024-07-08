<?php

class Gameserver
{
    /**
     * Gameserver ID
     *
     * @var string, usually the gameservers port
     */
    private $_id;
    
    /**
     * Config object
     *
     * @var Zend_Config
     */
    private $_config;
    
    /**
     * Gameserver data
     *
     * @var array
     */
    private $_data = array();
    
    /**
     * Constructor
     *
     * @param Zend_Config $config
     */
    public function __construct(Zend_Config $config, $serverId)
    {
        $this->_config = $config;
        $this->_id = $serverId;
        
        if (!$this->_config->get($this->_id)) {
            
            require_once 'Racenet/Model/Exception.php';
            throw new Racenet_Model_Exception("No server config found with id \"$this->_id\"");
        }
        
        $this->_data = $this->_config->get($this->_id)->toArray();
        $this->_data['id'] = $this->_id;
        $this->_data['players'] = array();
        $this->_data['map'] = new stdClass;
        
        
        $result =  $this->qstat();
        if ($result) {
            
            $this->parse($result);
        }
        
        // TEMP:
        
        if ($this->rcon) {
        
        
            $command = 'cd /home/racesow/kkrcon && ./kkrcon.pl --type "old" --address "'. $this->ip .'" --port "'. $this->port .'" '.  $this->rcon .' status';
            // echo $command . '<hr>';
            $result = `$command`;
            
            if (preg_match_all('/ *\d+ +-?\d+ +\d+(.*?) +\d+ +\d+[\d\.]+:\d+/', $result, $regs)) {
            
                foreach ($regs[1] as $nickname) {
                
                    if ($player = Doctrine::getTable('Player')->findOneBySimplified(trim($nickname))) {
                        
						$statCollection = array();
						if ($this->map && $this->map->id) {
							$statCollection = Doctrine::getTable('PlayerMap')
							   ->createQuery()
							   ->where('map_id = ?', $this->map->id)
							   ->addWhere('player_id = ?', $player->id)
							   ->execute();
                        }
						
                        if (count($statCollection)) {
                            
                            $player->mapValue('time', $statCollection[0]->time);
                        }

                    } else {
                    
                        $player = new stdClass;
                        $player->name = $nickname;
                    }
                    
                    $this->_data['players'][] = $player;
                }
            }
        }
    }
    
    /**
     * Magic getter
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->_data[$key])) {
            
            return $this->_data[$key];
            
        } else switch($key) {
            
            case 'password':
            case 'levelshot':
                $this->_data[$key] = $this->{'_get'. ucfirst($key)}();
                return $this->_data[$key];
                
            case 'data':
                return $this->_data;
        }
        
        return null;
    }

    /**
     * Execute an remote console command on the gameserver
     *
     * @param string $srvCmd
     * @return string
     */
    public function rcon($cmd)
    {
        $cliCmd = 'cd /home/warsow/kkrcon/ && ./kkrcon.pl --address '. $this->ip .' --port '. $this->port .' --type "old" '. $this->rcon .' "'. $cmd .'"';
        return `$cliCmd`; 
    }
    
    /**
     * Query server using qStat
     *
     * @return string
     */
    public function qstat()
    {
        $cmd = 'quakestat -warsows '. $this->ip .':'. $this->port .' -R -P';
        $result = `$cmd`;
        return $result;
    }
    
    /**
     * Parse qstat query result
     * 
     */
    protected function parse($data)
    {
        $lines = explode("\n", $data);
        
        foreach ($lines as $line) {
        
            if (preg_match('@([^ ]+) +(\d+ */ *\d+) +([^ ]+) +(\d+ */ *\d+) +([^ ]+) +(.+)?@', $line, $hits)) {
                
                $this->_data['numPlayers'] = $hits[2];
                if (array_key_exists(6, $hits)) {
                
                    $this->_data['hostname'] = $hits[6];
                    
                } else if (array_key_exists('altname', $this->_data) && !empty($this->_data['altname'])) {
                
                    $this->_data['hostname'] = $this->_data['altname'];
                }
                
                if (count($mapCollection = Doctrine::getTable('Map')->findByName($hits[3]))) {
                    
                    $this->_data['map'] = $mapCollection[0];
                }
                
            } else if (preg_match('@(-*\d+) +frags +team#(\d+) +(\d+)ms +(.+)@', $line, $hits)) {
                
                $player = new stdClass;
                $player->score = $hits[1];
                $player->team = $hits[2];
                $player->ping = $hits[3];
                $player->name = $hits[4];
                $player->id = null;
                
                if (count($playerCollection = Doctrine::getTable('Player')->findByName($player->name))) {
                    
                    $player->id = $playerCollection[0]->id;
                    
                    $statCollection = Doctrine::getTable('PlayerMap')
                       ->createQuery()
                       ->where('map_id = ?', $this->map->id)
                       ->addWhere('player_id = ?', $player->id)
                       ->execute();
                       
                    if (count($statCollection)) {
                        
                        //$player->time = $statCollection[0]->time;
                    }
                }
                
                $this->_data['players'][] = $player;
            }

            if (preg_match('@g_gametype=([^,]+)@', $line, $hits)) {
                
                $this->_data['gametype'] = $hits[1];
            }   
            
            if (preg_match( '@g_match_time=([^,]+)@', $line, $hits)) {
                
                $this->_data['matchtime'] = $hits[1];
            }
        }
    }
    
    /**
     * Get the password for the server
     *
     * @return string
     */
    protected function _getPassword()
    {
        $filename  = $this->_config->path->warsow->server . $this->_config->path->warsow->mod .'/cfgs/port_'. $this->port .'_password.cfg';
        if (is_file($filename)) {

            
            $content = Racenet_File::read( $filename );
            if( preg_match( '/^set password "*(.*?)"*$/i', $content, $hit ) ) {
                
                return $hit[1];
            }    
        }
        
        return "";
    }
    
    /**
     * Get levelshot source
     *
     * @return string
     */
    protected function _getLevelshot()
    {
        $defaultSrc = '/gfx/levelshots/medium/nolevelshot.jpg';
        if (!isset($this->map->name)) {
            
            return $defaultSrc;
        }
        
        $levelshotPath = PATH_HTDOCS .'/gfx/levelshots/medium/'. strtolower($this->map->name) .'.jpg';
        $levelshotSrc = '/gfx/levelshots/medium/'. urlencode(strtolower($this->map->name)) .'.jpg';
        
        return (is_file($levelshotPath)) ? $levelshotSrc : $defaultSrc;
    }
}