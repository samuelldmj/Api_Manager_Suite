<?php
//interacts with the DB,  by creating a Table users and inserting data set from Service folder.
namespace Src\Dal;

use RedBeanPHP\Facade as R;
use Src\Entity\User as UserEntity;

final class UserDal
{

    public const TABLE_NAME = 'users';

    /**
     * @throws \RedBeanPHP\RedException\SQL
     */
    public static function create(UserEntity $userEntity): int|string
    {
        $userBean = R::dispense(self::TABLE_NAME);

        $userBean->user_uuid = $userEntity->getUserUuid();
        $userBean->first_name = $userEntity->getFirstName();
        $userBean->last_name = $userEntity->getLastName();
        $userBean->email = $userEntity->getEmail();
        $userBean->phone = $userEntity->getPhoneNumber();
        $userBean->create_date = $userEntity->getCreatedDate();

        $id = R::store($userBean);

        R::close();

        return $id;

    }

    public static function getUserById(string $userUuid): ?array
    {

        //from the table, find the first occurence and then bind the user_uuid column with the searched uuid
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);
        return $userBean?->export();

    }

    public static function getAllUsers()
    {
        $userBean = R::findAll(self::TABLE_NAME);
        return $userBean;
    }

    public static function deleteUser(string $userUuid): bool
    {
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);

        return $userBean !== null && (bool) R::trash($userBean);
    }

}