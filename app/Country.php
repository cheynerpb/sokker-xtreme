<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
    	'id',
    	'name',
    	'currencyRate'
    ];

    public function getCurrentMoney($international_money)
    {
    	return $international_money / $this->currencyRate;
    }
}
