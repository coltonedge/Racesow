<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseRace extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('race');
    $this->hasColumn('id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'autoincrement' => true, 'length' => '3'));
    $this->hasColumn('map_id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('player_id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('server_id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'length' => '3'));
    $this->hasColumn('time', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('created', 'timestamp', 25, array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'notnull' => true, 'length' => '25'));
    $this->hasColumn('prejumped', 'enum', 5, array('type' => 'enum', 'values' => array(0 => 'true', 1 => 'false'), 'default' => 'true', 'length' => '5'));
  }

  public function setUp()
  {
    $this->hasOne('Map', array('local' => 'map_id',
                               'foreign' => 'id'));

    $this->hasOne('Player', array('local' => 'player_id',
                                  'foreign' => 'id'));

    $this->hasOne('Server', array('local' => 'server_id',
                                  'foreign' => 'id'));
  }
}