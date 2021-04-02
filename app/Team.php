<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
    	'id',
    	'team_name',
    	'country_id',
    	'region_id',
    	'foundation_date',
    	'rank',
    	'stadium_name',
    	'money',
    	'fanclubCount',
    	'fanclubMood',
    	'juniorsMax',
        'updated_at'
    ];

    public function players()
    {
        return $this->hasMany(TeamPlayer::class, 'team_id');
    }
}
