<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class ContestEdition extends Model
{
    protected $table = "contest_edition";

    protected $fillable = [
        'name',
        'active',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['winner', 'groups', 'team'];

    public function players()
    {
        return $this->hasMany(Player::class, 'contest_id');
    }

    public function getGroupsAttribute()
    {
        return $this->players->where('active', true)->groupBy('sk_player_id');
    }

    public function getWinnerAttribute()
    {
        $query = "SELECT MAX(players.score) as max_score, players.player_name
    			  FROM players
                  WHERE players.contest_id = '{$this->id}' AND active = true
    			  GROUP BY players.player_name
    			  ORDER BY max_score DESC LIMIT 1";

        $result = DB::select($query);

        if (count($result) > 0) {
            $winner = $result[0]->player_name . '  [  ' . $result[0]->max_score . '  ]';
        } else {
            $winner = 'No ha comenzado el concurso';
        }
        return $winner;
    }

    public function getTeamAttribute()
    {
        $query = "SELECT MAX(players.score) as max_score, players.team
    			  FROM players
                  WHERE players.contest_id = '{$this->id}' AND active = true
    			  GROUP BY players.team
    			  ORDER BY max_score DESC LIMIT 1";

        $result = DB::select($query);

        if (count($result) > 0) {
            $team = $result[0]->team;
        } else {
            $team = 'No ha comenzado el concurso';
        }
        return $team;
    }

    public static function boot()
    {
        //execute the parent's boot method
        parent::boot();

        // delete your related models
        static::deleting(function ($player) {
            $player->players()->delete();
        });
    }
}
