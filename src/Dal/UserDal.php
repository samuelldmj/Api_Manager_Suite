<?php
//interacts with the DB,  by creating a Table users and inserting data set from Service folder.
namespace Src\Dal;

use RedBeanPHP\Facade as R;
use Src\Entity\User as UserEntity;

final class UserDal 
{

    public const TABLE_NAME = 'users' ;

    /**
     * @throws \RedBeanPHP\RedException\SQL
     */
    public static function create(UserEntity $userEntity): int|string{
        $userBean = R::dispense(self::TABLE_NAME);

        $userBean->user_uuid = $userEntity->getUserUuid();
        $userBean->first_name = $userEntity->getFirstName();
        $userBean->last_name = $userEntity->getLastName();
        $userBean->email = $userEntity->getEmail();
        $userBean->phone = $userEntity->getPhoneNumber();
        $userBean->create_date = $userEntity->getCreatedDate();

        $id = R::store($userBean);

        return $id;

    }
}