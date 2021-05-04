<?php declare(strict_types=1);


namespace App\Confrontation;


use App\Match\Match;
use App\Team;

class Confrontation
{
    private Team $homeTeam;
    private Team $awayTeam;
    private array $matches = [];

    public function __construct(Team $homeTeam, Team $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function getMatches(): array
    {
        return $this->matches;
    }

    public function addMatch(Match $match)
    {
        $this->matches[] = $match;
    }
}
