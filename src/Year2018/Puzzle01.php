<?php

namespace Shadowinek\AdventOfCode\Year2018;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    private array $operations = [];

    public function runPart01(): int
    {
        $this->loadData();

        $frequency = 0;

        foreach ($this->operations as $operation) {
            $frequency += $operation;
        }

        return $frequency;
    }

    public function runPart02(): int
    {
        $this->loadData();
        $reached = [];

        $frequency = 0;

        while (true) {
            foreach ($this->operations as $operation) {
                $frequency += $operation;
                echo $frequency . PHP_EOL;

                if (isset($reached[$frequency])) {
                    return $frequency;
                } else {
                    $reached[$frequency] = true;
                }
            }
        }
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->operations[] = $this->parseNumbers($line)[0];
        }
    }
}
