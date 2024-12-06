<?php

namespace Shadowinek\AdventOfCode\Year2017;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    public function runPart01(): int
    {
        $total = 0;
        $numbers = str_split($this->data[0]);

        foreach ($numbers as $i => $number) {
            if ($this->isValid($number, $numbers[$i + 1] ?? $numbers[0])) {
                $total += $number;
            }
        }

        return $total;
    }

    private function isValid(int $a, int $b): bool
    {
        return $a === $b;
    }

    public function runPart02(): int
    {
        $total = 0;
        $numbers = str_split($this->data[0]);
        $count = count($numbers);

        foreach ($numbers as $i => $number) {
            if ($this->isValid($number, $this->getValidPair($numbers, $i, $count))) {
                $total += $number;
            }
        }

        return $total;
    }

    private function getValidPair(array $numbers, int $index, int $count): int
    {
        $newIndex = $index + $count / 2;

        if (!isset($numbers[$newIndex])) {
            $newIndex -= $count;
        }

        return $numbers[$newIndex];
    }
}
