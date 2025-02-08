<?php
declare(strict_types=1);
namespace Src\Service\ServiceInterface;

interface ItemServiceInterface
{
    public function create(object $payload): array;
    public function retrieve(string $id): array;
    public function retrieveAll(): array;
    public function update(string $id, object $payload): object|array;

    public function remove(string $id);
}