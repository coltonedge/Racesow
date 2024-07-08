<?php

ini_set( 'include_path', ini_get( 'include_path' ) . ";../../../library" );

$feed = Racenet_SimpleRss::read("http://1337demos.com/demos/rss.xml");

$demos = array();
foreach ($feed->channel->item as $item)
{
    $item->pubDate = date("Y-m-d", strtotime($item->pubDate));
    $demos[] = $item;
}

echo '<pre>';
print_r($demos);
echo '</pre>';


/**
 * Racenet_SimpleRss
 *
 */
class Racenet_SimpleRss
{
    /**
     * Config for Zend_Http_Client
     *
     * @var array
     */
    static protected $_config = array(
        "useragent"    => "Racenet RSS Reader",
        "maxredirects" => 0,
        "timeout"      => 5,
    );

    /**
     * Setter for the config
     *
     * @param array|Zend_Config $config
     * @return Racenet_SimpleRss
     */
    static public function setConfig($config)
    {
        if ($config instanceof Zend_Config) {
            
            self::$_config = $config->toArray();
            
        } else if (is_array($config)) {
            
            self::$_config = $config;
            
        } else if(null !== $config) {
            
            throw new Exception("invaliud config passed to Racenet_SimpleRss");
        }
        
        return $this;
    }
    
    static public function read($url)
    {
        try {

            require_once 'Zend/Http/Client.php';
            $client = new Zend_Http_Client();
            $client->setUri($url)
                   ->setConfig(self::$_config);
            $xml = $client->request();
            return simplexml_load_string($xml->getBody());
            
        } catch (Exception $e) {
            
            return $e->getMessage();
        }
    }
}

?>