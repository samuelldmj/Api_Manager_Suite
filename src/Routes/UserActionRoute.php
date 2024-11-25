<?php
namespace Src\Routes;



use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Src\Service\User;
use Src\Exceptions\InvalidValidationException;

enum UserActionRoute: string
{
    case CREATE = 'create';
    case RETRIEVE = 'retrieve';
    case RETRIEVE_ALL = 'retrieveAll';
    case REMOVE = 'remove';
    case UPDATE = 'update';

    //this getResponse methods interact with the User class based on the action value passed as query params
    public function getResponse(): string
    {
        // Get input data and decode it as payload
        $data = file_get_contents("php://input");
        $payload = json_decode($data);

        // Initialize a User instance with default data
        $user = new User();
        
        // Set userId from query parameter if available
        $userId = $_REQUEST['id'] ?? null;

        try {
            // Match the action and call the corresponding method
            //self points to the enums UserActionRoute then get map to the appropriate method in the User class.
            //for example if the  action = retrieve; $this will be UserActionRoute::RETRIEVE
            $response = match ($this) {
                self::CREATE => $user->create($payload),
                self::RETRIEVE_ALL => $user->retrieveAll(),
                self::RETRIEVE => $user->retrieve($userId),
                self::UPDATE => $user->update($payload),
                self::REMOVE => $user->remove($userId),
                default => $user->retrieve($userId)
            };
        } catch (InvalidValidationException $e) {
            // Handle errors and set HTTP status to BAD_REQUEST
            Http::setHeadersByCode(StatusCode::BAD_REQUEST);
            $response = [
                'errors' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ]
            ];
        }

        return json_encode($response);
    }
}

// Determine the action based on query parameters then execute the getResponse()
$action = $_REQUEST['action'] ?? null;

$userActionRoute = match ($action) {
    'create' => UserActionRoute::CREATE,
    'retrieve' => UserActionRoute::RETRIEVE,
    'remove' => UserActionRoute::REMOVE,
    'update' => UserActionRoute::UPDATE,
    default => UserActionRoute::RETRIEVE_ALL,
};

echo $userActionRoute->getResponse();

























