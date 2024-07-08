<?php

class GroupsController extends Racenet_Controller_Action
{
	/**
	 * Show the list of groups and invitations
	 */
    public function indexAction()
    {
        $this->view->headTitle("Clans");
		
		$account = RacenetAccount::getInstance();
		
		$this->view->invitations = Doctrine_Query::create()
			->from('ClanInvitation')
			->where('player_id = ?', $account->Player ? $account->Player->id : 0)
			->execute();
		
		$query = Doctrine_Query::create()
			->select('c.*, COUNT(m.player_id) AS members, SUM(p.points) AS points')
			->from('Clan c')
			->leftJoin('c.Members m')
			->leftJoin('m.Player p')
			->groupBy('c.id')
			->orderBy('SUM(p.points) DESC');
		
		$adapter = new Racenet_Paginator_Adapter_DoctrineQuery($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(15);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
		
		$this->view->paginator = $paginator;
		$this->view->messages = $this->_helper->FlashMessenger->getMessages();
    }
	
	/**
	 * Show a group's details
	 */
	public function detailsAction()
	{
		$clanId = $this->_getParam('id', 0);
		if (!$clan = Doctrine::getTable('Clan')->find($clanId)) {
		
			$this->_helper->_flashMessenger('Group does not exist.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
	
		$this->view->headTitle($clan->name);
	
		$query = Doctrine_Query::create()
			->from('ClanMember m')
			->innerJoin('m.Player p')
			->where('clan_id = ?', $clanId)
			->orderBy('p.points DESC');
	
		$adapter = new Racenet_Paginator_Adapter_DoctrineQuery($query);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(15);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
		
		$account = RacenetAccount::getInstance();
		
		$this->view->member = Doctrine_Query::create()
			->from('ClanMember')
			->where('clan_id = ?', $clan->id)
			->andWhere('player_id = ?', $account->Player ? $account->Player->id : 0)
			->fetchOne();
		
		$this->view->invitations = Doctrine_Query::create()
			->from('ClanInvitation')
			->where('clan_id = ?', $clan->id)
			->execute();
		
		$this->view->account = $account;
		$this->view->paginator = $paginator;
		$this->view->clan = $clan;
		$this->view->messages = $this->_helper->FlashMessenger->getMessages();
	}
	
	/**
	 * Join a group
	 */
	public function joinAction()
	{
		$account = RacenetAccount::getInstance();
		$clanId = $this->_getParam('id');
		if (!$account->Player->id) {
		
			$this->_helper->FlashMessenger('You need a linked nickname to join a group (see settings/ingame nickname).');
			$this->_redirect("/groups/details/id/". $clanId);
		}
		
		if (!Doctrine_Query::create()
			->from('ClanMember')
			->where('clan_id = ?', $clanId)
			->andWhere('player_id = ?', $account->Player->id)
			->limit(1)
			->fetchOne()) {
		
			$member = new ClanMember;
			$member->clan_id = $clanId;
			$member->player_id = $account->Player->id;
			$member->save();
			
			$this->_helper->FlashMessenger('You have joined the group.');
			
		} else {
		
			$this->_helper->FlashMessenger('You are already member of this group.');
		}
		
		$this->_redirect("/groups/details/id/". $clanId);
	}
	
	/**
	 * Leave a group
	 */
	public function leaveAction()
	{
		$account = RacenetAccount::getInstance();
		$clanId = $this->_getParam('id');
		if (!$account->Player->id) {
		
			$this->_helper->FlashMessenger('You need a linked nickname to leave a group (see settings/ingame nickname).');
			$this->_redirect("/groups/details/id/". $clanId);
		}
		
		if ($member = Doctrine_Query::create()
			->from('ClanMember')
			->where('clan_id = ?', $clanId)
			->andWhere('player_id = ?', $account->Player->id)
			->limit(1)
			->fetchOne()) {
		
			$member->delete();
			$this->_helper->FlashMessenger('You have left the group.');
			
		} else {
		
			$this->_helper->FlashMessenger('Can not leave the group.');
		}
		
		$this->_redirect("/groups/details/id/". $clanId);
	}
	
	/**
	 * Edit a group
	 */
	public function editAction()
	{
		$this->view->headTitle("Edit Group");
        $account = RacenetAccount::getInstance();		
		
        if (!$account->isLoggedIn()) {
            
            $url = parse_url($_SERVER['REQUEST_URI']);
            $this->_redirect('/forum/login.php?redirect='. $url['path']);
        }
        
		$newClan = false;
		$clanId = $this->_getParam('id');
        if (!$clanId ||(!$clan = Doctrine::getTable('Clan')->find($clanId))) {
		
			$newClan = true;
			$clan = new Clan;
			$clan->owner_id = $account->User->user_id;
		}
		
		if (!$account->Player->id) {
		
			$this->_helper->FlashMessenger('You need a linked nickname to create a group (see settings/ingame nickname).');
			$this->_redirect("/groups/");
		}
		
		if ($clan->owner_id != $account->User->user_id) {
		
			$this->_helper->_flashMessenger('You are not allowed to edit this group.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
        
        $form = new Form_Clan();
        $form->setMethod('post');
             
        if ($this->getRequest()->isPost()) {
            
            if ($form->isValid($this->_getAllParams())) {
			
				$clan->fromArray($form->getValues());
				$clan->save();
				
				if ($newClan) {
				
					$member = new ClanMember;
					$member->clan_id = $clan->id;
					$member->player_id = $account->Player->id;
					$member->save();
				}
				
				$this->_helper->_flashMessenger('Group has been saved.');
				$this->_redirect($this->view->url(array('id' => $clan->id)));
            }
            
        } else {
            
            $form->setDefaults($clan->toArray());
        }
		
		$this->view->messages = $this->_helper->_flashMessenger->getMessages();
		$this->view->clan = $clan;
        $this->view->form = $form;                             
	}
	
	/**
	 * Invite a player
	 */
	public function inviteAction()
	{
		$clanId = $this->_getParam('id', 0);
		if (!$clan = Doctrine::getTable('Clan')->find($clanId)) {
		
			$this->_helper->_flashMessenger('Group does not exist.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
	
		if ($clan->owner_id != RacenetAccount::getInstance()->User->user_id) {
		
			$this->_helper->_flashMessenger('You are not allowed to edit this group.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
	
		$form = new Form_Invite();
        $form->setMethod('post');
             
        if ($this->getRequest()->isPost()) {
            
            if ($form->isValid($this->_getAllParams())) {				
				
				if (!$player = Doctrine::getTable('Player')->findOneBySimplified($form->getValue('name'))) {
				
					$this->_helper->_flashMessenger('Player "'. $form->getValue('name') . '" does not exist.');
					$this->_redirect($this->view->url());
				}
				
				if (Doctrine_Query::create()
					->from('ClanMember')
					->where('clan_id = ?', $clan->id)
					->andWhere('player_id = ?', $player->id)
					->fetchOne()) {
				
					$this->_helper->_flashMessenger('Player already is in this group.');
					$this->_redirect($this->view->url());
				}
				
				if (Doctrine_Query::create()
					->from('ClanInvitation')
					->where('clan_id = ?', $clan->id)
					->andWhere('player_id = ?', $player->id)
					->fetchOne()) {
				
					$this->_helper->_flashMessenger('Player already has been invited.');
					$this->_redirect($this->view->url());
				}
					
				$invite = new ClanInvitation;
				$invite->clan_id = $clan->id;
				$invite->player_id = $player->id;
				$invite->save();
					
				$this->_helper->_flashMessenger('Player has been invited.');
				$this->_redirect($this->view->url());
            }
        }
		
		$this->view->messages = $this->_helper->_flashMessenger->getMessages();
		$this->view->clan = $clan;
		$this->view->form = $form;
	}
	
	/**
	 * Accept an invitation
	 */
	public function acceptAction()
	{
		$inviteId = $this->_getParam('id');
		$account = RacenetAccount::getInstance();
		
		if (!$account->isLoggedIn() || !$account->Player) {
		
			$this->_helper->_flashMessenger('You need to be logged in to accept an invitation.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
		
		if (!$invite = Doctrine::getTable('ClanInvitation')->find($inviteId)) {
		
			$this->_helper->_flashMessenger('Invalid invitation.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
		
		if ($account->Player->id != $invite->player_id) {
		
			$this->_helper->_flashMessenger('You can only accept your own invitations.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
		
		$member = new ClanMember;
		$member->clan_id = $invite->clan_id;
		$member->player_id = $invite->player_id;
		$member->save();
		$invite->delete();
		
		$this->_helper->_flashMessenger('You have joined the group.');
		$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
	}	
	
	/**
	 * Decline an invitation
	 */
	public function declineAction()
	{
		$inviteId = $this->_getParam('id');
		$account = RacenetAccount::getInstance();
		
		if (!$account->isLoggedIn() || !$account->Player) {
		
			$this->_helper->_flashMessenger('You need to be logged in to decline an invitation.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
		
		if (!$invite = Doctrine::getTable('ClanInvitation')->find($inviteId)) {
		
			$this->_helper->_flashMessenger('Invalid invitation.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}
		
		if ($account->Player->id != $invite->player_id) {
		
			$this->_helper->_flashMessenger('You can only decline your own invitations.');
			$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
		}

		$invite->delete();
		
		$this->_helper->_flashMessenger('You have declined the invitation.');
		$this->_redirect($this->view->url(array('controller' => 'groups'), null, true));
	}
}
