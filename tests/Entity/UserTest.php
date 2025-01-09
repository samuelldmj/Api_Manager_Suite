<?php
declare(strict_types=1);
namespace Src\Tests\Entity;



use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Src\Entity\User as UserEntity;
final class UserTest extends TestCase
{

    private UserEntity $userEntity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userEntity = new UserEntity;

    }
    public function testSequentialId(): void
    {
    $expectedId = 55;
        $this->userEntity->setUserSequentialId($$expectedId);
        $this->assertSame($$expectedId, $this->userEntity->getUserSequentialId());
    }

    public function testUuid():void {
        $expectedUuid = Uuid::uuid4()->toString();
        $this->userEntity->setUserUuid($expectedUuid);
        $this->assertSame($expectedUuid, $this->userEntity->getUserUuid());
    }

   
}