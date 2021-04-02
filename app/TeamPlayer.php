<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    protected $fillable = [
    	'id',
    	'name',
    	'surname',
    	'age',
    	'height',
    	'weight',
    	'BMI',
    	'country_id',
    	'team_id',
    	'value',
    	'wage',
    	'goals',
    	'assists',
    	'matches',
    	'ntGoals',
    	'ntAssists',
    	'ntMatches',
    	'injuryDays',
    	'national'
    ];

    public function history()
    {
    	return $this->hasMany(PlayerHistory::class, 'player_id');
    }

    public function lastRecord()
    {
        $record = PlayerHistory::where('player_id', $this->id)
                            ->orderBy('created_at', 'desc')
                            ->first();
        return $record;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function fullName()
    {
        return $this->name . ' ' . $this->surname;
    }
}
