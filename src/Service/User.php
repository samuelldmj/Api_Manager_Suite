<?php
//Business logic 
namespace Src\Service;

use Firebase\JWT\JWT;
use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;

use Src\Validation\UserValidation;
use Src\Exceptions\InvalidValidationException;
use Respect\Validation\Validator as v;
use Src\Dal\UserDal;
use Src\Entity\User as UserEntity;
use Src\Exceptions\EmailExistException;
use Src\Exceptions\InvalidCredentialException;

class User
{

  public function __construct(protected string $jwtSecretKey){

  }


  public function login(mixed $payload): ?array
  {
    $userValidation = new UserValidation($payload);
    if ($userValidation->isLoginSchemaValid()) {

      if (UserDal::doesEmailExist($payload->email))
       {
        $user = UserDal::getByEmail($payload->email);
        if (password_verify($payload->password, $user->password)) 
        {
          $userFullName = "{$user->first_name} {$user->last_name}";
          $iss = $_ENV['APP_URL'];
          $iat = time();
          $nbf = $iat + 10;
          $exp = $iat + $_ENV['JWT_EXPIRATION_TIME'];
          $aud = 'myusers';
          $user_arr_data = [
            'email' => $user->email,
            'name' => $userFullName
          ];
          $payload_info = [
            'iss' => $iss,
            'iat' => $iat,
            'nbf' => $nbf,
            'exp' => $exp,
            'aud' => $aud,
            'data' => $user_arr_data
          ];
          
          $algEncrypt = $_ENV['JWT_ALGORITHM_ENCRYPTION'];
          $jwtToken = JWT::encode($payload_info, $this->jwtSecretKey, $algEncrypt);
          UserDal::setUserJwtToken($jwtToken, $user->user_uuid);
          
          return [
            'message' => sprintf('%s successfully logged in', $userFullName),
            'token' => $jwtToken,
          ];

        }
        throw new InvalidCredentialException();
      }
      throw new InvalidValidationException();
    }

  }

  public function create(mixed $payload): object|array
  {

    $userValidation = new UserValidation($payload);

    //validating schema
    if ($userValidation->isCreationSchemaValid()) {
      //assigns uuid to user when a data input is created.
      $userId = Uuid::uuid4()->toString();

      $userEntity = new UserEntity;
      $userEntity->setUserUuid($userId)
        ->setFirstName($payload->first)
        ->setLastName($payload->last)
        ->setEmail($payload->email)
        ->setPassword(password_hash($payload->password, PASSWORD_ARGON2I))
        ->setPhoneNumber(phone: $payload->phoneNumber)
        ->setCreatedDate(date('Y-m-d H:i:s'));

      if (UserDal::doesEmailExist($userEntity->getEmail())) {
        throw new EmailExistException("This email {$userEntity->getEmail()} address already exists");
      }

      //from DAL file.
      if (!$userUuid = UserDal::create(userEntity: $userEntity)) {
        //when an entry into the database fails
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        $payload = [];
      }

      Http::getStatusCode(StatusCode::CREATED);
      // return array_merge((array) $payload, ["user_uuid" => $userUuid]);
      //or
      $payload->userUuid = $userUuid;
      return $payload;
    }
    throw new InvalidValidationException();
  }



  public function retrieveAll(): array
  {

    $allRec = UserDal::getAllRec();
    // foreach($allRec as $k){
    //   unset($k['id']);
    // }

    $hidingUserId = array_map(function (object $k) {
      unset($k['id']);
      return $k;
    }, $allRec);
    return $hidingUserId;
  }

  public function retrieve(string $userUuid): array
  {
    if (v::uuid(version: 4)->validate($userUuid)) {
      $userData = UserDal::getById($userUuid);
      //removing user id and uuid from the display field.
      unset($userData['id']);
      return $userData;
    }
    throw new InvalidValidationException('invalid UUID');
  }


  //deleting the user from the db using url: delete method
//     public function remove(string $userId): bool
// {
//     if (!v::uuid(version: 4)->validate($userId)) {
//         throw new InvalidValidationException('Invalid UUID');
//     }

  //     return UserDal::deleteUser($userId);
// }


  // ALTERNATIVELY //deleting the user from the db using url: post method on the body of the page
//incorrect payload will give a null response.
  public function remove(mixed $payload)
  {

    $userValidation = new UserValidation($payload);
    if ($userValidation->isDeleteUser()) {
      return UserDal::deleteRec($payload->userUuid);
    }
  }

  public function update(mixed $payload): object|array
  {

    $userValidation = new UserValidation($payload);
    
    // Validating schema
    if (!$userValidation->isUpdateSchemaValid()) {
      throw new InvalidValidationException();
    }

    $userUuid = $payload->userUuid;
    $userEntity = new UserEntity();

    // Setting my user entity properties
    if (!empty($payload->first)) {
      $userEntity->setFirstName($payload->first);
    }

    if (!empty($payload->last)) {
      $userEntity->setLastName($payload->last);
    }

    if (!empty($payload->email)) {
      $userEntity->setEmail($payload->email);
    }

    if (!empty($payload->phoneNumber)) {
      $userEntity->setPhoneNumber($payload->phoneNumber);
    }

    // Update in the database
    if (UserDal::update($userUuid, $userEntity) === false) {
      Http::setHeadersByCode(StatusCode::NOT_FOUND);
      return [];
    }

    Http::setHeadersByCode(StatusCode::OK);
    return $payload; 
  }

}