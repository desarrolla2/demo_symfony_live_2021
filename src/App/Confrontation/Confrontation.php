<?php declare(strict_types=1);


namespace App\Confrontation;


use App\Match\Match;

class Confrontation
{
    private string $homeTeam;
    private string $awayTeam;
    private array $matches = [];

    public function __construct(string $homeTeam, string $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
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
