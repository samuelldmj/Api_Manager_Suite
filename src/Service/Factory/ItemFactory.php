<?php
namespace Src\Service\Factory;

use Src\Service\foodItem;
use Src\Service\ElectronicItemService;
use Src\Service\GenericItemService;
use Src\Service\ServiceInterface\ItemServiceInterface;

class ItemFactory
{
    public static function getService(string $type): ItemServiceInterface
    {
        return match (strtolower($type)) {
            'food' => new foodItem(),
            // 'electronics' => new ElectronicItemService(),
            default => new GenericItemService(),
        };
    }
}
