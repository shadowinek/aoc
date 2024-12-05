<?php

namespace Shadowinek\AdventOfCode\Year2017;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private array $numbers = [];

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->numbers as $numbers) {
            $min = min($numbers);
            $max = max($numbers);

            $total += $max - $min;
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->numbers as $numbers) {
            $total += $this->findDivisible($numbers);
        }

        return $total;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->numbers[] = $this->parseNumbers($line);
        }
    }

    private function findDivisible(mixed $numbers): int
    {
        foreach ($numbers as $i => $a) {
            foreach ($numbers as $j => $b) {
                if ($i === $j) {
                    continue;
                }

                if ($a % $b === 0) {
                    return $a / $b;
                }
            }
        }

        return 0;
    }

}
