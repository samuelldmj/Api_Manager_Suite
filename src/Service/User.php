<?php 
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
    public function create(mixed $data):object{

        $userValidation = new  UserValidation($data);

        //validating schema
      if($userValidation->isCreationSchemaValid())
      {
        //assigns uuid to user when a data input is created.
        $userId = Uuid::uuid4();

        $userEntity = new UserEntity;
        $userEntity->setUserUuid($userId)
        ->setFirstName($data->first)
        ->setLastName($data->last)
        ->setEmail($data->email)
        ->setPhoneNumber($data->phoneNumber)
        ->setCreatedDate(date('Y-m-d H:i:s'));

        try {
          //from DAL file.
            UserDal::create(userEntity: $userEntity);
        } catch (\RedBeanPHP\RedException\SQL $exception) {
          Http::getStatusCode(StatusCode::INTERNAL_SERVER_ERROR);
          $data = [];
        }
        return $data;
      }

      throw new InvalidValidationException('invalid Data');
    }
    public function retrieveAll():array{
      $allUsers  = UserDal::getUsers();
      // foreach($allUsers as $k){
      //   unset($k['id']);
      // }

      $hidingUserId = array_map(function(object $k){
        unset($k['id']);
        return $k;
      }, $allUsers);
        return $hidingUserId;
    }

    public function retrieve(string $userId): array
    {
        if (v::uuid(version:4)->validate($userId)) {
            $userData = UserDal::getUserById($userId);
            //removing user id and uuid from the display field.
            unset($userData['id']);
            return $userData;
        }
        throw new InvalidValidationException('invalid UUID');
    }

    public function remove(string $userId):bool{
      if (v::uuid(version:4)->validate($userId)) {
        // $this->userId = $userId;
        return true;
    }
    throw new InvalidValidationException('invalid UUID');
    }

    public function update(mixed $data):object{
        $userValidation = new  UserValidation($data);

        //validating schema
      if($userValidation->isCreationSchemaValid())
      {
        return $data;
      }
      throw new InvalidValidationException();
    }
}