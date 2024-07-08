<?php

/**
 * Player model
 */
class Player extends BasePlayer
{
    public function getLatestPersonalHighscores($limit = 10)
    {
        $collection = Doctrine_Query::create()
           ->from('PlayerMap')
           ->where('player_id = ?', $this->id)
           ->andWhere('time')
           ->orderBy('created DESC')
           ->limit((integer)$limit)
           ->execute();
           
        return $collection;
    }
    
    public function hasIngameLinkage()
    {
        return (boolean)$this->IngameLinkage->User->user_id;
    }
    
    public function hasNickmergeRequested()
    {
        return (boolean)Doctrine::getTable('NickmergeRequest')->findByFromPlayerId($this->id)->count();
    }
}