<?php


namespace App\Draw;


use App\Confrontation\Confrontation;

class Rand implements DrawInterface
{
    public function execute(array $teams): array
    {
        shuffle($teams);
        $round = [];
        for ($i = 0; $i <= count($teams) - 1; $i += 2) {
            $confrontation = new Confrontation($teams[$i], $teams[$i + 1]);
            $round[] = $confrontation;
        }

        return $round;
    }
}
