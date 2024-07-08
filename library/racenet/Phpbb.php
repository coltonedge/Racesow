<?php

$config = Zend_Registry::get('config');
// assign doctrine database config to phpbb
if (preg_match("/^([^:]+):\/\/([^:@]+):*([^@]+)*@([^\/]+)\/(.+)$/", $config->database->doctrine->dsn, $matches) && $matches[1] == 'mysql') {

    $dbhost     = $matches[4];
    $dbname     = $matches[5];
    $dbuser     = $matches[2];
    $dbpasswd   = $matches[3];   
}

/**
 * Called by phpBB to view ingame nicknames
 *
 * @param string $poster
 * @return string
 */
function get_racenet_nickname( $poster ) {

    $user = Doctrine::getTable('PhpbbUsers')->createQuery()->where('username = ?', $poster)->fetchOne();
    if (!$user || !$user->IngameLinkage->Player->id) {
        
        return $poster;
    }
        
    return '<a title="account: '. $poster .'" href="/player/index/id/'. $user->IngameLinkage->Player->id .'/">'. new Racenet_IngameColors($user->IngameLinkage->Player->name) .'</a>';
}

/**
 * Returns the racenet view
 *
 * @return Zend_View
 */
function get_racenet_view()
{
    $config = Zend_Registry::get('config');
    $view = new Zend_View($config);
    $view->setBasePath(PATH_ROOT . DS . 'application' . DS . 'views');
    
    return $view;
}

function racenet_addUrlParam($url, $param, $value, $replace = true)
{
    if (preg_match('/[&\?]'. $param .'(?:=[^&=]*)?/', $url)) {

    	if ($replace) {
    		
	    	return preg_replace('/([&\?]'. $param .')(?:=[^&=]*)?/', '\\1='. $value, $url);
    	}
    	
    	return $url;
    }
    
    return $url . (strpos($url, '?') ? '&' : '?') . $param . '=' . $value;
}

/**
 * Render the racenet layout header
 *
 * @return string
 */
function get_racenet_header()
{
    $config = Zend_Registry::get('config');

    $request = new Zend_Controller_Request_Simple('index', 'index', 'forum');
    Zend_Controller_Front::getInstance()->setRequest($request);

    $view = get_racenet_view();
    $view->navigation = NavigationTree::getInstance();
    
    return $view->render('layout_head.phtml');
}

/**
 * Render the racenet layout footer
 *
 * @return string
 */
function get_racenet_footer()
{
    $view = get_racenet_view();
    
    return $view->render('layout_foot.phtml');
}

function facebook_logout()
{
	$session = new Zend_Session_Namespace('facebook');
	$session->unsetAll();
}
