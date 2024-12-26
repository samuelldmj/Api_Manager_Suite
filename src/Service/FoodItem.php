<?php
namespace Src\Service;
use Src\Exceptions\InvalidValidationException;
use Src\Dal\FoodDal;
use Respect\Validation\Validator as v;
use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;
use Src\Entity\foodItem as FoodEntity;
use Src\Validation\foodValidation;

class foodItem {



    public function create(mixed $payload):object|array{

        $foodValidation = new FoodValidation($payload);

        //validating schema
      if($foodValidation->isCreationSchemaValid())
      {
        //assigns uuid to food when a data input is created.
        $foodId = Uuid::uuid4();

        $foodEntity = new FoodEntity;
        $foodEntity->setFoodUuid($foodId)
        ->setFoodName($payload->foodName)
        ->setFoodPrice($payload->foodPrice)
        ->setFoodAvailability($payload->foodAvailability)
        ->setCreatedDate(date('Y-m-d H:i:s'));

          //from DAL file.
            if(FoodDal::create(foodEntity: $foodEntity) === false){
              //when an entry into the database fails
              Http::setHeadersByCode(StatusCode::INTERNAL_SERVER_ERROR);
              $payload = [];
            }

            Http::getStatusCode(StatusCode::CREATED);
            return $payload;
        } 
        throw new InvalidValidationException();
      }


    public function retrieve(string $itemUuid): array
{
    // Validate the UUID format (version 4)
    if (!v::uuid(version: 4)->validate($itemUuid)) {
        throw new InvalidValidationException('Invalid UUID');
    }

    // Retrieve the item data using the given UUID
    $itemData = FoodDal::get($itemUuid);

    // If no item data is found, return an empty array
    if (!$itemData) {
        return [];
    }

    // Remove the 'id' field from the item data, as it's not needed for display
    unset($itemData['id']);
    
    return $itemData;
}

    


    public function retrieveAll():array{
        $allFoodItem  = FoodDal::getAllRec();
        // foreach($allItems as $i){
        //   unset($i['id']);
        // }
  
        $hidingfoodId = array_map(function(object $k){
          unset($k['id']);
          return $k;
        }, $allFoodItem);
          return $hidingfoodId;
      }

}

