<?php

class PlayerRanking extends Racenet_Ranking
{   
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
        $this->_searchColumn = 'simplified';
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
            'awardval',
			'skill'
         );
         
        $this->_ascColumns = array(
        
            'id',
            'name',
            'simplified'
        );
    }

    /**
     * Getter for query parts
     *
     */
    protected function _getQueryParts()
    {
        return array(
            'from' => 'player',
            'select' => array('*', '0 AS position', 'points / IF( maps <10, maps *10, maps ) AS skill'), 
            'where' => array('(playtime > 300000 OR races > 10)'),
            'orderby' => array($this->_order .' '. $this->_dir),
        );
    }
    
    /**
     * Fetch additional information for an item
     *
     * @param stdClass &$item
     * @return void
     */
    protected function _addAdditionalInformation(&$item)
    {
        $id = (integer)$item->id;
        $stmt = $this->_pdo->query("SELECT *, COUNT(id) AS num FROM award WHERE player_id = $id GROUP BY type");
        $item->awards = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$stmt = $this->_pdo->query("SELECT COUNT(*) from player_map WHERE player_id = $id AND prejumped = 'true'");
		$item->pj = $stmt->fetchColumn();
    }
}
