<?php
//interacts with the DB,  by creating a Table users and inserting data set from Service folder.
namespace Src\Dal;

use RedBeanPHP\Facade as R;
use RedBeanPHP\RedException\SQL;
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
        $userBean->password = $userEntity->getPassword();
        $userBean->phone = $userEntity->getPhoneNumber();
        $userBean->create_date = $userEntity->getCreatedDate();

        try {
            return R::store($userBean);
        } catch(SQL $e){
            return false;
        } finally {
            R::close();
        }
    }

    public static function getById(string $userUuid): ?array
    {

        //from the table, find the first occurence and then bind the user_uuid column with the searched uuid
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);
        return $userBean?->export();

    }


    public static function getAllRec()
    {
        $userBean = R::findAll(self::TABLE_NAME);
        return $userBean;
    }

    public static function deleteRec(string $userUuid): bool
    {
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);

        return $userBean !== null && (bool) R::trash($userBean);
    }


    public static function update(string $userUuid, UserEntity $userEntity)
    {
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);
        
        if($userBean){
           $firstName = $userEntity->getFirstName();
           $lastName = $userEntity->getLastName();
           $email = $userEntity->getEmail();
           $phone = $userEntity->getPhoneNumber();


           if($firstName){
            $userBean->first_name = $firstName;
           }

           if($lastName){
            $userBean->last_name = $lastName;
           }

           if($email){
            $userBean->email = $email;
           }

           if($phone){
            $userBean->phone = $phone;
           }
        }

        try {
            return R::store($userBean);
        } catch(SQL $e){
            return false;
        } finally {
            R::close();
        }
    }

    public static function doesEmailExist($email){

        return R::findOne(self::TABLE_NAME, 'email = ?', [$email] ) !== null;
    }

    public static function getByEmail($email): ?array{
        $userBean =  R::findOne(self::TABLE_NAME, 'email = ?', [$email] );
        return $userBean?->export();
    }

}