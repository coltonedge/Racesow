<?php
/**
 * Load model-classes from the (specified) model folder
 *
 */
 
class Racenet_Controller_Action_Helper_ModelFactory extends Zend_Controller_Action_Helper_Abstract 
{ 
    /**
     * $_modelPathSpec
     *
     * @var string
     */ 
    protected $_modelPathSpec = ':moduleDir/models'; 
 
    /**
     * $_modelPrefixSpec
     *
     * @var string
     */ 
    protected $_modelPrefixSpec  = ':moduleName_'; 
     
    /**
     * $_modelPostfixSpec
     *
     * @todo Postfixing of names?
     * @var string
     */ 
    //protected $_modelPostfixSpec = ''; 
     
    /**
     * setModelPathSpec()
     *
     * @param string $pathSpec
     * @return Xend_Controller_Action_Helper_ModelLoader
     */ 
    public function setModelPathSpec($pathSpec) 
    { 
        $this->_modelPathSpec = $pathSpec; 
        return $this; 
    } 
     
    /**
     * setModelPrefixSpec()
     *
     * @param string $prefixSpec
     * @return Xend_Controller_Action_Helper_ModelLoader
     */ 
    public function setModelPrefixSpec($prefixSpec) 
    { 
        $this->_modelPrefixSpec = $prefixSpec; 
        return $this; 
    } 
        
    /**
     * load()
     *
     * @param string $model
     * @param unknown_type $pathSpec
     * @param unknown_type $prefixSpec
     */ 
    public function load($models, $pathSpec = null, $prefixSpec = null) 
    { 
        Zend_Loader::loadClass('Racenet_Model_Abstract');
              $this->_request = $this->getRequest();
        
          if( !is_array( $models ) )
          {
            $models = array( $models );
          }
        $modelPathSpec   = ($pathSpec !== null) ? $pathSpec : $this->_modelPathSpec; 
        $modelPrefixSpec = ($prefixSpec !== null) ? $prefixSpec : $this->_modelPrefixSpec; 
 
        foreach ($models as $model)
        { 
            if (class_exists($model))
                continue; 
 
            if (!isset($front))
            { 
                $front = Zend_Controller_Front::getInstance(); 
                $modules = $front->getControllerDirectory(); 
            } 
             
            // strip the controller directory name to get the real module path. 
            foreach ($modules as $moduleName => $moduleDir)
            { 
                $modules[$moduleName] = preg_replace('/\/'.$front->getModuleControllerDirectoryName().'$/', '', $moduleDir); 
            }  
             
            // init 
            $validModule   = null; 
            $strippedModel = $model; 
             
            // find module based of what was supplied from the user 
            if ($modelPrefixSpec != '')
            { 
                foreach (array_keys($modules) as $checkModule)
                { 
                    if (preg_match('/^'.str_replace(':moduleName', $checkModule, $modelPrefixSpec).'/i', $model, $matches))
                    { 
                        $validModule = $checkModule; 
                        $strippedModel = preg_replace('/^'.$matches[0].'/i', '', $model); 
                        break; 
                    } 
                } 
            } 
             
            // no module based on name, find the module name we are currently in? 
            if (!$validModule)
            { 
                if ($this->_request instanceof Zend_Controller_Request_Abstract)
                { 
                    $validModule = $this->_request->getModuleName(); 
                    $stripFrom = str_replace(':moduleName', $validModule, $modelPrefixSpec); 
                    $strippedModel = preg_replace('/^'.$stripFrom.'/i', '', $model); 
                } 
            } 
             
            // if not a valid module, go default. 
            if (!$validModule)
            { 
                $validModule = 'default'; 
            } 
             
            // translate the path to the place where models are stored 
            $translatedPath = str_replace(':moduleDir', $modules[$validModule], $modelPathSpec); 
     
            // since we will use loadFile, we need to do our own Class->File (with dir) mapping 
            if (strstr($strippedModel, '_'))
            { 
                $moreDir = substr($strippedModel, 0, strrpos($strippedModel, '_')+1); 
                $strippedModel = str_replace($moreDir, '', $strippedModel); 
                $translatedPath .= DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $moreDir); 
            } 
             
            // make sure to translate underscores to dir seperators 
            $strippedModel = str_replace('_', DIRECTORY_SEPARATOR, $strippedModel); 
                     
            // load the file 
            Zend_Loader::loadFile($strippedModel . '.php', $translatedPath); 
                         
            // if model doesnt exist now, we gots major problems 
            if (!class_exists($model))
            { 
                throw new Zend_Controller_Action_Exception('Model class ' . $model . ' not found in file ' . $strippedModel .'.php in path ' . $translatedPath); 
            } 
             
        } 
 
    } 
     
    /**
     * Enter description here...
     *
     * @param unknown_type $model
     * @param unknown_type $pathSpec
     * @param unknown_type $prefixSpec
     * @return unknown
     */
    public function direct( $model, $props = array() ) 
    { 
            return $this->getInstance( $model, $props );
    }
    
    /**
     * Enter description here...
     *
     * @param unknown_type $modelName
     * @return unknown
     */
        private function getInstance( $modelName, $props )
        {
            $this->load( $modelName );
            return new $modelName( $props );
        }
} 