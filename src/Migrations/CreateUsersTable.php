<?php

namespace Src\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends BaseMigration
{
    public function up()
    {
        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uuid')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('session_token')->nullable();
            $table->timestamp('last_session_time')->nullable();
            $table->timestamps();
        });

        echo "CreateUsersTable migration applied.";
    }

    public function down()
    {
        Capsule::schema()->dropIfExists('users');
        echo "CreateUsersTable migration rolled back." ;
    }
}