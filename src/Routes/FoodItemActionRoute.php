<?php
namespace Src\Routes;



use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Src\Service\foodItem;
use Src\Exceptions\InvalidValidationException;

enum FoodItemActionRoute: string
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
        $foodItem = new foodItem();

        // Set foodItemId from the query parameter in the url if available
        $foodItemId = $_REQUEST['id'] ?? '';

        try {
            // Handle case where 'id' is required but not provided
            if ($this === self::RETRIEVE && $foodItemId === null) {
                throw new InvalidValidationException('Food item ID is required for this action.', StatusCode::BAD_REQUEST);
            }

            // Match the action and call the corresponding method
            //self points to the enums foodItemActionRoute then get map to the appropriate method in the foodItem class.
            //for example if the  action = retrieve; $this will be foodItemActionRoute::RETRIEVE
            $response = match ($this) {
                self::CREATE => $foodItem->create($payload),
                self::RETRIEVE_ALL => $foodItem->retrieveAll(),
                self::RETRIEVE => $foodItem->retrieve($foodItemId),
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

$foodItemActionRoute = FoodItemActionRoute::tryFrom($action);
if ($foodItemActionRoute) {
    echo $foodItemActionRoute->getResponse();
} else {
    require_once "route-not-found.php";
}


























