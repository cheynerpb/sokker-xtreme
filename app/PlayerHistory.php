<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerHistory extends Model
{
    protected $table = "team_players_history";

    protected $fillable = [
    	'player_id',
    	'skillForm',
    	'skillExperience',
    	'skillTeamwork',
    	'skillDiscipline',
    	'skillStamina',
    	'skillPace',
    	'skillTechnique',
    	'skillPassing',
    	'skillKeeper',
    	'skillDefending',
    	'skillPlaymaking',
    	'skillScoring'
    ];

    public function player()
    {
    	return $this->belongsTo(TeamPlayer::class, 'player_id');
    }
}
