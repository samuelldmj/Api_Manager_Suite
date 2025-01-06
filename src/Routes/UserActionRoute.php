<?php
namespace Src\Routes;


use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Src\Exceptions\EmailExistException;
use Src\Service\User;
use Src\Exceptions\NotFoundException;
use Src\Exceptions\TokenSessionNotSetException;
use Src\Service\SecretKey;

enum UserActionRoute: string
{
    case CREATE = 'create';
    case RETRIEVE = 'retrieve';
    case RETRIEVE_ALL = 'retrieveAll';
    case REMOVE = 'remove';
    case UPDATE = 'update';
    case LOGIN = 'login';

    //this getResponse methods interact with the User class based on the action value passed as query params
    public function getResponse(): string
    {
        // Get input data and decode it as payload
        $data = file_get_contents("php://input");
        $payload = json_decode($data);

        // Initialize a User instance and generate secretkey to login and for the business logic.
        $secretKey = SecretKey::getJwtSecretKey();
        $user = new User($secretKey);
        
        // Set userUuid from the query parameter in the url if available
        $userUuid = $_REQUEST['id'] ?? null;

        try {
            $expectedHttpMethod = match($this){
                self::LOGIN => CustomHttp::POST_METHOD,
                self::CREATE => CustomHttp::POST_METHOD,
                self::RETRIEVE_ALL => CustomHttp::GET_METHOD,
                self::RETRIEVE => CustomHttp::GET_METHOD,
                self::REMOVE => CustomHttp::DELETE_METHOD,
                self::UPDATE => CustomHttp::PUT_METHOD
            };


            if(!CustomHttp::httpMethodValidator($expectedHttpMethod)){
                throw new NotFoundException('Http Method is incorrect.');
              }
 
            
            // Match the action and call the corresponding method
            //self points to the enums UserActionRoute then get map to the appropriate method in the User class.
            //for example if the  action = retrieve; $this will be UserActionRoute::RETRIEVE
            $response = match ($this) {
                self::CREATE => $user->create($payload),
                self::RETRIEVE_ALL => $user->retrieveAll(),
                self::RETRIEVE => $user->retrieve($userUuid),
                self::UPDATE => $user->update($payload),
                self::LOGIN => $user->login($payload),
               // self::REMOVE => $user->remove($userId),

               //logic for post method
                self::REMOVE => $user->remove($payload)
            };
        } catch(TokenSessionNotSetException $e){
            Http::setHeadersByCode(StatusCode::INTERNAL_SERVER_ERROR);
            $response = [
              'errors' => [
                    'message' => $e->getMessage(),
                ]
              ];

        } catch ( EmailExistException $e) {
            // Handle errors and set HTTP status to BAD_REQUEST
            Http::setHeadersByCode(StatusCode::BAD_REQUEST);
            $response = [
                'errors' => [
                    'message' => $e->getMessage(),
                ]
            ];
        }

        return json_encode($response);
    }
}

// Determine the action based on query parameters then execute the getResponse()
$action = $_REQUEST['action'] ?? null;

$userActionRoute = userActionRoute::tryFrom($action);
if ($userActionRoute){
    echo $userActionRoute->getResponse();
}  else {
    require_once "route-not-found.php";
}


























