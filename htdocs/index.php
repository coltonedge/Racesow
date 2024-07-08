<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'on');
date_default_timezone_set('Europe/Berlin');

// Outsource this part so phpBBinZend can include it
require_once '../config/config.php';
$config = Zend_Registry::get('config');

// Request
$request = new Zend_Controller_Request_Http();
$request->setBaseUrl('/');

// Router
$router = new Zend_Controller_Router_Rewrite();
$router->addConfig($config, 'routes');

// Layout
Zend_Layout::startMvc();

// FIXME: Zend Database, still required for map-admin -.-
$db = Zend_Db::factory($config->database);
$db->setFetchMode(Zend_Db::FETCH_OBJ);
Zend_Db_Table_Abstract::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

// Controller
$controller = Zend_Controller_Front::getInstance();

// Configure teh FrontController
$controller
    ->throwExceptions($config->debug)
    ->setRequest($request)
    ->returnResponse(true)
    ->setRouter($router);

// Add Modules (controller directories) and include paths for autoloading
if ($config->modules) {

	foreach ($config->modules as $moduleName => $moduleEnabled) {
	
		if ($moduleEnabled) {
			
            $modulePath = PATH_ROOT . DS . 'application' . ($moduleName != 'default' ? DS . $moduleName : '');
    		$controller->addControllerDirectory($modulePath . DS . 'controllers', $moduleName);
		}
	}
}

$controller->registerPlugin(new Racenet_Controller_Plugin_NewnetStatusWrapper);
//$controller->registerPlugin(new Racenet_Controller_Plugin_FrontControllerCaching);

/*
$x = new Zend_Session_Namespace('temp2');
if (isset($_GET['dev'])) {

	$x->x = true;
}

if (!$x->x) {

	die('<html>
<head>
<style type="text/css">
body {
    font-family: arial;
}
#maintenance {
    clear: both;
}
#head {
    float: left;
    margin: 10px 10px 10px 0;
    padding: 0;
    font-size: 16px;
}
#countbox {
    font-weight: bold;
    margin: 10px 5px 10px 10px;
    float: left;
    font-size: 16px;
}
#link {
    clear: left;
    margin-top: 20px;
    margin-left: 20px;
    font-size: 12px;
}
</style>
</head>
<body>
<SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
<!--

function calcTime(offset) {

    // create Date object for current location
    d = new Date(2010,9,20,20,10,00);
   
    // convert to msec
    // add local time zone offset
    // get UTC time in msec
    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
   
    // create new Date object for different city
    // using supplied offset
    nd = new Date(utc + (3600000*offset));
   
    // return time as a string
    return nd;

}

var dateFuture = calcTime("+2");

function GetCount(){

        dateNow = new Date();                                                                        //grab current date
        amount = dateFuture.getTime() - dateNow.getTime();                //calc milliseconds between dates
        delete dateNow;

        // time is already past
        if(amount < 0){
                document.getElementById(\'countbox\').innerHTML="Now!";
        }
        // date is still good
        else{
                days=0;hours=0;mins=0;secs=0;out="";

                amount = Math.floor(amount/1000);//kill the "milliseconds" so just secs

                days=Math.floor(amount/86400);//days
                amount=amount%86400;

                hours=Math.floor(amount/3600);//hours
                amount=amount%3600;

                mins=Math.floor(amount/60);//minutes
                amount=amount%60;

                secs=Math.floor(amount);//seconds

                if(days != 0){out += days +" day"+((days!=1)?"s":"")+", ";}
                if(days != 0 || hours != 0){out += hours +" hour"+((hours!=1)?"s":"")+", ";}
                if(days != 0 || hours != 0 || mins != 0){out += mins +" minute"+((mins!=1)?"s":"")+", ";}
                out += secs +" seconds";
                document.getElementById(\'countbox\').innerHTML=out;

                setTimeout("GetCount()", 1000);
        }
}

window.onload=function(){GetCount();}//call when everything has loaded

//-->
</script>
<h1 id="maintenance">Racenet Maintenance</h1>
<div id="countbox"></div>
<h2 id="head">until Racesow 0.5 release and warsow-race.net relaunch</h2>
<div id="link">If you want to write a news acticle here you got <a href="http://racesow.warsow-race.net/wiki/Racesow/Press">all required information about the release</a></div>
</body>
</html>');
}
*/

// Let the race begin...
echo $controller->dispatch();
