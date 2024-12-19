<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle09 extends AbstractPuzzle
{
    private array $numbers = [];
    private const PREAMBLE_TEST = 5;
    private const PREAMBLE = 25;
    private int $preamble;

    public function runPart01(): int
    {
        $this->loadData();

        return $this->findWeakness();
    }

    private function findWeakness(): int
    {
        for ($i = $this->preamble; $i < count($this->numbers); $i++) {
            $current = $this->numbers[$i];

            if (!$this->isValid($current, $i)) {
                return $current;
            }
        }

        return -1;
    }

    private function isValid(int $number, int $index): bool
    {
        for ($i = $index - $this->preamble; $i < $index; $i++) {
            for ($j = $i + 1; $j < $index; $j++) {
                if ($this->numbers[$i] + $this->numbers[$j] === $number) {
                    return true;
                }
            }
        }

        return false;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $weakness = $this->findWeakness();

        while (true) {
            $start = array_shift($this->numbers);
            $found = [$start];

            foreach ($this->numbers as $number) {
                $found[] = $number;
                $start += $number;

                if ($start === $weakness) {
                    return min($found) + max($found);
                }

                if ($start > $weakness) {
                    break;
                }
            }
        }
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->numbers[] = (int)$line;
        }

        if (count($this->numbers) < self::PREAMBLE) {
            $this->preamble = self::PREAMBLE_TEST;
        } else {
            $this->preamble = self::PREAMBLE;
        }
    }
}
