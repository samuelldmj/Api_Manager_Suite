<?php 
namespace Src\Endpoints;
class User 
{
    public int $userId;
    public function __construct(public string $name, public string $email, public string $phoneNumber)
    {
        
    }

    public function create():self{
        return $this;
    }
    public function retrieveAll():array{
        return [];
    }

    public function retrieve(int $userId): self{
        $this->userId = $userId;
        return $this;
    }

    public function remove():bool{
        return true;
    }

    public function update():self{
        return $this;
    }
}