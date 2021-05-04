<?php

namespace App\Draw;

interface DrawInterface
{
    public function execute(array $teams): array;
}
