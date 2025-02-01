<?php
namespace Src\Dal;

use Ramsey\Uuid\Uuid;
use RedBeanPHP\Facade as R;
use RedBeanPHP\RedException\SQL;
use Src\Entity\foodItem;
use Src\Entity\Item as ItemEntity;


final class FoodDal {

    //redbean would make a table, if it doesn't exist
    //using underscore to create a table via red bean is invalid 
    public const TABLE_NAME = 'items';
    public static function createFood(foodItem $foodEntity): bool|int|string {
        $itemBean = R::dispense(self::TABLE_NAME);
    
        $itemBean->item_uuid = $foodEntity->getItemUuid();
        $itemBean->item_name = $foodEntity->getItemName();
        $itemBean->item_price_in_naira = $foodEntity->getItemPrice();
        $itemBean->item_availabilty = $foodEntity->getItemAvailabilty();
        $itemBean->create_date = $foodEntity->getCreatedDate();
        $itemBean->category = 'food'; 
        $itemBean->updated_at = date('Y-m-d H:i:s'); 
    
        try {
            if (!R::testConnection()) {
                die('Unable to connect to the database.');
            }
            return R::store($itemBean);
        } catch (SQL $e) {
            return false;
        } finally {
            R::close();
        }
    }
     


    // public static function createDefaultItem(ItemEntity $itemEntity){
    //     $itemBean = R::dispense(self::TABLE_NAME);

    //     $itemBean->item_name = $itemEntity->getItemName();
    //     $itemBean->item_price = $itemEntity->getItemPrice();
    //     $itemBean->item_availability = $itemEntity->getItemAvailabilty();
    //     $itemBean->itemUuid = $itemEntity->getItemUuid();
    //     $itemBean->create_date = $itemEntity->getCreatedDate();

    //     return R::store($itemBean);
    // }

  

    // public static function get(string $itemUuid): ?array
    // {

    //     //from the table, find the first occurence and then bind the item_uuid column with the searched uuid
    //     $itemBean = R::findOne(self::TABLE_NAME, 'food_uuid = ?', [$itemUuid]);
    //     return $itemBean?->export();

    // }


    public static function getFoodById(string $itemUuid): ItemEntity
    {
        $itemBean = R::findOne(self::TABLE_NAME, 'item_uuid = ?', [$itemUuid]);
    
        if (!$itemBean) {
            throw new \Exception("Item not found");
        }
    
        $exportedData = $itemBean->export();
        return (new ItemEntity())->unserialize($exportedData);
    }
    



    // public static function getAllRec()
    // {
    //     $itemBean = R::findAll(self::TABLE_NAME);
    //     return $itemBean;
    // }


    public static function getAllFoods()
    {
        $itemBean = R::findAll(self::TABLE_NAME);

        if(!$itemBean && count($itemBean)){
            return [];
        }
    
        return array_map(function(object $item):array{
          $itemEntity = (new ItemEntity)->unserialize($item?->export());

            return [
                'foodUuid' => $itemEntity->getItemUuid(),
                'price' => $itemEntity->getItemPrice(),
                'availabilty' => $itemEntity->getItemAvailabilty(),
                'foodName' => $itemEntity->getItemName()
            ];

        }, $itemBean);
        
    }

    public static function updateFoodById(string $itemUuid, foodItem $foodEntity) {
        $itemBean = R::findOne(self::TABLE_NAME, 'item_uuid = ?', [$itemUuid]);
    
        if ($itemBean) {
            $itemName = $foodEntity->getItemName();
            $itemPrice = $foodEntity->getItemPrice();
            $itemAvailability = $foodEntity->getItemAvailabilty();
    
            if ($itemName) $itemBean->item_name = $itemName;
            if ($itemPrice) $itemBean->item_price_in_naira = $itemPrice;
            if ($itemAvailability) $itemBean->item_availabilty = $itemAvailability;
    
            // Automatically set updated_at to the current time
            $itemBean->updated_at = date('Y-m-d H:i:s');
        }
    
        try {
            return R::store($itemBean);
        } catch (SQL $e) {
            return false;
        } finally {
            R::close();
        }
    }
    

    public static function getItemsByCategory(string $category): array {
        $itemBeans = R::find(self::TABLE_NAME, 'category = ?', [$category]);
    
        if (!$itemBeans) {
            return [];
        }
    
        return array_map(function ($itemBean) {
            $itemEntity = (new ItemEntity())->unserialize($itemBean->export());
    
            return [
                'uuid' => $itemEntity->getItemUuid(),
                'name' => $itemEntity->getItemName(),
                'price' => $itemEntity->getItemPrice(),
                'availability' => $itemEntity->getItemAvailabilty(),
                'created_at' => $itemEntity->getCreatedDate(),
                'updated_at' => $itemBean->updated_at,
            ];
        }, $itemBeans);
    }
    

}