<?php
namespace Src\Routes;



use PH7\JustHttp\StatusCode;
use Src\Service\foodItem;
use Src\Exceptions\InvalidValidationException;

enum ItemActionRoute: string
{
    case RETRIEVE = 'retrieve';
    case RETRIEVE_ALL = 'retrieveAll';
    case CREATE = 'create';

    //this getResponse methods interact with the foodItem class based on the action value passed as query params
    public function getResponse(): string
    {
        // Get input data and decode it as payload
        $data = file_get_contents("php://input");
        $payload = json_decode($data);

        // Initialize a foodItem instance fom the business logic.
        $item = new foodItem();

        // Set foodItemId from the query parameter in the url if available
        $itemId = $_REQUEST['id'] ?? '';

        // Handle case where 'id' is required but not provided
        if ($this === self::RETRIEVE && $itemId === null) {
            throw new InvalidValidationException('Food item ID is required for this action.', StatusCode::BAD_REQUEST);
        }

        // Match the action and call the corresponding method
        //self points to the enums foodItemActionRoute then get map to the appropriate method in the foodItem class.
        //for example if the  action = retrieve; $this will be foodItemActionRoute::RETRIEVE
        $response = match ($this) {
                // self::CREATE => $item->create($payload),
            self::RETRIEVE_ALL => $item->retrieveAll(),
            self::RETRIEVE => $item->retrieve($itemId),
        };


        return json_encode($response);
    }
}

// Determine the action based on query parameters then execute the getResponse()
$action = $_REQUEST['action'] ?? null;

$itemActionRoute = ItemActionRoute::tryFrom($action);
if ($itemActionRoute) {
    echo $itemActionRoute->getResponse();
} else {
    require_once "route-not-found.php";
}


























