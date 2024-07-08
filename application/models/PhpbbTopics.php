<?php

/**
 * PhpbbTopics
 *
 */
class PhpbbTopics extends BasePhpbbTopics
{
    /**
     * setUp
     *
     */
    public function setUp()
    {
        $this->hasOne('PhpbbForums as Forum', array(
            'local' => 'forum_id',
            'foreign' => 'forum_id',
        ));
        
        $this->hasMany('PhpbbPosts as Posts', array(
            'local' => 'topic_id',
            'foreign' => 'topic_id',
        ));
    }

    public function getContent( $topic_first_post_id )
    {
        $query = $this->_table->createQuery()
                ->from('PhpbbPostsText')
                ->where('post_id = ?', $topic_first_post_id)
                ->limit(1);

        if ($text= $query->fetchOne()) {

            return $text->post_text;
        }

        return null;
    }
}
