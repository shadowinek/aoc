<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle05 extends AbstractPuzzle
{
    private array $passes = [];

    public function runPart01(): int
    {
        $this->loadData();

        $ids = [];

        foreach ($this->passes as $pass) {
            $ids[] = $this->getSeatId($pass);
        }

        return max($ids);
    }

    public function runPart02(): int
    {
        $this->loadData();

        $ids = [];

        foreach ($this->passes as $pass) {
            $ids[] = $this->getSeatId($pass);
        }

        for ($i = min($ids); $i < max($ids); $i++) {
            if (!in_array($i, $ids)) {
                return $i;
            }
        }

        return -1;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->passes[] = $line;
        }
    }

    private function getSeatId(mixed $pass): int
    {
        $rows = [0, 127];
        $cols = [0, 7];

        foreach (str_split($pass) as $letter) {
            switch ($letter) {
                case 'F':
                    $rows[1] = $rows[0] + intdiv($rows[1] - $rows[0], 2);
                    break;
                case 'B':
                    $rows[0] = $rows[1] - intdiv($rows[1] - $rows[0], 2);
                    break;
                case 'L':
                    $cols[1] = $cols[0] + intdiv($cols[1] - $cols[0], 2);
                    break;
                case 'R':
                    $cols[0] = $cols[1] - intdiv($cols[1] - $cols[0], 2);
                    break;
            }
        }

        return $rows[0] * 8 + $cols[0];
    }
}
