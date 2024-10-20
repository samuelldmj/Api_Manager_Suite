<?php 
namespace Src\Endpoints;
class User 
{
    public function __construct(public string $name, public string $email, public string $phoneNumber)
    {
        
    }

    public function create(){}
    public function retrieveAll():array{}

    public function retrieve():User{}

    public function remove():bool{}

    public function update():User{}
}