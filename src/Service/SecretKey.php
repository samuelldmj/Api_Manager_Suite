<?php

//The process of obtaining a jwtKey  for login
/*
1)
userRoute file
 $secretKey = SecretKey::getJwtSecretKey();
$user = new User($secretKey);

SecretKey file and Tokendal
2) SecretKey service interact with the database(TokenDal)
=> checks if secretkey exist (using getSecretKey()), if not, create one and save in the db secret table.
=> then return the secret key

3) then back to userRoute file
4) then move to user service file.
5)then set the jwtToken into the users table  for that specific user in its session token field(column) using the UserDal class file and  setJwtToken method. then user is authorized to log in.
A message is displayed after authentication.
*/

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