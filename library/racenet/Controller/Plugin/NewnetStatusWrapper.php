<?php

/**
 * Convert all old URLs to the new ones and tell the spider that urls have changed
 *
 */
class Racenet_Controller_Plugin_NewnetStatusWrapper extends Zend_Controller_Plugin_Abstract
{
    /**
     * Called before Zend_Controller_Front enters its dispatch loop.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $location = 'http://'. $_SERVER['HTTP_HOST'];
        $op = $request->getParam('op');
        if (!$op && substr($_SERVER['HTTP_HOST'], 0, 5) == 'forum') {
            
            $op = 'forum';
            $location = 'http://www.warsow-race.net';
        }
        
        switch ($op) {
            
            default:
               return; // if not in list do not set new headers
               
            case 'forum':
                $location .= '/forum' . $_SERVER['REQUEST_URI'];
                break;
                
            case 'search':
            case 'compare':
            case 'live':
            case 'awards':
            case 'maps':
            case 'admin':
                $location .= "/". $op;
                break;
                
            case 'impressum':
                $location .= '/imprint/';
                break;
                
            case 'players':
                $location .= "/ranking/";
                break;
                
            case 'player':
            case 'map':
                $location .= "/". $op ."/". $request->getParam('id');
                break;
                
            case 'home':
                $location .= "/";
                break;
        }
        
        header("HTTP/1.1 301 Moved Permanently");
        header("Date: ". date("D, d F Y H:i:s T"));
        header('Location: '. $location);
        
        echo '<html><head><title>The Warsow Racenet - 301 Moved Permanently</title></head>';
        echo '<body><h1>301 Moved Permanently</h1>';
        echo 'your browser did not redirect you to the new location.<br/>';
        echo 'klick <a href="'. $location .'">here</a> to visit the new location of this page ('. $location .').<br/><br/>';
        echo '&copy; ' . date('Y'). ' - The Warsow Racenet'; 
        exit;
    }
}