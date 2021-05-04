<?php declare(strict_types=1);


namespace App\Match;


use App\Team;

class Match
{
    private Team $homeTeam;
    private Team $awayTeam;
    private int $homeGoals;
    private int $awayGoals;

    public function __construct(Team $homeTeam, Team $awayTeam, int $homeGoals, int $awayGoals)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function getHomeGoals(): int
    {
        return $this->homeGoals;
    }

    public function getAwayGoals(): int
    {
        return $this->awayGoals;
    }
}
