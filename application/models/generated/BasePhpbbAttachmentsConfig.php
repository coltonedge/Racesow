<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BasePhpbbAttachmentsConfig extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('phpbb_attachments_config');
    $this->hasColumn('config_name', 'string', 255, array('type' => 'string', 'primary' => true, 'length' => '255'));
    $this->hasColumn('config_value', 'string', 255, array('type' => 'string', 'default' => '', 'notnull' => true, 'length' => '255'));
  }

}