<?php

/**
 * playerModel
 *
 */
class MapsOld extends Racenet_Model_Abstract
{
    /**
     * TODO: NEW PART
     */

    
    /**
     * Maps
     *
     * @var Zend_Db_Table_Abstract
     */
    private $_table;

    /**
     * Get the maps-table
     *
     * @return Zend_Db_Table_Abstract
     */
    private function _getTable()
    {
        if( null === $this->_table )
        {
            $this->_table = new Model_MapsTable;
        }
        return $this->_table;
    }

    
    /**
     * Set a request obejct
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return mapsModel
     */
    public function setRequest(Zend_Controller_Request_Abstract $request)
    {
        $this->_request = $request;
        return $this;
    }
    
    /**
     * Parse order and direction from request object
     * Validates the given order and direction based
     * on the table definition and userdefined filters.
     * Finally read the validated request from the db.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return array Maps
     */
    public function getItems($order = null, $dir = null, $filter = null, $partialMatch = false, $freestyle = array('true', 'false'), $status = array('enabled'))
    {
        $validation = array(
            "name"       => "ASC",
            "races"      => "DESC",
            "freestyle"  => "ASC",
            "downloads"  => "DESC",
            "playtime"   => "DESC",
            "rating"     => "DESC",
            "created"    => "DESC",
        );   
        
        if( !in_array(strtolower($order), array_keys($validation) ) )
            $order = "name";

        if( !in_array(strtoupper($dir), array("ASC", "DESC")) )
            $dir = $validation[$order];

        $select = $this->_db
                       ->select()
                       ->from("map", array(
                           "id", 
                           "name" => "IF(name = '' OR name IS NULL, file, name)",
                           "file",
                           "status",
                           "freestyle",
                           "races",
                           "downloads",
                           "playtime",
                           "rating",
                           "created"))
                       ->where("status IN('". join("','", $status) ."')")
                       ->where("freestyle IN('". join("','", $freestyle) ."')")
                       ->order($order ." ". $dir);
       
        $this->order = $order;
        $this->dir = $dir;

        if ($filter) {
            if ($partialMatch) {
                $filter = "%$filter%";
            }
            $select = $select->where("name LIKE ?", $filter);
        }
        
        return $this->_db->fetchAll($select);
    }
    
    /**
     * Get the maps a player has raced
     *
     * @param integer $playerId
     * @return array
     */
    public function getPlayerItems($playerId, $order, $dir)
    {
        $validation = array(
            "map_id" => "ASC",
            "map" => "ASC",
            "status" => "ASC",
            "playtime" => "DESC",
            "position" => "ASC",
            "races" => "DESC",
            "time" => "ASC",
        );
        
        if( !in_array(strtolower($order), array_keys($validation) ) )
            $order = "playtime";

       if( !in_array(strtoupper($dir), array("ASC", "DESC")) )
            $dir = $validation[$order];
        
        $fields = array(
            "map_id" => "m.id",
            "map" => "m.name",
            "status" => "m.status",
            "playtime" => "pm.playtime",
            "position" => "pm.position",
            "races" => "pm.races",
            "time" => "pm.time",
        );
            
        $select = $this->_db
                       ->select()
                       ->from(array("m" => "map"), $fields)
                       ->join(array("pm" => "player_map"), "pm.player_id = ". (integer)$playerId. " AND pm.map_id = m.id", array())
                       ->where("m.status = 'enabled'")
                       ->order( $order ." ". $dir );
        
        return $this->_db->fetchAll($select);
    }
    
    public function getRating()
    {
        
    }
    
    /**
     * Read from the DB when stats were updated last time.
     * 
     * FIXME: don't write the query but use db-layer functions. put in own model?
     *      * 
     * @return string Date and time of last stats computing
     */
    public function getComputionTime()
    {
        return $this->_db->fetchOne("SELECT DATE_FORMAT( created, '%d.%m.%Y, %H:%i:%s' ) FROM log_render ORDER BY created DESC LIMIT 1");
    }
}