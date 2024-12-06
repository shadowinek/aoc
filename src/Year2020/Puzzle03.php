<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $map;
    private const string TREE = '#';
    private int $maxRow;
    private int $maxCol;

    public function runPart01(): int
    {
        $this->loadData();

        return $this->checkSlope(3, 1);
    }

    private function checkSlope(int $right, int $down): int
    {
        $x = $y = $trees = 0;

        while (true) {
            $x += $down;
            $y += $right;

            if ($x >= $this->maxRow) {
                break;
            }

            if ($y >= $this->maxCol) {
                $y -= $this->maxCol;
            }

            if ($this->map[$x][$y] === self::TREE) {
                $trees++;
            }
        }

        return $trees;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $slopes = [
            [1, 1],
            [3, 1],
            [5, 1],
            [7, 1],
            [1, 2],
        ];

        $trees = 1;

        foreach ($slopes as $slope) {
            $trees *= $this->checkSlope($slope[0], $slope[1]);
        }

        return $trees;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->map[] = str_split($line);
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
    }
}
