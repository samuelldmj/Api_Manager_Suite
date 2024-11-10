<?php 
namespace Src\Endpoints;

use Src\Validation\UserValidation;
use Src\Exceptions\InvalidValidationException;
use Respect\Validation\Validator as v;
class User 
{
    public  ?string $userId;
    public function __construct(
        public string $name, 
        public string $email, 
        public string $phoneNumber)
    { 

    }

    public function create(mixed $data):object{

        $userValidation = new  UserValidation($data);

        //validating schema
      if($userValidation->isCreationSchemaValid())
      {
        return $data;
      }

      throw new InvalidValidationException('invalid payload');
    }
    public function retrieveAll():array{
        return [];
    }

    public function retrieve(string $userId): self
    {
        if (v::uuid(version:4)->validate($userId)) {
            $this->userId = $userId;
            return $this;
        }
        throw new InvalidValidationException('invalid UUID');
    }

    public function remove(string $userId):self{
      if (v::uuid(version:4)->validate($userId)) {
        $this->userId = $userId;
        return $this;
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