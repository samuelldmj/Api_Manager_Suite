<?php

//The process of obtaining a jwtKey  for login
/*
1)
userRoute file
 In the UserActionRoute.php file,
the SecretKey::getJwtSecretKey() method is called to retrieve the JWT secret key. This key is then passed to the User class constructor.

2) 
in the SecretKey file
The SecretKey::getJwtSecretKey() method performs the following steps:
=> Checks if the secret key exists in the database:
=> If no key exists, generates a new one and saves it to the database:
=> Caches the key for future use:

3) Back to UserRoute File
After retrieving the secret key, the User object is instantiated with the secret key. The User class uses this key to generate and validate JWT tokens

4) In the User class, the login() method uses the secret key to generate a JWT token for the authenticated user.

5)Set the JWT Token in the Users Table
After generating the JWT token, the updateUserSessionToken() method updates the user's session_token and last_session_time fields in the database.

6) Display a Message After Authentication
Once the token is generated and the user's session is updated, a success message is returned to the client:
*/


namespace Src\Service;

use Src\Model\SecretKey as ModelsSecretKey;

class SecretKey
{
    private static $jwtSecretKey = null;

    //todo move jwtSecretKey from db to .env
    public static function getJwtSecretKey(): string
    {
        // Return the cached key if it exists
        if (self::$jwtSecretKey !== null) {
            return self::$jwtSecretKey;
        }

        // Retrieve the first secret key from the database
        $jwtKey = ModelsSecretKey::first()?->secret_key;

        // If no key exists, generate and store a new one
        if (!$jwtKey) {
            $uniqueSecretKey = hash('sha512', strval(time()));
            ModelsSecretKey::create(['secret_key' => $uniqueSecretKey]);
            $jwtKey = $uniqueSecretKey;
        }

        // Cache the key for future use
        self::$jwtSecretKey = $jwtKey;
        return $jwtKey;
    }
}
