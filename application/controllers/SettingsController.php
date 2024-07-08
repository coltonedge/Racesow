<?php

/**
 * Controller for Settings
 *
 * @uses       Racenet_Controller_Action 
 */
class SettingsController extends Racenet_Controller_Action
{
    /**
     * indexAction
     *
     */
    public function indexAction()
    {
		$this->view->headTitle("Settings");
        $account = RacenetAccount::getInstance();

        if (!$account->isLoggedIn()) {
            
            $url = parse_url($_SERVER['REQUEST_URI']);
            $this->_redirect('/forum/login.php?redirect='. $url['path']);
        }
        
        $user = $account->getIdentity();
        
        $form = new Form_Settings();
        $form->setAction('/settings/')
             ->setMethod('post');
             
        if ($this->getRequest()->isPost()) {
            
            if ($form->isValid( $this->_getAllParams() ) ) {
				$formData = $form->getValues();
                if (array_key_exists('user_avatar',$formData)) {
                
                    $formData['user_allowavatar'] = 1;
                    $formData['user_avatar_type'] = 1;
                }
				if (array_key_exists('user_avatar', $formData) && !$formData['user_avatar']) {
				
					unset($formData['user_avatar']);
				}
				
                $user->fromArray($formData);
                $user->save();
                
                $this->_helper->flashMessenger->addMessage('Your changes have been saved.');
                $this->_redirect('/settings');
            }
            
        } else {
            
            $form->setDefaults($user->toArray());
            $this->view->messages = $this->_helper->_flashMessenger->getMessages();
        }
         

		$this->view->user = $account;
        $this->view->form = $form;                             
    }
    
    /**
     * ingameAction
     *
     */
    public function ingameAction()
    {
		$this->view->headTitle("Ingame nick");
        $account = RacenetAccount::getInstance();
        $form = new Form_Ingame();
        $form->setAction('/settings/ingame/')
             ->setMethod('post');

        if (!$account->isLoggedIn()) {
            
            $url = parse_url($_SERVER['REQUEST_URI']);
            $this->_redirect('/forum/login.php?redirect='. $url['path']);
        }
        
        $user = $account->getIdentity();

        if ($this->getRequest()->isPost()) {
            
            if ($form->isValid( $this->_getAllParams() ) ) {

                $player = Doctrine::getTable('Player')->findOneBySimplified($form->getValue('simplified'));
            
                // all tests are ok, if the linkage exists we update it
                if ($account->hasIngameLinkage()) {
                    
                    $linkage = $user->IngameLinkage;
                    $linkage->player_id = $player->id;
                    $linkage->save();
                    

                    
                    
                    $this->_helper->flashMessenger->addMessage('Your ingame linkage has been updated.<br/><span style="color: blue">Your new playerID is: ' . $player->id . '</span>');
                    $this->_redirect('/settings/ingame');

                // else we add a linkage to the database
                } else {
                    
                    $linkage = new PlayerPhpbbuser;
                    $linkage->player_id = $player->id;
                    $linkage->user_id = $user->user_id;
                    $linkage->save();
                    $this->_helper->flashMessenger->addMessage('Your Racenet account is now linked to your ingame nick.<br/>Your playerID is: ' . $player->id);
                    $this->_redirect('/settings/ingame');
                }
            }
            
        } else {
            
            $existingData = array(
                'username'   => $user->username,
                'simplified' => $user->IngameLinkage->Player->simplified,
            );
            
            $form->setDefaults($existingData);
        }
        
        $this->view->messages = $this->_helper->_flashMessenger->getMessages();

        $this->view->form=$form;
    }
	
    public function facebookAction()
    {
        if (!$this->getRequest()->isPost()) {
		
			die(json_encode(array('error' => 'non post call')));
		}
		
		$session = new Zend_Session_Namespace('facebook');
		$session->id = $this->_getParam('id');
		
		$response = new stdClass;
		$account = RacenetAccount::getInstance();
        if ($account->isLoggedIn()) {
		
			$user = $account->getIdentity();
			if (!$user->facebook_id) {
			
				$user->facebook_id = $this->_getParam('id');
				$user->save();
				$response->code = 1;
			
			} else if ($user->facebook_id != $this->_getParam('id')) {
				
				$response->code = 5;
				
			} else {
			
				$response->code = 2;
			}
				
		} else {
		
			if ($user = Doctrine::getTable('PhpbbUsers')->findOneByFacebookId((integer)$this->_getParam('id'))) {
			
				$response->code = 3;
			
			} else {
			
				$response->code = 4;
			}	
		}
		
		die(Zend_Json::encode($response));
	}
}
