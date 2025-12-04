<?php

namespace Shadowinek\AdventOfCode\Year2025;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle04 extends AbstractPuzzle
{
    private array $map = [];
    private int $maxRow;
    private int $maxCol;
    private const string PAPER = '@';
    private const string REMOVED = 'x';
    private array $directions = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [0, -1],
        [0, 1],
        [1, -1],
        [1, 0],
        [1, 1],
    ];

     private function loadData(): void
    {
        $this->maxRow = count($this->data);

        foreach ($this->data as $i => $line) {
            $this->map[$i] = str_split($line);
        }

        $this->maxCol = count($this->map[$i]);
    }

    public function runPart01(): int
    {
        $this->loadData();

        $this->print();

        $total = 0;

        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $cell) {
                if ($cell === self::PAPER && $this->checkSurrounding($row, $col)) {
                    $total++;
                }
            }
        }

        return $total;
    }

    private function checkSurrounding(int $row, int $col): bool
    {
        $total = 0;

        foreach ($this->directions as $direction) {
            list($dRow, $dCol) = $direction;

            if (isset($this->map[$row + $dRow][$col + $dCol]) && $this->map[$row + $dRow][$col + $dCol] === self::PAPER) {
                $total++;
            }
        }

        return $total < 4;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $this->print();

        $count = $this->countPaper();
        $total = 0;

        while (true) {
            foreach ($this->map as $row => $cols) {
                foreach ($cols as $col => $cell) {
                    if ($cell === self::PAPER && $this->checkSurrounding($row, $col)) {
                        $total++;
                        $this->map[$row][$col] = self::REMOVED;
                    }
                }
            }

            $newCount = $this->countPaper();

            if ($newCount === $count) {
                break;
            } else {
                $count = $newCount;
            }
        }

        return $total;
    }

    private function countPaper(): int
    {
        $total = 0;

        foreach ($this->map as $cols) {
            foreach ($cols as $cell) {
                if ($cell === self::PAPER) {
                    $total++;
                }
            }
        }

        return $total;
    }

    private function print(): void
    {
        for ($i = 0; $i < $this->maxRow; $i++) {
            for ($j = 0; $j < $this->maxCol; $j++) {
                echo $this->map[$i][$j];
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }
}
