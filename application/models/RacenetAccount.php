<?php

/**
 * Racenet interface to phpBB's authentication
 *
 */
final class RacenetAccount
{
    /**
     * Singleton instance
     *
     * @var RacenetAccount
     */
    protected static $_instance; 
    
    /**
     * The authenticated user
     *
     * @var User
     */
    protected $_user;
    
    /**
     * warsow.net forum login
     *
     * @var string
     */
    protected $_warsowId;
    
    /**
     * The current session ID
     *
     * @var string
     */
    protected $_sessionId;
    
    /**
     * Show the login form in any case?
     *
     * @var boolean
     */
    protected $_forceLoginForm = false;
    
    /**
     * A notice to de displayed under
     * the login form
     *
     * @var string
     */
    protected $_notice;
    
    public function isAnonymous()
    {
    	return !$this->_user || $this->_user->user_id < 1;
    }
    
    /**
     * Determine authenticated user
     * 
     * @return void
     */
    protected final function __construct()
    {
    	// warsowID
    	/*
        $session = new Zend_Session_Namespace('warsowID');
        if (isset($session->name) && !empty($session->name)) {
        	
        	if ($this->_user = Doctrine::getTable('PhpbbUsers')
        	   ->findOneByWarsowId($session->name)) {

        	   	$this->_warsowId = $session->name;
        	
            } else {
            	
            	$this->_notice = 'Please also login with your racenet account to connect it to your warsow.net account';
            }
        }
        */
		
		// facebookID
		/*
		$session = new Zend_Session_Namespace('facebook');
		if (isset($session->id) && !empty($session->id)) {
		
			$this->_user = Doctrine::getTable('PhpbbUsers')
				->findOneByFacebookId($session->id);
		}
		*/
        
        // racenetID
        if (!$this->_user) {
        	
	        // read phpBB cookie
	        $cookieName = Doctrine::getTable('PhpbbConfig')->find('cookie_name')->config_value;
	        if (isset($_COOKIE[$cookieName .'_data'])) {
	               
	            $this->_cookie = (object)unserialize(stripslashes($_COOKIE[$cookieName .'_data']));
	        }
	        
	        // phpBB session auth
	        if (isset($_COOKIE[$cookieName .'_sid']) && isset($this->_cookie)) {
	            
	            // try to read user session from cookie
	            $this->_sessionId = $_COOKIE[$cookieName .'_sid'];
	            if ($session = Doctrine::getTable('PhpbbSessions')
	                ->createQuery()
	                ->where('session_id = ?', $this->_sessionId)
	                ->addWhere('session_user_id = ?', $this->_cookie->userid)
	                ->fetchOne()) {

                    $this->_user = $session->User;
                }
	        }
        }
        
        // phpBB autologin
        if (!$this->_user && isset($this->_cookie->autologinid) && isset($this->_cookie->userid)) {
        
            $sessionKey = Doctrine::getTable('PhpbbSessionsKeys')
                ->createQuery()
                ->where('key_id = MD5(?)', $this->_cookie->autologinid)
                ->addWhere('user_id = ?', $this->_cookie->userid)
                ->fetchOne();
            
            if ($sessionKey) {
                
                $this->_user = $sessionKey->User;
            }
        }
        
		/*
        if ($this->_user && $this->_user->user_id > 0 && empty($this->_warsowId) && empty($this->_user->warsow_id)) {
        	
        	$this->_notice = 'You can login with your warsow.net account to connect it to your warsow-race.net login.';
        	$this->_forceLoginForm = true;
        }
		*/
		
        if (isset($this->_user->user_id) &&($session = Doctrine::getTable('PhpbbSessions')
            ->createQuery()
            ->where('session_user_id = ?', $this->_user->user_id)
            ->orderBy('session_time DESC')
            ->fetchOne())) {
            
            $session->session_time = time();
            $session->save();
        }
        
        $messages = array(
        
            1 => 'warsow.net login successful but the warsowID is already assigned to another warsow-race.net account.',
            2 => 'the warsowID  has been assigned to your warsow-race.net account successfully.',
            3 => 'invalid username and/or password for warsow.net given',
            4 => 'racenetID login successful.',
            5 => 'warsowID login successful.',
            6 => 'warsow.net login successful but no warsow-race.net account was found. please login or register a racenetID.',
        );
        
        $session = new Zend_Session_Namespace('auth_notice');
        if (isset($_GET['msgCode']) && array_key_exists($_GET['msgCode'], $messages)) {
        	
        	$msgId = $_GET['msgCode'];
        	$session->text = $messages[$msgId];
        	$url = preg_replace('/[&\?]msgCode=([^&]*)/', '', $_SERVER['REQUEST_URI']);
        	if (strpos($url, '&') && !strpos($url, '?')) {
        		
        		$n = 1;
        		$url = str_replace('&', '?', $url, $n);
        	}
        	
        	header('Location: '. $url);
        	exit;
        }
        
        
        if (isset($session->text) && !empty($session->text)) {
        	
        	$this->_notice = $session->text;
        	$session->unsetAll();
        }
    }
    
    public function hasWarsowId()
    {
    	return $this->_user && !empty($this->_user->warsow_id);
    }
    
    public function getWarsowId()
    {
    	return $this->_user->warsow_id;
    }
    
    public function hasNotice()
    {
    	return !empty($this->_notice);
    }
    
    public function getNotice()
    {
    	return $this->_notice;
    }
    
    public function showLoginForm()
    {
    	return $this->_forceLoginForm || !$this->isLoggedIn();
    }
    
    /**
     * Singleton getter
     *
     * @return User
     */
    public static final function getInstance()
    {
        if (self::$_instance === null) {
            
            self::$_instance = new self;
        }
        
        return self::$_instance;
    }
    
    /**
     * Getter for the identity
     *
     * @return User
     */
    public function getIdentity()
    {
        return $this->_user;
    }   
	
    /**
     * Setter for the identity
     *
     * @return User
     */
    public function setIdentity($user)
    {
        return $this->_user = $user;
    }
    
    /**
     * Magic getter
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key == 'Player') {
            
			if ($this->_user && $this->_user->IngameLinkage && $this->_user->IngameLinkage->Player) {
			
				return $this->_user->IngameLinkage->Player;
			}
			
			return null;
        }
        
        if ($key == 'User') {
            
            return $this->_user;
        }
        
        if ($this->_user) {
            
            return $this->_user->$key;
        }
    }
    
    /**
     * Check if a user is authenticated
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return ($this->_user && $this->_user->user_id != -1);
    }
    
    /**
     * Check if the authenticated user has ingame linkage
     *
     * @return unknown
     */
    public function hasIngameLinkage()
    {
        if (!$this->isLoggedIn()) {
            
            return false;
        }
        
        if ((boolean)$this->_user->IngameLinkage->Player->id) {
           
            return $this->_user->IngameLinkage->Player; 
        }
       
        return false;
    }
    
    /**
     * Get the URL for logging out
     *
     * @return string
     */
    public function getLogoutUrl()
    {
        return '/forum/login.php?logout=true'. ( $this->_sessionId ? '&sid='. $this->_sessionId : '' ) .'&redirect='. $this->getRedirectUri();
    }
    
    /**
     * Get the URL where the user should be redirected to after a login or logout
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $_SERVER['REQUEST_URI'] == '/' ? '/news' : $_SERVER['REQUEST_URI'];
    }
}
