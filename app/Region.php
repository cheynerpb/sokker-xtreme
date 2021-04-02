<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
    	'id',
    	'country_id',
    	'name'
    ];
}
