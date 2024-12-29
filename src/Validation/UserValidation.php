<?php
namespace Src\Validation;
use Respect\Validation\Validator as v;

class UserValidation 
{

    private const MINIMUM_NAME_LENGTH = 2;
    private const MAXIMUM_NAME_LENGTH = 40;
    private const MINIMUM_PASSWORD_LENGTH = 5;

    public function __construct(private mixed $data)
    {
       
    }

    public function isCreationSchemaValid()
    {

      $schemaValidator =  v::attribute('first', v::stringType()
      ->length(self::MINIMUM_NAME_LENGTH, self::MAXIMUM_NAME_LENGTH))
      ->attribute('last', v::stringType()
      ->length(self::MINIMUM_NAME_LENGTH, self::MAXIMUM_NAME_LENGTH))
      ->attribute('email', v::email(), mandatory:false)
      ->attribute('password', v::stringType())
      ->length(self::MINIMUM_PASSWORD_LENGTH)
      ->attribute('phoneNumber', v::phone(), mandatory: false);

      return $schemaValidator->validate($this->data);
    }

    public function isUpdateSchemaValid(){
        return $this->isCreationSchemaValid();
    }

    public function isDeleteUser(){
         return v::attribute('userUuid', v::uuid())->validate($this->data);   
    }

   
}