<?php 
//Business logic 
namespace Src\Service;

use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;

use Src\Validation\UserValidation;
use Src\Exceptions\InvalidValidationException;
use Respect\Validation\Validator as v;
use Src\Dal\UserDal;
use Src\Entity\User as UserEntity;
class User 
{
    public function create(mixed $payload):object|array{

        $userValidation = new  UserValidation($payload);

        //validating schema
      if($userValidation->isCreationSchemaValid())
      {
        //assigns uuid to user when a data input is created.
        $userId = Uuid::uuid4()->toString();

        $userEntity = new UserEntity;
        $userEntity->setUserUuid($userId)
        ->setFirstName($payload->first)
        ->setLastName($payload->last)
        ->setEmail($payload->email)
        ->setPhoneNumber($payload->phoneNumber)
        ->setCreatedDate(date('Y-m-d H:i:s'));

          //from DAL file.
            if(UserDal::create(userEntity: $userEntity) === false){
              //when an entry into the database fails
              Http::setHeadersByCode(StatusCode::INTERNAL_SERVER_ERROR);
              $payload = [];
            }

            Http::getStatusCode(StatusCode::CREATED);
            return $payload;
        } 
        throw new InvalidValidationException();
      }

     
    
    public function retrieveAll():array{
      $allRec  = UserDal::getAllRec();
      // foreach($allRec as $k){
      //   unset($k['id']);
      // }

      $hidingUserId = array_map(function(object $k){
        unset($k['id']);
        return $k;
      }, $allRec);
        return $hidingUserId;
    }

    public function retrieve(string $userUuid): array
    {
        if (v::uuid(version:4)->validate($userUuid)) {
            $userData = UserDal::get($userUuid);
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
public function remove(mixed $payload){
  $userValidation = new  UserValidation($payload);
  if($userValidation->isDeleteUser()){
     return UserDal::deleteRec($payload->userUuid);
  }
}

public function update(mixed $payload): object|array {
  $userValidation = new UserValidation($payload);
  
  // Validating schema
  if (!$userValidation->isCreationSchemaValid()) {
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
      Http::setHeadersByCode(StatusCode::INTERNAL_SERVER_ERROR);
      return [];
  }

  Http::setHeadersByCode(StatusCode::OK); // Correct response header
  return $payload; // Return updated payload
}

}