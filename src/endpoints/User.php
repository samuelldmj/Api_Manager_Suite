<?php 
namespace Src\Endpoints;

use Src\Exceptions\InvalidValidationException;
use Respect\Validation\Validator as v;
class User 
{
    public int $userId;
    public function __construct(public string $name, public string $email, public string $phoneNumber)
    {
        
    }

    public function create(mixed $data):object{
        $minimumNameLength = 2;
        $maxmumNameLength = 60;

      $schemaValidator =  v::attribute('first', v::stringType()
      ->length($minimumNameLength, $maxmumNameLength))
      ->attribute('last', v::stringType()
      ->length($minimumNameLength, $maxmumNameLength))
      ->attribute('email', v::email(), mandatory:false)
      ->attribute('phoneNumber', v::phone(), mandatory: false);

      if($schemaValidator->validate($data)){
        return $data;
      }

      throw new InvalidValidationException();
    }
    public function retrieveAll():array{
        return [];
    }

    public function retrieve(int $userId): self{
        $this->userId = $userId;
        return $this;
    }

    public function remove():bool{
        return true;
    }

    public function update(mixed $data):self{
        return $this;
    }
}