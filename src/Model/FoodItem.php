<?php

namespace Src\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    protected $table = 'food_items'; // Define your table name
    protected $primaryKey = 'id'; // Primary key (if not "id", change it)
    public $timestamps = true; // Enable created_at and updated_at
    protected $fillable = [
        'item_uuid', 'item_name', 'item_price_in_naira',
        'item_availabilty', 'created_at', 'updated_at'
    ];


}
