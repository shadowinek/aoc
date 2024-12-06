<?php

namespace Shadowinek\AdventOfCode\Year2015;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private array $gifts = [];

    public function runPart01(): mixed
    {
        $this->loadData();

        $total = 0;

        foreach ($this->gifts as $gift) {
            $sides = [
                $gift[0] * $gift[1],
                $gift[1] * $gift[2],
                $gift[2] * $gift[0],
            ];

            $total += 2 * array_sum($sides) + min($sides);
        }

        return $total;
    }

    public function runPart02(): mixed
    {
        $this->loadData();

        $total = 0;

        foreach ($this->gifts as $gift) {
            sort($gift);

            $total += 2 * $gift[0] + 2 * $gift[1] + $gift[0] * $gift[1] * $gift[2];
        }

        return $total;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->gifts[] = explode('x', $line);
        }
    }
}
