<?php

/**
 * @see Zend_Db_Table_Abstract
 */
require_once 'Zend/Db/Table/Abstract.php';

/**
 * Table map of racenet database
 *
 */
class MapItemsTableOld extends Zend_Db_Table_Abstract
{
    protected $_name = 'map_item';
    protected $_primary = array('map_id', 'item');
}