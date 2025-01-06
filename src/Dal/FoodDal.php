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

    // public static function create(foodItem $foodEntity): bool|int|string
    // {
    //     $itemBean = R::dispense(self::TABLE_NAME);

    //     $itemBean->food_uuid = $foodEntity->getFoodUuid();
    //     $itemBean->food_name = $foodEntity->getFoodName();
    //     $itemBean->food_price_in_naira = $foodEntity->getFoodPrice();
    //     $itemBean->food_availabilty = $foodEntity->getFoodAvailability();
    //     $itemBean->create_date = $foodEntity->getCreatedDate();

    //     try {
    //         if (!R::testConnection()) {
    //             die('Unable to connect to the database.');
    //         }

    //         return R::store($itemBean);
    //     } catch (SQL $e) {
    //         return false;
    //     } finally {
    //         R::close();
    //     }
    // }  


    public static function createDefaultItem(ItemEntity $itemEntity){
        $itemBean = R::dispense(self::TABLE_NAME);

        $itemBean->item_name = $itemEntity->getItemName();
        $itemBean->item_price = $itemEntity->getItemPrice();
        $itemBean->item_availability = $itemEntity->getItemAvailabilty();
        $itemBean->itemUuid = $itemEntity->getItemUuid();

        return R::store($itemBean);
    }

  

    // public static function get(string $itemUuid): ?array
    // {

    //     //from the table, find the first occurence and then bind the item_uuid column with the searched uuid
    //     $itemBean = R::findOne(self::TABLE_NAME, 'food_uuid = ?', [$itemUuid]);
    //     return $itemBean?->export();

    // }


    public static function get(string $itemUuid): ItemEntity
    {

        //from the table, find the first occurence and then bind the item_uuid column with the searched uuid
        $itemBean = R::findOne(self::TABLE_NAME, 'item_uuid = ?', [$itemUuid]);
        return (new ItemEntity())->unserialize($itemBean?->export());

    }



    // public static function getAllRec()
    // {
    //     $itemBean = R::findAll(self::TABLE_NAME);
    //     return $itemBean;
    // }


    public static function getAllRec()
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


}