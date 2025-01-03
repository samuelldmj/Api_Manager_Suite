<?php

namespace   Src\Service;

use Src\Dal\TokenKeyDal;

class SecretKey {

    public static function getJwtSecretKey(): string{

        $jwtKey = TokenKeyDal::getSecretKey();

        if(!$jwtKey){
            $uniqueSecretKey = hash('sha512', strval(time()));
            TokenKeyDal::saveJwtSecretKey($uniqueSecretKey);
            return $uniqueSecretKey;
        }

        return $jwtKey;
    }
}