<?php
namespace Src\Routes;

use PH7\JustHttp\StatusCode;
use Src\Exceptions\InvalidValidationException;

enum ItemActionRoute: string
{
    case RETRIEVE = 'retrieve';
    case RETRIEVE_ALL = 'retrieveAll';
    case CREATE = 'create';
    case UPDATE = 'update';

    case DELETE = 'delete';

    public function getResponse(string $type): string
    {
        $data = file_get_contents("php://input");
        $payload = json_decode($data);

        // Dynamically resolve service
        $itemService = \Src\Service\Factory\ItemFactory::getService($type); 
        $itemId = $_REQUEST['id'] ?? '';

        if ($this === self::RETRIEVE && empty($itemId)) {
            throw new InvalidValidationException('Item ID is required for this action.', StatusCode::BAD_REQUEST);
        }

        $response = match ($this) {
            self::CREATE => $itemService->create($payload),
            self::RETRIEVE => $itemService->retrieve($itemId),
            self::RETRIEVE_ALL => $itemService->retrieveAll(),
            self::UPDATE => $itemService->update($itemId, $payload),
            self::DELETE => $itemService->remove($itemId)
        };

        return json_encode($response);
    }
}

// Determine the action and type, then execute the getResponse()
$action = $_REQUEST['action'] ?? null;
// Default to a generic type
$type = $_REQUEST['type'] ?? 'generic'; 

$itemActionRoute = ItemActionRoute::tryFrom($action);
if ($itemActionRoute) {
    echo $itemActionRoute->getResponse($type);
} else {
    require_once "route-not-found.php";
}
