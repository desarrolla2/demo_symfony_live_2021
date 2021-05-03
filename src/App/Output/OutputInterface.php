<?php

namespace App\Output;

interface OutputInterface
{
    public function write(string $text): void;
}
