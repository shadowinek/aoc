<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle10 extends AbstractPuzzle
{
    private array $adapters = [];
    public function runPart01(): int
    {
        $this->loadData();

        $joltage = 0;
        $oneDiff = 0;
        $threeDiff = 0;

        foreach ($this->adapters as $adapter) {
            $diff = $adapter - $joltage;
            $joltage = $adapter;

            if ($diff === 1) {
                $oneDiff++;
            } elseif ($diff === 3) {
                $threeDiff++;
            }
        }

        return $oneDiff * $threeDiff;
    }

    public function runPart02(): int
    {
        $this->loadData();


    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->adapters[] = (int)$line;
        }

        $this->adapters[] = max($this->adapters) + 3;

        sort($this->adapters);
    }
}
