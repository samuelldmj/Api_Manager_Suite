<?php
//Business logic 
namespace Src\Service;

use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager as DB;

use PH7\JustHttp\StatusCode;
use PH7\PhpHttpResponseHeader\Http;
use Ramsey\Uuid\Uuid;

use Src\Validation\UserValidation;
use Src\Exceptions\InvalidValidationException;
use Respect\Validation\Validator as v;

use Src\Exceptions\EmailExistException;
use Src\Exceptions\InvalidCredentialException;
use Src\Exceptions\TokenSessionNotSetException;
use Src\Model\User as ModelUser;

use function Symfony\Component\Clock\now;
use Carbon\Carbon;

class User
{

  public function __construct(protected string $jwtSecretKey){

  }

 
  public function login(mixed $payload): ?array
{
    return DB::transaction(function () use ($payload) {
        $userValidation = new UserValidation($payload);

        if (!$userValidation->isLoginSchemaValid()) {
            throw new InvalidCredentialException();
        }

        $userEmail = ModelUser::where('email', $payload->email)->first();
        if (!$userEmail) {
            throw new InvalidValidationException("User not found.");
        }

        if (!password_verify($payload->password, $userEmail->password)) {
            throw new InvalidCredentialException();
        }

        // Generate JWT token
        $userFullName = "{$userEmail->first_name} {$userEmail->last_name}";
        $iss = $_ENV['APP_URL'];
        $iat = time();
        $nbf = $iat + 10;
        $exp = $iat + $_ENV['JWT_EXPIRATION_TIME'];
        $aud = 'myusers';
        $user_arr_data = ['email' => $userEmail->email, 'name' => $userFullName];

        $payload_info = [
            'iss' => $iss,
            'iat' => $iat,
            'nbf' => $nbf,
            'exp' => $exp,
            'aud' => $aud,
            'data' => $user_arr_data
        ];

        $algEncrypt = $_ENV['JWT_ALGORITHM_ENCRYPTION'];
        $jwtToken = JWT::encode($payload_info, $this->jwtSecretKey, $algEncrypt);

        // Update session token
        $userEmail->update([
            'session_token' => $jwtToken,
            'last_session_time' => Carbon::now()->toDateTimeString()
        ]);

        return [
            'message' => sprintf('%s successfully logged in', $userFullName),
            'token' => $jwtToken,
        ];
    });
}



public function create(mixed $payload): object|array
{
    $userValidation = new UserValidation($payload);

    // Validating schema
    if (!$userValidation->isCreationSchemaValid()) {
        throw new InvalidValidationException();
    }

    return DB::transaction(function () use ($payload) {
        $userId = Uuid::uuid4()->toString();

        $userEmail = ModelUser::where('email', $payload->email)->first();
        if ($userEmail) {
            throw new EmailExistException("This email {$userEmail->email} already exists");
        }

        $createUser = ModelUser::create([
            'user_uuid' => $userId,
            'first_name' => $payload->first,
            'last_name' => $payload->last,
            'email' => $payload->email,
            'phone' => $payload->phoneNumber,
            'created_at' => now(),
            'password' => password_hash($payload->password, PASSWORD_ARGON2I),
        ]);

        if (!$createUser) {
            throw new \Exception("User creation failed."); // Trigger rollback
        }

        $payload->userUuid = $userId;
        return $payload;
    });
}



  public function retrieveAll(): array
  {

  return ModelUser::all()->toArray();
  }

  public function retrieve(string $userUuid): array
  {
    if (v::uuid(version: 4)->validate($userUuid)) {
      $userData = ModelUser::where('item_uuid', $userUuid)->first();
      return $userData;
    }
    throw new InvalidValidationException('invalid UUID');
  }


//deleting the user from the db using url: post method on the body of the page
//incorrect payload will give a null response.
  public function remove(mixed $payload)
  {

    $userValidation = new UserValidation($payload);
    if ($userValidation->isDeleteUser()) {

     return  ModelUser::where('user_uuid', $payload->userUuid)->delete();
      
    }
  }


  public function update(mixed $payload): array
{
    return DB::transaction(function () use ($payload) {
        $userValidation = new UserValidation($payload);

        if (!$userValidation->isUpdateSchemaValid()) {
            throw new InvalidValidationException();
        }

        $user = ModelUser::where('user_uuid', $payload->userUuid)->firstOrFail();

        $user->update(array_filter([
            'first_name' => $payload->first ?? null,
            'last_name' => $payload->last ?? null,
            'email' => $payload->email ?? null,
            'phone' => $payload->phoneNumber ?? null,
            'updated_at' => now(),
        ]));

        return $user->toArray();
    });
}


}