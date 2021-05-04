<?php declare(strict_types=1);

namespace Tests\App;

use App\Competition;
use App\Draw\Rand;
use App\Output\Output;
use App\Team;
use PHPUnit\Framework\TestCase;

class CompetitionTest extends TestCase
{
    public function test_competition()
    {
        $competition = new Competition(
            [
                new Team('AC Milan'),
                new Team('Arsenal FC'),
                new Team('Atlético de Madrid'),
                new Team('Borussia Dortmund'),
                new Team('Chelsea FC'),
                new Team('FC Barcelona'),
                new Team('FC Bayern München'),
                new Team('FC Internazionale Milano'),
                new Team('Juventus'),
                new Team('Liverpool FC'),
                new Team('Manchester City FC'),
                new Team('Manchester United FC'),
                new Team('Paris Saint-Germain'),
                new Team('Real Madrid CF'),
                new Team('Sevilla FC'),
                new Team('Tottenham Hotspur'),
            ], new Rand(), new Output(),
        );
        $competition->run();

        $this->assertInstanceOf(Team::class, $competition->getWinner());
        $this->assertCount(29, $competition->getMatches());
    }
}
