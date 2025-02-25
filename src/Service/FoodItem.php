<?php

namespace Src\Service;

use Ramsey\Uuid\Uuid;
use PH7\JustHttp\StatusCode;
use Src\Validation\foodValidation;
use PH7\PhpHttpResponseHeader\Http;
use Respect\Validation\Validator as v;
use Src\Models\FoodItem as ModelsFoodItem;
use Src\Exceptions\InvalidValidationException;
use Src\Service\ServiceInterface\ItemServiceInterface;

class foodItem implements ItemServiceInterface
{


  public function create(object $payload): array
  {
    $foodValidation = new FoodValidation($payload);

    // Validating schema
    if ($foodValidation->isCreationSchemaValid()) {
      // Assigns UUID to food when a data input is created
      $foodId = Uuid::uuid4();

      // Create the food item using Eloquent
      $foodItem = ModelsFoodItem::create([
        'item_uuid' => $foodId,
        'item_name' => $payload->foodName,
        'item_price_in_naira' => $payload->foodPrice,
        'item_availabilty' => $payload->foodAvailability,
        'create_date' => ModelsFoodItem::now(),
      ]);

      Http::getStatusCode(StatusCode::CREATED);
      return [
        'status' => 'success',
        'data' => $foodItem,
      ];
    }

    throw new InvalidValidationException();
  }



  public function retrieve(string $itemUuid): array
  {
    // Validate the UUID format (version 4)
    if (v::uuid(version: 4)->validate($itemUuid)) {

      $itemData = ModelsFoodItem::where('item_uuid', $itemUuid)->first();
      return $itemData;
    }

    // If no item data is found, return an empty array
    return [];
  }



  public function retrieveAll(): array
  {
    return  ModelsFoodItem::all()->toArray();
  }


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

    $foodItem = ModelsFoodItem::where('item_uuid', $id)->first();

    if (!$foodItem) {
      return [];
    }

    // Update only provided fields
    $foodItem->update(array_filter([
      'item_name' => $payload->foodName ?? null,
      'item_price_in_naira' => $payload->foodPrice ?? null,
      'item_availabilty' => $payload->foodAvailability ?? null,
      'updated_at' => ModelsFoodItem::now(),
    ]));

    return $foodItem;
  }


  public function remove(string $id)
  {
    if (!v::uuid(version: 4)->validate($id)) {
      throw new InvalidValidationException('Invalid UUID');
    }

    ModelsFoodItem::where('item_uuid', $id)->delete();
    return "deleted";
  }
}
