<?php

/**
 * Serverstats
 *
 */
class Serverstats
{
    /**
     * Singleton instance
     *
     * @var Serverstats
     */
    private static $_instance;
    
    /**
     * Singleton implementation
     *
     * @return Serverstats
     */
    public final static function getInstance()
    {
        if (!self::$_instance) {
            
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }
    
    /**
     * Singleton implementation
     *
     */
    private final function __construct()
    {
    }
    
    /**
     * Prevent cloning
     */
    private final function __clone()
    {
    }

    /**
     * read date/time of the first race. assume this
     * value as the time of the last stats reset
     *
     * @param $dateFormat    Formatstring for MySQL DATE_FORMAT()
     * @return string Formatted date/time string
     */
    public function getLastReset( $dateFormat = '%M %D %Y' )
    {
        if ($result = Doctrine_Query::create()
            ->select("DATE_FORMAT( created, '$dateFormat' )")
            ->from('Race')
            ->orderBy('created')
            ->limit(1)
            ->fetchOne())
        return $result->DATE_FORMAT;
    }
    
    /**
     * Read the number of players
     *
     * @param boolean $inactive Count players without any races?
     * @return string Formatted number of players
     */
    public function getNumPlayers($inactive = true)
    {
        $query = Doctrine::getTable('Player')
            ->createQuery()
            ->select('COUNT(id)')
            ->limit(1);
            
        if (!$inactive) {
            
            $query = $query->where('playtime');
        }
        
        if ($result = $query->fetchOne()) {
            
            return number_format($result->COUNT, 0, '', ',');
        }
    }
    
    /**
     * Get the number ob available maps
     *
     * @param boolean $freestyle Count freestylemaps?
     * @return string Formatted number  of maps
     */
    public function getNumMaps( $freestyle = false ) {
        
        if ($result = Doctrine::getTable('Map')
            ->createQuery()
            ->select('COUNT(id)')
            ->where("status = 'enabled'")
            ->addWhere("freestyle = '". ($freestyle ? '1' : '0') ."'")
            ->limit(1)
            ->fetchOne()) {
           
            return number_format($result->COUNT, 0, '', ','); 
        }
    }
    
    /**
     * Get the number of finished races
     *
     * @return string Formatted number of races
     */
    public function getNumRaces()
    {
        if ($result = Doctrine::getTable('Race')
            ->createQuery()
            ->select('COUNT(id)')
            ->limit(1)
            ->fetchOne()) {
            
            return number_format($result->COUNT, 0, '', ',');
        }
    }
    
    /**
     * Get the summarized time of all finished races
     *
     * @return string Formatted number of races
     */
    public function getRaceTime() {
        
        if ($result = Doctrine::getTable('Race')
            ->createQuery()
            ->select('SUM(time)')
            ->limit(1)
            ->fetchOne()) {
                
            $raceTime = new Racenet_Filter_Racetime;
            $raceTime->setFormat(Racenet_Filter_Racetime::FORMAT_SHORTTEXT)
                     ->setInputUnit(Racenet_Filter_Racetime::MS_MILLI)
                     ->setTrimZeros(true);
                     
            return $raceTime->filter($result->SUM);
        }
    }
    
    /**
     * Get the pure racing time
     *
     * @return string Formatted number of races
     */
    public function getRacingTime() {
        
        if ($result = Doctrine::getTable('PlayerMap')
            ->createQuery()
            ->select('SUM(racing_time)')
            ->limit(1)
            ->fetchOne()) {
                
            $raceTime = new Racenet_Filter_Racetime;
            $raceTime->setFormat(Racenet_Filter_Racetime::FORMAT_SHORTTEXT)
                     ->setInputUnit(Racenet_Filter_Racetime::MS_MILLI)
                     ->setTrimZeros(true);
                     
            return $raceTime->filter($result->SUM);
        }
    }
    
    /**
     * Get the overall number of tries
     *
     * @return integer
     */
    public function getNumTries() {
        
        if ($result = Doctrine::getTable('PlayerMap')
            ->createQuery()
            ->select('SUM(overall_tries)')
            ->limit(1)
            ->fetchOne()) {
                
            return number_format($result->SUM, 0, '', ',');
        }
    }
    
    /**
     * Get the taotal playtime
     *
     * @return string Formatted number of races
     */
    public function getPlayTime() {
        
       if ($result = Doctrine::getTable('Player')
            ->createQuery()
            ->select('SUM(playtime)')
            ->where('playtime')
            ->limit(1)
            ->fetchOne()) {
                
            $tmp = $result->SUM;
            $raceTime = new Racenet_Filter_Racetime;
            $raceTime->setFormat(Racenet_Filter_Racetime::FORMAT_SHORTTEXT)
                     ->setInputUnit(Racenet_Filter_Racetime::MS_MILLI)
                     ->setTrimZeros(true);
                     
            return $raceTime->filter($tmp);
        }
    }
    
    /**
     * Get the number of races today
     *
     * @return string
     */
    public function getNumRacesToday()
    {
        if ($result = Doctrine::getTable('Race')
            ->createQuery()
            ->select('COUNT(id)')
            ->where('CURDATE() = DATE(created)')
            ->limit(1)
            ->fetchOne()) {
        
            return  number_format($result->COUNT, 0, '', ',');
        }
    }
    
    /**
     * Get the number of races yesterday
     *
     * @return string
     */
    public function getNumRacesYesterday()
    {
        if ($result = Doctrine::getTable('Race')
            ->createQuery()
            ->select('COUNT(id)')
            ->where('SUBDATE(CURDATE(),1) = DATE(created)')
            ->limit(1)
            ->fetchOne()) {
        
            return  number_format($result->COUNT, 0, '', ',');
        }
    }
    
    /**
     * Read the number of players
     *
     * @param boolean $inactive Count players without any races?
     * @return string Formatted number of players
     */
    public function getNumberOfUsers()
    {
        if ($result = Doctrine::getTable('PhpbbUsers')
            ->createQuery()
            ->select('COUNT(user_id) - 1 as COUNT') // there is anonymous user
            ->limit(1)
            ->fetchOne()) {
            
            return number_format($result->COUNT, 0, '', ',');
        }
    }    
    
    /**
     * Read the number of players
     *
     * @param boolean $inactive Count players without any races?
     * @return string Formatted number of players
     */
    public function getNumberOfIngameLinkages()
    {
        if ($result = Doctrine::getTable('PlayerPhpbbuser')
            ->createQuery()
            ->select('COUNT(player_id)')
            ->limit(1)
            ->fetchOne()) {
            
            return number_format($result->COUNT, 0, '', ',');
        }
    }
    
    public function getNumberOfDownloads()
    {
        if ($result = Doctrine::getTable('LogDownload')
            ->createQuery()
            ->select('COUNT(*)')
            ->limit(1)
            ->fetchOne()) {
            
            return number_format($result->COUNT, 0, '', ',');
        }
    }
}

?>