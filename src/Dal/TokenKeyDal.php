<?php

namespace Src\Dal;
use RedBeanPHP\Facade as R;

final class TokenKeyDal {

    public const TABLE_NAME = 'secretkeys';

    public static function saveJwtSecretKey(string $jwtKey): void
    {
        $tokenBean = R::dispense(self::TABLE_NAME);

        $tokenBean->secretKey = $jwtKey;

        R::store($tokenBean);

        R::close();

    }

    public static function getSecretKey():?string
    {
        $tokenKeyBean = R::load(self::TABLE_NAME, 1);
        return $tokenKeyBean?->secretKey;
    }
    

}
