<?php

/**
 * Form for requesting nickname merges
 *
 */
class Form_Settings extends Racenet_Form
{
    /**
     * Constructor replacement
     *
     */
    public function init()
    {

        //encoding for file upload
        $this->setAttrib('enctype', 'multipart/form-data');

        // warsowID
        $user = $this->createElement('text', 'warsow_id');
        $user->setLabel('warsowID')
             ->setAttrib('readonly','true')
             ->setRequired(false)
             ->setIgnore(true);
        $this->addElement($user);
        
        // racenetID
        $user = $this->createElement('text', 'username');
        $user->setLabel('racenetID')
             ->setAttrib('readonly','true')
             ->setRequired(false)
             ->setIgnore(true);
             //asd
        $this->addElement($user);
        
        // password
        Zend_Loader::loadClass('Racenet_Filter_Md5');
        Zend_Loader::loadClass('Racenet_Validate_Context');
        $pwFilter = new Racenet_Filter_Md5();
        
        $pw = $this->createElement('password', 'user_password');
        $pw->setLabel('Password')
           ->setIgnoreIfEmpty(true)
           ->setDescription('Only enter if you want to change your password')
           ->addFilter($pwFilter);
        $this->addElement($pw);
    
        // password confirmation
        $elem = $this->createElement('password', 'password_confirm');
        $elem->setLabel('Confirm password');
        
        $pwContext = new Racenet_Validate_Context();
        $pwContext->addContextElement( $pw );
        
        $elem->addFilter($pwFilter)
             ->addValidator($pwContext)
             ->setIgnore(true)
             ->setAllowEmpty(false); // validate even if empty
        $this->addElement($elem);
        
        // firstname
        $elem = $this->createElement('text', 'user_firstname');
        $elem->setLabel('Firstname');
        $this->addElement($elem);
        
        // lastname
        $elem = $this->createElement('text', 'user_lastname');
        $elem->setLabel('Lastname');
        $this->addElement($elem);
        
        // from
        $elem = $this->createElement('text', 'user_from');
        $elem->setLabel('Location');
        $this->addElement($elem);
        
        // interests
        $elem = $this->createElement('text', 'user_interests');
        $elem->setLabel('Interests');
        $this->addElement($elem);
        
        // icq
        $elem = $this->createElement('text', 'user_icq');
        $elem->setLabel('ICQ');
        $this->addElement($elem);
        
        // aim
        $elem = $this->createElement('text', 'user_aim');
        $elem->setLabel('AIM');
        $this->addElement($elem);
        
        // msn
        $elem = $this->createElement('text', 'user_msnm');
        $elem->setLabel('MSN');
        $this->addElement($elem);
        
        // yim
        $elem = $this->createElement('text', 'user_yim');
        $elem->setLabel('YIM');
        $this->addElement($elem);

        
        // avatar
        $elem = $this->createElement('file','user_avatar')
            ->setLabel('Avatar file (gif or jpg)')
            ->setDestination('forum/images/avatars/')
			->addValidator('Extension',true,'jpg,gif')
            ->addValidator('IsImage',true)
            ->addValidator('ImageSize',true,array('maxwidth' => 100 , 'maxheight' => 100))
            ->addValidator('FilesSize',true,200000);
        $this->addElement($elem);
        
        if ($filename = $elem->getFileName()) {
        
			$filenameParts = explode('.',$filename);
            $elem->addFilter('Rename',array('target' => 'forum/images/avatars/'. uniqid(rand()) .'.'. array_pop($filenameParts)));
        }

        // submit button
        $elem = $this->createElement('submit', 'save');
        $elem->setLabel('Save changes')
             ->setIgnore(true);
        $this->addButtonElement($elem);

    }
}
