<?php
namespace Src\Validation;
use Respect\Validation\Validator as v;

class FoodValidation 
{

    private const MINIMUM_NAME_LENGTH = 2;
    private const MAXIMUM_NAME_LENGTH = 40;
    public function __construct(private mixed $data)
    {
       
    }

    public function isCreationSchemaValid()
    {

      $schemaValidator =  v::attribute('foodName', v::stringType()
      ->length(self::MINIMUM_NAME_LENGTH, self::MAXIMUM_NAME_LENGTH))
      ->attribute('foodPrice', v::intType())
      ->attribute('foodAvailability', v::boolType());
      return $schemaValidator->validate($this->data);
    }

    public function isUpdateSchemaValid(){
        return $this->isCreationSchemaValid();
    }

    public function isDeleteFoodItem(){
         return v::attribute('userUuid', v::uuid())->validate($this->data);   
    }

}