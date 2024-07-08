<?php

class MapAdmin extends Racenet_Ranking
{
    /**
     * Maptypes
     *
     * @var object
     */
    protected $_types;
    /**
     * Mapstates
     *
     * @var object
     */
    protected $_states;
    
    /**
     * Weapons
     *
     * @var object
     */
    protected $_weapons;
    
    /**
     * Mapping for short warsow weapon names to
     * long real item names from quake 2
     *
     * @var array
     */
    protected $_weaponNamesMapping;
    
    /**
     * Mapping for weapons, type 1
     *
     * @var array
     */
    protected static $tmpDef = array(
    
        'weapon_rocketlauncher' => 0,
        'weapon_plasmagun' => 1,
        'weapon_grenadelauncher' => 2,
        'weapon_railgun'=> 3,
        'weapon_lightning' => 4,
        'weapon_lasergun' => 4,
        'weapon_shotgun' => 5,
        'weapon_machinegun' => 6
    );

    /**
     * Mapping for weapons, type 2
     *
     * @var array
     */
    protected static $tmpDef2 = array(
    
        'rl' => 0,
        'pg' => 1,
        'gl' => 2,
        'eb' => 3,
        'lg' => 4,
        'rg' => 5,
        'mg' => 6
    );
    
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
        $this->_order = 'playtime';
        $this->_defaultOrder = 'playtime';
        $this->_searchColumn = 'name';
        $this->_dir = 'DESC';
        $this->_defaultDir = 'DESC';
        $this->_secondOrder = 'races';
        
        $this->_columns = array(
        
            'id',
            'name',
            'simplified',
            'points',
            'races',
            'maps',
            'diff_points',
            'position',
            'playtime',
            'awardval'
         );
         
        $this->_ascColumns =  array(
        
            'id',
            'name',
            'longname'
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
        
        $this->_weaponNamesMapping = array(
    
            'rl' => 'weapon_rocketlauncher',
            'pg' => 'weapon_plasmagun',
            'gl' => 'weapon_grenadelauncher',
            'eb' => 'weapon_railgun',
            'lg' => 'weapon_lightning',
            'rg' => 'weapon_shotgun',
            'mg' => 'weapon_machinegun'
        );
    }

    /**
     * Getter for query parts
     *
     */
    protected function _getQueryParts()
    {
        $query = array(
            'from' => 'map AS m',
            'select' => array('m.*', '0 AS position'), 
            'where' => array('status = \'enabled\''),
            'orderby' => array($this->_order .' '. $this->_dir),
        );
        
        // apply weapon filter to the query
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
        
        // apply status filter to the query
        foreach ($this->_states as $status) {
            
            
        }
        
        // apply type filter to the query
        $typeFilter = array();
        foreach ($this->_types as $type => $status) {
            
            if ($status) {
                
                $typeFilter[] = $type == 'fs' ? 'true' : 'false';
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
        
        $item->rating = rand(1, 5);
        $item->ratings = rand(5, 100);
    }
    
    /**
     * Setter to filter by weapons
     *
     * @param mixed $weapon
     */
    public function filterWeapon($weapon)
    {
        if (isset($this->_weapons->$weapon)) {
        
            $this->_weapons->$weapon = true;
        }
    }
    
    /**
     * Setter for filter by type
     *
     * @param mixed $type
     */
    public function filterType($type)
    {
        if (isset($this->_types->$type)) {
        
            $this->_types->$type = true;
        }
    }
    
    /**
     * Setter for filter by status
     *
     * @param mixed $statuss
     */
    public function filterStatus($status)
    {
        if (isset($this->_states->$status)) {
        
            $this->_states->$status = true;
        }
    }
    
    /**
     * Getter for form-action
     *
     * @param Zend_View $view
     * @return string URL
     */
    public function getFormAction($view)
    {
        return $view->url(array(
           'module' => 'default',
           'controller' => 'maps',
           'action' => 'index'
        ), null, true);
    }
}
