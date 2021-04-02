<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = "players";

    protected $fillable = [
        'contest_id',
        'country_id',
        'sk_player_id',
        'team',
        'player_name',
        'player_age',
        'stamina',
        'pace',
        'technique',
        'passing',
        'keeper',
        'defender',
        'playmaker',
        'striker',
        'score'
    ];

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function edition()
    {
        return $this->hasOne(ContestEdition::class, 'contest_id');
    }
}
