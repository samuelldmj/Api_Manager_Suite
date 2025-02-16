<?php


namespace Src\Migrations;

abstract class BaseMigration
{
    abstract public function up();
    abstract public function down();
}
