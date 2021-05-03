<?php declare(strict_types=1);


namespace App\Match;


class Match
{
    private string $homeTeam;
    private string $awayTeam;
    private int $homeGoals;
    private int $awayGoals;

    public function __construct(string $homeTeam, string $awayTeam, int $homeGoals, int $awayGoals)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
    }

    public function getHomeTeam(): string
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): string
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
