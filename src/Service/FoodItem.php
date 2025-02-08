<?php
namespace Src\Service;
use Src\Entity\Item as ItemEntity;
use Src\Exceptions\InvalidValidationException;
use Src\Dal\FoodDal;
use Respect\Validation\Validator as v;
use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;
use Src\Entity\foodItem as FoodEntity;
use Src\Service\ServiceInterface\ItemServiceInterface;
use Src\Validation\foodValidation;

class foodItem implements ItemServiceInterface
{


  public function create(object $payload): array
  {
    $foodValidation = new FoodValidation($payload);

    // Validating schema
    if ($foodValidation->isCreationSchemaValid()) {
      // Assigns UUID to food when a data input is created
      $foodId = Uuid::uuid4();

      $foodEntity = new FoodEntity();
      $foodEntity
        ->setItemUuid($foodId)
        ->setItemName($payload->foodName)
        ->setItemPrice($payload->foodPrice)
        ->setItemAvailabilty($payload->foodAvailability)
        ->setCreatedDate(date('Y-m-d H:i:s'));

      // From DAL file
      $statusCode = StatusCode::CREATED; // 201
      Http::setHeadersByCode($statusCode);

      // Simulate getting status after client validation
      $clientStatusCode = $statusCode;

      if ($clientStatusCode !== StatusCode::OK) {
        FoodDal::createFood(foodEntity: $foodEntity);
      } else {
        return [
          'status' => 'error',
          'message' => 'Invalid status code. Data not inserted.'
        ];
      }
      
      Http::getStatusCode(StatusCode::CREATED);

      // Convert $payload to an array before returning it
      return (array) $payload;
    }

    throw new InvalidValidationException();
  }



  public function retrieve(string $itemUuid): array
  {
    // Validate the UUID format (version 4)
    if (v::uuid(version: 4)->validate($itemUuid)) {
      if ($itemData = FoodDal::getFoodById($itemUuid)) {
        if ($itemData->getItemUuid()) {
          return [
            'itemUuid' => $itemData->getItemUuid(),
            'itemName' => $itemData->getItemName(),
            'price' => $itemData->getItemPrice(),
            'availabilty' => $itemData->getItemAvailabilty(),
            'createdDate' => $itemData->getCreatedDate()
          ];
        }
      }

    }


    // If no item data is found, return an empty array
    return [];
  }



  public function retrieveAll(): array
  {
    $allFoodItem = FoodDal::getAllFoods();
    // foreach($allItems as $i){
    //   unset($i['id']);
    // }

    $hidingfoodId = array_map(function (object $k) {
      unset($k['id']);
      return $k;
    }, $allFoodItem);
    return $hidingfoodId;
  }



  //retrieving all when createDefault method is set in the fooddal file
//     public function retrieveAll():array{
//         $allFoodItem  = FoodDal::getAllFoods();

  //         if(count($allFoodItem) === 0){
//           $itemEntity = new ItemEntity();

  //           $itemUuid = Uuid::uuid4()->toString();
//           $itemEntity->setItemName('Bread and Beans')
//           ->setItemPrice(100.99)
//           ->setItemUuid($itemUuid)
//           ->setItemAvailabilty(true);

  //           FoodDal::createDefaultItem($itemEntity);
//         }

  //         return $allFoodItem;

  // }

  public function update(string $id, object $payload): array
{
    // Validate the payload using a validation class
    $userValidation = new foodValidation($payload);

    $id = $payload->itemUUid; 
    if (!v::uuid(version: 4)->validate($id)) {
        throw new InvalidValidationException('Invalid UUID');
    }

    if (!$userValidation->isUpdateSchemaValid()) {
        throw new InvalidValidationException('Invalid data schema for update.');
    }

    // Initialize the food entity
    $foodEntity = new FoodEntity();

    // Set properties of the food entity based on the payload
    if (!empty($payload->foodName)) {
        $foodEntity->setItemName($payload->foodName);
    }

    if (!empty($payload->foodPrice)) {
        $foodEntity->setItemPrice($payload->foodPrice);
    }

    if (!empty($payload->foodAvailability)) {
        $foodEntity->setItemAvailabilty($payload->foodAvailability);
    }

    // Call the DAL to update the food item using the provided ID
    $updateResult = FoodDal::updateFoodById($id, $foodEntity);

    if ($updateResult === false) {
        Http::setHeadersByCode(StatusCode::NOT_FOUND);
        return [
            'status' => 'error',
            'message' => 'Food item not found or update failed.',
            'data' => ['id' => $id, 'provided_payload' => $payload]
        ];
    }

    // If successful, return the updated payload with a success message
    Http::setHeadersByCode(StatusCode::OK);
    return [
        'status' => 'success',
        'message' => 'Food item updated successfully.',
        'data' => $payload
    ];
}


  public function remove(string $id){
      if(!v::uuid(version: 4)->validate($id)){
        throw new InvalidValidationException('Invalid UUID');
      }

       FoodDal::deleteFoodItem($id);
       return "deleted";
  }


}