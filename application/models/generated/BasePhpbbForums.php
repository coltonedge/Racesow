<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BasePhpbbForums extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('phpbb_forums');
    $this->hasColumn('forum_id', 'integer', 2, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'length' => '2'));
    $this->hasColumn('cat_id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('forum_status', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('forum_order', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '1', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('forum_posts', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('forum_topics', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('forum_last_post_id', 'integer', 3, array('type' => 'integer', 'unsigned' => '1', 'default' => '0', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('prune_enable', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_view', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_read', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_post', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_reply', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_edit', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_delete', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_sticky', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_announce', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_vote', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_pollcreate', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('auth_attachments', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('attached_forum_id', 'integer', 3, array('type' => 'integer', 'default' => '-1', 'notnull' => true, 'length' => '3'));
    $this->hasColumn('auth_download', 'integer', 1, array('type' => 'integer', 'default' => '0', 'notnull' => true, 'length' => '1'));
    $this->hasColumn('forum_name', 'string', 150, array('type' => 'string', 'length' => '150'));
    $this->hasColumn('forum_desc', 'string', 2147483647, array('type' => 'string', 'length' => '2147483647'));
    $this->hasColumn('prune_next', 'integer', 4, array('type' => 'integer', 'length' => '4'));
  }

  public function setUp()
  {
    $this->hasMany('PhpbbTopics', array('local' => 'forum_id',
                                        'foreign' => 'forum_id'));
  }
}