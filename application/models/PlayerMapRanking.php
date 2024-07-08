<?php

/**
 * List of all maps played by a specific player
 *
 */
class PlayerMapRanking extends MapRanking
{
    /**
     * Show only maps the player played
     *
     * @var unknown_type
     */
    protected $_playerId = 0;
    
    /**
     * Singleton instance
     *
     * @var Racenet_Ranking
     */
    protected static $__instance;
    
    /**
     * Get singleton instance
     *
     * @return Ranking
     * @return Racenet_Ranking
     */
    public static function getInstance()
    {
        if (!self::$__instance) {
            
            self::$__instance = new self;
        }
        
        return self::$__instance;
    }
    
    /**
     * Constructor replacement
     *
     */
    public function init()
    {
        $this->_order = 'points';
        $this->_defaultOrder = 'points';
        $this->_searchColumn = 'name';
        $this->_dir = 'DESC';
        $this->_defaultDir = 'DESC';
        $this->_secondOrder = 'races';
        
        $this->_columns = array(
        
            'id',
            'name',
            'longname',
            'races',
            'tries',
            'duration',
            'time',
            'pos',
            'playtime',
            'points',
            'created',
         );
         
        $this->_ascColumns =  array(
        
            'id',
            'name',
            'tries',
            'duration',
            'longname',
        );
        
         $this->_types = (object)array(
        
           'race' => false,
           'fs' => false,
        );
    
        $this->_weapons = (object)array(
        
           'rl' => false,
           'pg' => false,
           'gl' => false,
           'lg' => false,
           'rg' => false,
           'eb' => false,
        );
    }

    /**
     * Getter for query parts
     *
     */
    protected function _getQueryParts()
    {
        $query = array(
        
            'from' => 'player_map as p',
            'join' => array('map as m ON m.id = p.map_id'),
            'where' => array('player_id = '. $this->_playerId),
            'select' => array('m.id', 'm.name', 'm.longname', 'p.races', 'p.playtime', 'p.created','p.position as pos','p.points','p.time','p.tries'), 
            'orderby' => array($this->_order .' '. $this->_dir),
            //'groupby' => array('m.id')
        );
        
        $weaponFilter = array();        
        foreach ($this->_weapons as $type => $status) {
            
            if ($status) {

                $weaponFilter[] = $this->_weaponNamesMapping[$type];
            }
        }
        
        if (count($weaponFilter)) {
            
            // $weaponsPattern = array('_','_','_','_','_','_','_');
            $weaponsPattern = array(0,0,0,0,0,0,'_'); // last position is machinegun which is not included in warsow
            foreach ($weaponFilter as $itemName) {
            
                $weaponsPattern[self::$tmpDef[$itemName]] = 1;
            }
            
            $query['where'][] = "weapons LIKE '". join('', $weaponsPattern) ."'";
        }
        
        // apply type filters to the query
        $typeFilter = array();
        foreach ($this->_types as $type => $status) {
            
            if ($status) {
                
                $typeFilter[] = $type == 'fs' ? '1' : '0';
            }
        }
        if (count($typeFilter)) {
            
            $query['where'][] = "freestyle IN ('". join("','", $typeFilter) ."')";
        }
        
        return $query;
    }
    
    /**
     * Fet additional information for an item
     *
     * @param stdClass &$item
     * @return void
     */
    protected function _addAdditionalInformation(&$item)
    {
        // levelshots
        $thumbsUrl = '/gfx/levelshots/thumbs/';
        $thumbsPath = PATH_HTDOCS . str_replace('/', DS, $thumbsUrl);
        if (is_file($thumbsPath . strtolower($item->name) .'.jpg')) {
            
            $item->image = $thumbsUrl . urlencode(strtolower($item->name) .'.jpg');
            
        } else {
            
            $item->image = $thumbsUrl . 'nolevelshot.jpg';
        }

        // weapons
        $item->items = Doctrine::getTable('MapItem')->findByMapId($item->id);
    }
    
    /**
     * Setter to filter by player id
     *
     * @param integer $playerId
     * @return MapRanking
     */
    public function filterPlayer($playerId)
    {
        $this->_playerId = (integer)$playerId;
        return $this;
    }
    
    
    /**
     * Getter for form-action
     *
     * @param Zend_View $view
     * @return string URL
     */
    public function getFormAction($view)
    {
        return '/blah';
    }
}
