<?php declare(strict_types=1);

namespace Tests\App;

use App\Competition;
use App\Output\Output;
use PHPUnit\Framework\TestCase;

class CompetitionTest extends TestCase
{
    public function test_competition()
    {
        $competition = new Competition(
            [
                'AC Milan',
                'Arsenal FC',
                'Atlético de Madrid',
                'Borussia Dortmund',
                'Chelsea FC',
                'FC Barcelona',
                'FC Bayern München',
                'FC Internazionale Milano',
                'Juventus',
                'Liverpool FC',
                'Manchester City FC',
                'Manchester United FC',
                'Paris Saint-Germain',
                'Real Madrid CF',
                'Sevilla FC',
                'Tottenham Hotspur',
                new Output(),
            ]
        );
        $competition->run();

        $this->assertIsString($competition->getWinner());
        $this->assertCount(29, $competition->getMatches());
    }
}
