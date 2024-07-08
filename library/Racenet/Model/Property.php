<?php

class Racenet_Model_Property
{
    public $value;
    public $access = Racenet_Model::ACL_FULL;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function __toSting()
    {
        return $this->value;
    }
}