<?php
namespace Src\Service;

use Src\Service\ServiceInterface\ItemServiceInterface;

class GenericItemService implements ItemServiceInterface
{
    public function create(object $payload): array
    {
        throw new \Exception('Create operation not supported for this item type.');
    }

    public function retrieve(string $id): array
    {
        throw new \Exception('Retrieve operation not supported for this item type.');
    }

    public function retrieveAll(): array
    {
        throw new \Exception('RetrieveAll operation not supported for this item type.');
    }

    public function update(string $id, object $payload): array
    {
        throw new \Exception('Update operation not supported for this item type.');
    }

    public function remove(string $id): array
    {
        throw new \Exception('Delete operation not supported for this item type.');
    }
}
