<?php
//interacts with the DB,  by creating a Table users and inserting data set from Service folder.
namespace Src\Dal;

use RedBeanPHP\Facade as R;
use RedBeanPHP\RedException\SQL;
use Src\Entity\User as UserEntity;

final class UserDal
{
    // Constant defining the table name in the database
    public const TABLE_NAME = 'users';

    /**
     * Create a new user record in the database.
     *
     * @param UserEntity $userEntity The user entity containing the data to insert.
     * @throws \RedBeanPHP\RedException\SQL If a database error occurs.
     * @return int|string The UUID of the created user or false in case of an error.
     */
    public static function create(UserEntity $userEntity): int|string
    {
        // Create a new bean (record) for the "users" table
        $userBean = R::dispense(self::TABLE_NAME);

        // Assign values to the bean's properties from the UserEntity object
        $userBean->user_uuid = $userEntity->getUserUuid();
        $userBean->first_name = $userEntity->getFirstName();
        $userBean->last_name = $userEntity->getLastName();
        $userBean->email = $userEntity->getEmail();
        $userBean->password = $userEntity->getPassword();
        $userBean->phone = $userEntity->getPhoneNumber();
        $userBean->create_date = $userEntity->getCreatedDate();

        try {
            // Save the bean to the database and return its auto-incremental ID
            $redBeanIncrementalId = R::store($userBean);
        } catch (SQL $e) {
            // If an exception occurs during the save, return false
            return false;
        } finally {
            // Close the database connection
            R::close();
        }

        // Reload the bean using its ID to get the full saved record
        $userBean = R::load(self::TABLE_NAME, $redBeanIncrementalId);

        // Return the user UUID from the saved record
        return $userBean->user_uuid;
    }

    /**
     * Retrieve a user record by its UUID.
     *
     * @param string $userUuid The UUID of the user to retrieve.
     * @return array|null The user record as an associative array or null if not found.
     */
    public static function getUserById(string $userUuid): ?array
    {
        // Find the user record where "user_uuid" matches the provided UUID
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);
        // Export the record as an associative array or return null if not found
        return $userBean?->export();
    }

    /**
     * Retrieve all user records from the database.
     *
     * @return array The list of user records or an empty array if none are found.
     */
    public static function getAllUserRec()
    {
        // Retrieve all records from the "users" table
        $userBean = R::findAll(self::TABLE_NAME);
        // Return the records or an empty array if none exist
        return !$userBean ? [] : $userBean;
    }

    /**
     * Delete a user record by its UUID.
     *
     * @param string $userUuid The UUID of the user to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public static function deleteUserRec(string $userUuid): bool
    {
        // Find the user record by its UUID
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);
        // Delete the record if it exists and return the result
        return $userBean !== null && (bool) R::trash($userBean);
    }

    /**
     * Update a user record with new data.
     *
     * @param string $userUuid The UUID of the user to update.
     * @param UserEntity $userEntity The user entity containing updated data.
     * @return int|false The ID of the updated record or false if the update fails.
     */
    public static function update(string $userUuid, UserEntity $userEntity)
    {
        // Find the user record by its UUID
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);

        // Update the fields if they exist in the entity
        if ($userBean) {
            $firstName = $userEntity->getFirstName();
            $lastName = $userEntity->getLastName();
            $email = $userEntity->getEmail();
            $phone = $userEntity->getPhoneNumber();

            if ($firstName) $userBean->first_name = $firstName;
            if ($lastName) $userBean->last_name = $lastName;
            if ($email) $userBean->email = $email;
            if ($phone) $userBean->phone = $phone;
        }

        try {
            // Save the updated record
            return R::store($userBean);
        } catch (SQL $e) {
            // Return false if an exception occurs
            return false;
        } finally {
            // Close the database connection
            R::close();
        }
    }

    /**
     * Check if an email already exists in the database.
     *
     * @param string $email The email to check.
     * @return bool True if the email exists, false otherwise.
     */
    public static function doesEmailExist($email)
    {
        // Find the first record with the matching email
        return R::findOne(self::TABLE_NAME, 'email = ?', [$email]) !== null;
    }

    /**
     * Retrieve a user record by email.
     *
     * @param string $email The email to search for.
     * @return object|null The user record as an object or null if not found.
     */
    public static function getByEmail($email): ?object
    {
        // Find the first record with the matching email
        return R::findOne(self::TABLE_NAME, 'email = ?', [$email]);
    }

    /**
     * Set a JWT session token for a user.
     *
     * @param string $jwtToken The JWT token to assign.
     * @param string $userUuid The UUID of the user.
     */
    public static function setUserJwtToken(string $jwtToken, string $userUuid): void
    {
        // Find the user record by its UUID
        $userBean = R::findOne(self::TABLE_NAME, 'user_uuid = ?', [$userUuid]);
        // Update the session token and last session time
        $userBean->session_token = $jwtToken;
        $userBean->last_session_time = time();
        // Save the updated record
        R::store($userBean);
    }
}
