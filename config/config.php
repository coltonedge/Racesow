<?php

//////////////////////////////////////////////////////////////////////////////
// Include this file to get access to all interfaces. Do NOT touch!
//////////////////////////////////////////////////////////////////////////////

include('init.php');

// Exception handling
set_exception_handler(array(new Racenet_ExceptionHandler, 'handle'));

// Debugging
FB::setEnabled($config->debug);

// Cookies
ini_set('session.cookie_domain', Doctrine::getTable('PhpbbConfig')->find('cookie_domain')->config_value);
