<?php

namespace Shadowinek\AdventOfCode\Year2018;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $strings = [];

    public function runPart01(): int
    {
        $this->loadData();

        return 0;
    }

    public function runPart02(): int
    {
        $this->loadData();

        return 0;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->strings[] = str_split($line);
        }
    }
}
