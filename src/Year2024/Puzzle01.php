<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    private array $leftNumbers = [];
    private array $rightNumbers = [];

    public function runPart01(): int
    {
        $this->loadData();

        sort($this->leftNumbers);
        sort($this->rightNumbers);

        $distance = 0;

        foreach ($this->leftNumbers as $i => $leftNumber) {
            $distance += abs($leftNumber - $this->rightNumbers[$i]);
        }

        return $distance;
    }

    public function runPart02(): int
    {
        $this->loadData();
        $occurrences = array_count_values($this->rightNumbers);
        $similarity = 0;

        foreach ($this->leftNumbers as $leftNumber) {
            $similarity += $leftNumber * ($occurrences[$leftNumber] ?? 0);
        }

        return $similarity;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $numbers = $this->parseNumbers($line);

            $this->leftNumbers[] = (int)$numbers[0];
            $this->rightNumbers[] = (int)$numbers[1];
        }
    }
}
