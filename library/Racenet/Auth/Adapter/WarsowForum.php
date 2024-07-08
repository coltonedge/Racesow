<?php

/**
 * Racenet_Auth_Adapter_WarsowForum
 *
 * @author Andreas Linden <zlx@gmx.de>
 */
class Racenet_Auth_Adapter_WarsowForum implements Zend_Auth_Adapter_Interface
{
	/**
	 * The Url to send the authentication request to
	 *
	 * @var string
	 */
	protected $_authUrl = 'http://www.warsow.net/forum/login.php';
	
	/**
	 * Cookie for $_authUrl
	 *
	 * @var string
	 */
	protected $_cookie;
	
    /**
     * Foo Bar Baz
     *
     * @var string
     */
    protected $_csrfToken;
	
	
    /**
     * Forum login name
     *
     * @var string
     */
    protected $_identity;

    /**
     * Password for login name
     *
     * @var string
     */
    protected $_credential;

    /**
     * Client to initialize a login
     * 
     * @var Zend_Http_Client
     */
    protected $_initClient;
    
    /**
     * Client to perform a login
     * 
     * @var Zend_Http_Client
     */
    protected $_loginCLient;
    
    /**
     * Constructor
     *
     * @param Zend_Config $config
     * @return void
     */
    public function __construct()
    {
        $this->_initClient = new Zend_Http_Client;
    	$this->_initClient
            ->setUri($this->_authUrl)     
            ->setMethod(Zend_Http_Client::GET);
        
        $this->_loginClient = new Zend_Http_Client;
        $this->_loginClient
            ->setUri($this->_authUrl)     
            ->setMethod(Zend_Http_Client::POST)
            ->setEncType(Zend_Http_Client::ENC_URLENCODED)
            ->setParameterPost('form_sent', '1');
    }
    
    /**
     * Run the init request to get the cookie 
     * and the csrfToken
     * 
     * @return void
     */
    protected function _initRequest()
    {
    	$initReponse = $this->_initClient->request();
        $initBody = $initReponse->getBody();
        
        // debugging
        #fb($this->_initClient,'$initClient');
        #fb($initReponse,'$initReponse');
        #echo $initBody;
        
        if (!$this->_cookie = $initReponse->getHeader('Set-cookie')) {
        	
        	return false;
        }
        
        $tmpToken = substr($initBody, strpos($initBody, 'csrf_token" value="') + 19);
        $this->_csrfToken = substr($tmpToken, 0, strpos($tmpToken, '"'));
        #if (strlen($this->_csrfToken) != 32) {
        #	
        #	return false;
        #}
        
        return true;
    }
    
    /**
     * Run the login request
     * 
     * @return void
     */
    protected function _loginRequest()
    {
    	$loginResponse = $this->_loginClient
    	    ->setParameterPost('req_username', $this->_identity)
            ->setParameterPost('req_password', $this->_credential)
            ->setParameterPost('csrf_token', $this->_csrfToken)
            ->setParameterPost('redirect_url', $this->_authUrl . '?csrf_token=' . $this->_csrfToken)
            #->setHeaders('Cookie', $this->_cookie)
            ->request();
            
        // debugging
        #fb($this->_loginClient,'$loginClient');
        #fb($loginResponse->getBody(),'$loginResponse');
        #echo $loginResponse->getBody();
        
        if (strpos($loginResponse->getBody(), 'logged in successfully')) {
            
        	return true;
        }
        
        return false;
    }
    
    /**
     * Set the value to be used as the identity
     *
     * @param  string $value
     * @return Racenet_Auth_Adapter_WarsowForum
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * Set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Racenet_Auth_Adapter_WarsowForum
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }
    
    /**
     * Defined by Zend_Auth_Adapter_Interface.  This method is called to 
     * attempt an authenication. 
     *
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        if ($this->_initRequest() && $this->_loginRequest()) {

        	$code = Zend_Auth_Result::SUCCESS;
            $message = 'Warsow authentication successful.';
                
        } else {
            
            $identity = false;
            $code = Zend_Auth_Result::FAILURE;
            $message = 'Warsow authentication failed.';
        }
        
        return new Zend_Auth_Result(
            $code,
            $this->_identity,
            array($message)
        );
    }
}
