<?php

namespace Src\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'user_uuid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'created_at',
        'updated_at',
        'password',
        'session_token',
        'last_session_time'
    ];

    

}
