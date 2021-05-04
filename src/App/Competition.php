<?php declare(strict_types=1);

namespace App;

use App\Confrontation\Confrontation;
use App\Draw\DrawInterface;
use App\Match\Match;
use App\Output\Output;

class Competition
{
    const ROUND_FINAL = 'ROUND_FINAL';
    const ROUND_OF_16 = 'ROUND_OF_16';
    const ROUND_OF_8 = 'ROUND_OF_8';
    const ROUND_SEMIFINAL = 'ROUND_SEMIFINAL';

    private array $teams = [];
    private array $confrontations = [];
    private Team $winner;
    private Output $output;
    private DrawInterface $draw;

    public function __construct(array $teams, DrawInterface $draw, Output $output)
    {
        $this->teams = $teams;
        $this->output = $output;
        $this->draw = $draw;
    }


    public function getMatches(): array
    {
        $matches = [];
        /** @var Confrontation $confrontation */
        foreach ($this->confrontations as $confrontation) {
            $matches = array_merge($matches, $confrontation->getMatches());
        }

        return $matches;
    }

    public function getWinner(): Team
    {
        return $this->winner;
    }

    public function run()
    {
        $classifiedTeams = $this->playRound($this->teams, 'Octavos de final', self::ROUND_OF_16);
        $classifiedTeams = $this->playRound($classifiedTeams, 'Cuartos de final', self::ROUND_OF_8);
        $classifiedTeams = $this->playRound($classifiedTeams, 'Semifinales', self::ROUND_SEMIFINAL);
        $classifiedTeams = $this->playRound($classifiedTeams, 'Final', self::ROUND_FINAL);

        $this->winner = $classifiedTeams[0];

        $this->write('');
        $this->write(sprintf('Vencedor de la ESL: "%s"', $this->winner));
    }

    private function calculateWinner(Team $firstTeamName, Team $secondTeamName, int $firstTeamGoals, int $secondTeamGoals): Team
    {
        if ($firstTeamGoals > $secondTeamGoals) {
            $this->write(sprintf('    + winner "%s"', $firstTeamName));

            return $firstTeamName;
        }
        if ($firstTeamGoals < $secondTeamGoals) {
            $this->write(sprintf('    + winner "%s"', $secondTeamName));

            return $secondTeamName;
        }
        if (round(0, 1) == 0) {
            $this->write(sprintf('    + winner "%s"', $firstTeamName));

            return $firstTeamName;
        }
        $this->write(sprintf('    + winner "%s"', $secondTeamName));

        return $secondTeamName;
    }

    private function getWinnerOfDoubleGame(Confrontation $confrontation): Team
    {
        $this->write(sprintf('"%s" vs "%s', $confrontation->getHomeTeam(), $confrontation->getAwayTeam()));

        $firstMatch = new Match($confrontation->getHomeTeam(), $confrontation->getAwayTeam(), rand(0, 5), rand(0, 5));
        $this->write(sprintf('  - "%s" (%d) vs "%s" (%d)', $firstMatch->getHomeTeam(), $firstMatch->getHomeGoals(), $firstMatch->getAwayTeam(), $firstMatch->getAwayGoals()));

        $confrontation->addMatch($firstMatch);

        $secondMatch = new Match($confrontation->getAwayTeam(), $confrontation->getHomeTeam(), rand(0, 5), rand(0, 5));
        $this->write(sprintf('  - "%s" (%d) vs "%s" (%d)', $secondMatch->getHomeTeam(), $secondMatch->getHomeGoals(), $secondMatch->getAwayTeam(), $secondMatch->getAwayGoals()));

        $confrontation->addMatch($firstMatch);


        return $this->calculateWinner(
            $confrontation->getHomeTeam(),
            $confrontation->getAwayTeam(),
            $firstMatch->getHomeGoals() + $secondMatch->getAwayGoals(),
            $firstMatch->getAwayGoals() + $secondMatch->getHomeGoals(),
        );
    }

    private function playConfrontationAndGetWinner(Confrontation $confrontation, string $roundName): Team
    {
        switch ($roundName) {
            case self::ROUND_OF_16:
            case self::ROUND_OF_8:
            case self::ROUND_SEMIFINAL:
                return $this->getWinnerOfDoubleGame($confrontation, $roundName);
            case self::ROUND_FINAL:
                return $this->getWinnerOfSingleGame($confrontation, $roundName);
        }
    }

    private function getWinnerOfSingleGame(Confrontation $confrontation): Team
    {
        $this->write(sprintf('"%s" vs "%s', $confrontation->getHomeTeam(), $confrontation->getAwayTeam()));
        $match = new Match($confrontation->getHomeTeam(), $confrontation->getAwayTeam(), rand(0, 5), rand(0, 5));
        $this->write(sprintf('  - "%s" (%d) vs "%s" (%d)', $match->getHomeTeam(), $match->getHomeGoals(), $match->getAwayTeam(), $match->getAwayGoals()));

        $confrontation->addMatch($match);

        return $this->calculateWinner($confrontation->getHomeTeam(), $confrontation->getAwayTeam(), $match->getHomeGoals(), $match->getAwayGoals());
    }

    /**
     * draw participants and initializes confrontations
     */
    private function createRound(array $teams): array
    {
        $round = $this->draw->execute($teams);
        foreach ($round as $confrontation) {
            $this->confrontations[] = $confrontation;
        }

        return $round;
    }

    private function playRound(array $teams, string $roundTitle, string $roundType): array
    {
        $this->write('');
        $this->write($roundTitle);
        $round = $this->createRound($teams);
        $classifiedTeams = [];
        foreach ($round as $confrontation) {
            $classifiedTeams[] = $this->playConfrontationAndGetWinner($confrontation, $roundType);
        }

        return $classifiedTeams;
    }

    private function write(string $text): void
    {
        $this->output->write($text);
    }
}
