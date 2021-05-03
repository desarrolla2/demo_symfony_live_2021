<?php

namespace App\Output;


class Output implements OutputInterface
{
    public function write(string $text): void
    {
        echo $text.PHP_EOL;
    }
}
