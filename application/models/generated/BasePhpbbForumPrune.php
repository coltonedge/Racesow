<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BasePhpbbForumPrune extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('phpbb_forum_prune');
    $this->hasColumn('prune_id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'autoincrement' => true, 'length' => '3'));
    $this->hasColumn('forum_id', 'integer', 2, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '2'));
    $this->hasColumn('prune_days', 'integer', 2, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '2'));
    $this->hasColumn('prune_freq', 'integer', 2, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '2'));
  }

}