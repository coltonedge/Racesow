<?php

class MapPlayerRanking extends Racenet_Ranking
{
    /**
     * Singleton instances
     *
     * @var array
     */
    protected static $__instances;
    
    /**
     * The map the ranking is for
     *
     * @var Map
     */
    protected $_map;
    
    /**
     * Get singleton instance
     *
     * @return MapPlayerRanking
     */
    public static function getInstance($mapId)
    {
        if (!self::$__instances[$mapId]) {
            
            self::$__instances[$mapId] = new self($mapId);
        }
        
        return self::$__instances[$mapId];
    }
    
    /**
     * Constructor
     *
     * @param integer $mapId
     */
    protected function __construct($mapId)
    {
        parent::__construct();
        
        $this->_map = Doctrine::getTable('Map')->find($mapId);
        
        $this->_order = 'time';
        $this->_defaultOrder = 'time';
        $this->_searchColumn = 'simplified';
        $this->_dir = 'DESC';
        $this->_defaultDir = 'DESC';
        
        $this->_columns = array(
        
            'time',
            'races',
            'tries',
            'duration',
            'points',
            'pos',
            'playtime',
            'name',
            'simplified',
            'created',
         );
         
        $this->_ascColumns =  array(
        
            'player_id',
            'name',
            'time',
            'tries',
            'duration',
            'position',
        );
    }

    /**
     * Getter for query parts
     *
     */
    protected function _getQueryParts()
    {
        $query = array(
            'from' => 'player_map AS pm',
            'join' => array('player AS p ON p.id = pm.player_id'),
            'select' => array('pm.*, pm.position as pos, p.name, p.simplified, p.id AS player_id', '0 AS position', 'pm.tries'), 
            'where' => array('pm.map_id = '. $this->map->id, 'pm.time'),
            'orderby' => array($this->_order .' '. $this->_dir),
        );

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
        if ($server = Doctrine::getTable('Server')->find($item->server_id)) {
        	
            $item->servername = $server->servername;
            $item->serverIdent = $server->ident;
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
