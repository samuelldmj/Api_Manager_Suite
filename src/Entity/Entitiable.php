<?php

namespace Src\Entity;

interface Entitiable {

    public function unserialize(?array $data):self;                                    

    public function setSequentialId(int $itemId);
    
    public function getSequentialId();
    
    }
