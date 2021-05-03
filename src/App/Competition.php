<?php declare(strict_types=1);

namespace App;

use App\Confrontation\Confrontation;
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
    private string $winner = '';
    private Output $output;

    public function __construct(array $teams, Output $output)
    {
        $this->teams = $teams;
        $this->output = $output;
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

    public function getWinner(): string
    {
        return $this->winner;
    }

    public function run()
    {
        $this->write('');
        $this->write('Octavos de final');
        $round = $this->createRound($this->teams);
        $classifiedTeams = [];
        foreach ($round as $confrontation) {
            $classifiedTeams[] = $this->calculate($confrontation, self::ROUND_OF_16);
        }

        $this->write('');
        $this->write('Cuartos de final');
        $round = $this->createRound($classifiedTeams);
        $classifiedTeams = [];
        foreach ($round as $confrontation) {
            $classifiedTeams[] = $this->calculate($confrontation, self::ROUND_OF_8);
        }

        $this->write('');
        $this->write('Semifinales');
        $round = $this->createRound($classifiedTeams);
        $classifiedTeams = [];
        foreach ($round as $confrontation) {
            $classifiedTeams[] = $this->calculate($confrontation, self::ROUND_SEMIFINAL);
        }

        $this->write('');
        $this->write('Final');
        $round = $this->createRound($classifiedTeams);
        foreach ($round as $confrontation) {
            $this->winner = $this->calculate($confrontation, self::ROUND_FINAL);
        }

        $this->write('');
        $this->write(sprintf('Vencedor de la ESL: "%s"', $this->winner));
    }

    private function calculateWinner(string $firstTeamName, string $secondTeamName, int $firstTeamGoals, int $secondTeamGoals): string
    {
        if ($firstTeamGoals > $secondTeamGoals) {
            $this->write(sprintf('    + winner "%s"', $firstTeamName));

            return $firstTeamName;
        } else {
            if ($firstTeamGoals < $secondTeamGoals) {
                $this->write(sprintf('    + winner "%s"', $secondTeamName));

                return $secondTeamName;
            } else {
                if (round(0, 1) == 0) {
                    $this->write(sprintf('    + winner "%s"', $firstTeamName));

                    return $firstTeamName;
                } else {
                    $this->write(sprintf('    + winner "%s"', $secondTeamName));

                    return $secondTeamName;
                }
            }
        }
    }

    private function getWinnerOfDoubleGame(Confrontation $confrontation): string
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

    /**
     * This method play de confrontation and return the winner of a double or single match
     */
    private function calculate(Confrontation $confrontation, string $roundName): string
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

    private function getWinnerOfSingleGame(Confrontation $confrontation): string
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
        shuffle($teams);
        $round = [];
        for ($i = 0; $i <= count($teams) - 1; $i += 2) {
            $confrontation = new Confrontation($teams[$i], $teams[$i + 1]);
            $round[] = $confrontation;
            $this->confrontations[] = $confrontation;
        }

        return $round;
    }

    private function write(string $text): void
    {
        $this->output->write($text);
    }
}
