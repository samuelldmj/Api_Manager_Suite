<?php 


namespace Src\Model;

use Illuminate\Database\Eloquent\Model;

class SecretKey extends Model
{
    protected $table = 'secretkeys';

    protected $fillable = ['secret_key'];

    public $timestamps = false;
}
