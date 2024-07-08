<?php

/**
 * Form for requesting nickname merges
 *
 */
class Form_Mapupload extends Racenet_Form
{
    /**
     * Where to upload to
     *
     * @var string
     */
    protected $_uploadDestination;
    
    /**
     * Upload identifier for uploadprogress
     *
     * @var string
     */
    protected $_uploadId;

    /**
     * Called by Zend_Form Constructor
     *
     */
    public function init()
    {
        if( !is_dir($this->_uploadDestination) )
        {
            throw new Racenet_Form_Exception('invalid upload destination. provide in Racenet_Form::__construct($props)');
        }
       
        // for upload
        $this->_uploadId = sha1(uniqid());
        $this->setAttrib("enctype", "multipart/form-data");
        $this->setAttrib("onsubmit", "uploadProgress('uploadId', '/maps/uploadprogress/');");
        
        // Go
        $this->autoAddElements();
    }

    /**
     * Upload identifier
     * 
     * THIS FIELD MUST BE THE FIRST ONE IN THE FORM
     * OTHERWISE UPLOAD PROGRESS TRACKING WON'T WORK
     *
     * @return Zend_Form_Element_Hidden
     */
    public function elemUploadId()
    {
        return $this->createElement("hidden", "UPLOAD_IDENTIFIER")
                    ->setAttrib("id", "uploadId")
                    ->setValue($this->_uploadId)
                    ->setIgnore(true);
    }
    
    /**
     * pk3 upload
     *
     * @return Zend_Form_Element_File
     */
    public function elemFileupload()
    {
        // damnit PEAR has some sctrict warnings, so disable strict for now.
        // FIXME: is it working without warnings now?
        // $errOriginal = error_reporting();
        // error_reporting( $errOriginal &~E_STRICT );
        
        //$extInc = new Racenet_Filter_IncrementFileExt;
        $valMap = new Racenet_Validate_Mapupload;
        $adapter = new Racenet_File_Transfer_Adapter_Http;
        //$adapter->addFilter($extInc);
        $adapter->setDestination( $this->_uploadDestination );
        
        $elem = $this->createElement("file", "pk3edmap")
                     ->setTransferAdapter($adapter)
                     ->addValidator($valMap)
                     ->setLabel('Map in *.pk3')
                     ->setRequired(true);
                     
        // error_reporting($errOriginal);
        return $elem;
    }
    
    /**
     * Maptype
     *
     * @return Zend_Form_Element_Radio
     */
    public function elemMaptype()
    {
        return $this->createElement("radio", "freestyle")
                    ->setMultiOptions(array(
                       "false" => "Race",
                       "true" => "Freestyle"))
                    ->setLabel("Map type")
                    ->setValue("false")
                    ->setRequired(true);
    }
    
    /**
     * Submit
     *
     * @return Zend_Form_Element_Submit
     */
    public function elemSubmit()
    {
        return $this->createElement("submit", "save")
                    ->setLabel("Upload now")
                    ->setAttrib("id", "submitMap")
                    ->setIgnore(true);
    }
    
//    public function elemProgress()
//    {
//        return $this->createElement('progressbar', 'progress')
//                    ->setIgnore(true);
//    }
    
    /**
     * Get the upload id
     *
     * @return string
     */
    public function getUploadId()
    {
        return $this->_uploadId;
    }
}
