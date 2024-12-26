<?php
namespace Src\Dal;
use RedBeanPHP\Facade as R;
use RedBeanPHP\RedException\SQL;
use Src\Entity\foodItem;


final class FoodDal {

    //redbean would make a table, if it doesn't exist
    //using underscore to create a table via red bean is invalid 
    public const TABLE_NAME = 'fooditems';

    public static function create(foodItem $foodEntity): bool|int|string
    {
        $itemBean = R::dispense(self::TABLE_NAME);

        $itemBean->food_uuid = $foodEntity->getFoodUuid();
        $itemBean->food_name = $foodEntity->getFoodName();
        $itemBean->food_price_in_naira = $foodEntity->getFoodPrice();
        $itemBean->food_availabilty = $foodEntity->getFoodAvailability();
        $itemBean->create_date = $foodEntity->getCreatedDate();

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


    public static function get(string $itemUuid): ?array
    {

        //from the table, find the first occurence and then bind the item_uuid column with the searched uuid
        $itemBean = R::findOne(self::TABLE_NAME, 'food_uuid = ?', [$itemUuid]);
        return $itemBean?->export();

    }

    public static function getAllRec()
    {
        $itemBean = R::findAll(self::TABLE_NAME);
        return $itemBean;
    }

}