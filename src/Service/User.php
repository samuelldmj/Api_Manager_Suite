<?php
//Business logic 
namespace Src\Service;

use Firebase\JWT\JWT;
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

class User
{

  public function __construct(protected string $jwtSecretKey){

  }

  //Todo problem with login
  //todo check readme Txt file for more info
  public function login(mixed $payload): ?array
  {
      $userValidation = new UserValidation($payload);
      
      // Validate the login schema
      if (!$userValidation->isLoginSchemaValid()) {
          throw new InvalidCredentialException();
      }
  
      // Find the user by email
      $userEmail = ModelUser::where('email', $payload->email)->first();
      if (!$userEmail) {
          throw new InvalidValidationException("User not found.");
      }
  
      // Verify the password
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
      $user_arr_data = [
          'email' => $userEmail->email,
          'name' => $userFullName
      ];
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
  
      try {
          // Update the session token and last session time using Eloquent
          $userEmail->update([
              'session_token' => $jwtToken,
              'last_session_time' => time()
          ]);
      } catch (\Exception $e) {
          var_dump("Failed to update session token: " . $e->getMessage());
          throw new TokenSessionNotSetException();
      }
  
      // Return the success response
      return [
          'message' => sprintf('%s successfully logged in', $userFullName),
          'token' => $jwtToken,
      ];
  }


  public function create(mixed $payload): object|array
  {

    $userValidation = new UserValidation($payload);

    //validating schema
    if ($userValidation->isCreationSchemaValid()) {
      //assigns uuid to user when a data input is created.
      $userId = Uuid::uuid4()->toString();

      $userEmail = ModelUser::where('email', $payload->email)->first();

      if ($userEmail?->email) {
        throw new EmailExistException("This email {$userEmail->email} address already exists");
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
        //when an entry into the database fails
        Http::setHeadersByCode(StatusCode::BAD_REQUEST);
        $payload = [];
      }

      Http::getStatusCode(StatusCode::CREATED);     
      $payload->userUuid = $userId;
      return $payload;
    }
    throw new InvalidValidationException();
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
  }

}