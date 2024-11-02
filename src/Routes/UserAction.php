<?php
namespace Src\Routes;

use Exception;
use Src\Endpoints\User;
use Src\Exceptions\InvalidValidationException;
// require_once dirname(__DIR__) . '/endpoints/User.php';

enum  UserAction: string
{
    case CREATE = 'create';
    case RETRIEVE = 'retrieve';
    case RETRIEVE_ALL = 'retrieveAll';
    case REMOVE = 'remove';
    case UPDATE = 'update';
    public function getResponse():string
    {   

        $data = file_get_contents("php://input");
        $payload = json_decode($data);

     
    
        $user = new User('Sam', 'sam@hotmail.com', '0808437284' );

        // $userId = !empty($_GET['user_id']) ? $_GET['user_id'] : null;
        $user->userId =  !empty( $_GET['user_id']) ? (int) $_GET['user_id'] : 0 ; 

        try {
            $response =  match($this)
            {
              self::CREATE => $user->create($payload),
              self::RETRIEVE_ALL => $user->retrieveAll(),
              self::RETRIEVE =>$user->retrieve($user->userId),
              self::UPDATE =>$user->update($payload),
              self::REMOVE =>$user->remove(),     
              default =>$user->retrieve($user->userId)
            };

        } catch (InvalidValidationException | Exception $e) {
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


$action = $_GET['action'] ?? null; 

$userAction = match ($action){
    'create' => UserAction::CREATE, //201
    'retrieve' => UserAction::RETRIEVE, //200
    'remove' => UserAction::REMOVE, //204
    'update' => UserAction::UPDATE, //
    default => UserAction::RETRIEVE_ALL //200
};

echo $userAction->getResponse();
























