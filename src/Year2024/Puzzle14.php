<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle14 extends AbstractPuzzle
{
    private array $robots;
    private array $map = [];
    private int $maxRow = 7;
    private int $maxCol = 11;
    private array $quadrants;

    public function runPart01(): int
    {
        $this->loadData();
        $this->getQuadrants();
        $total = 1;

        foreach ($this->robots as $robot) {
            $this->calculatePosition($robot);
        }

        foreach ($this->quadrants as $quadrant) {
            $totalInQuadrant = 0;

            [$minRow, $maxRow, $minCol, $maxCol] = array_values($quadrant);

            for ($y = $minRow; $y <= $maxRow; $y++) {
                for ($x = $minCol; $x <= $maxCol; $x++) {
                    if (isset($this->map[$y][$x])) {
                        $totalInQuadrant += $this->map[$y][$x];
                    }
                }
            }

            $total *= $totalInQuadrant;
        }

        return $total;
    }

    private function getQuadrants(): void
    {
        $halfCol = floor($this->maxCol / 2);
        $halfRow = floor($this->maxRow / 2);

        $this->quadrants = [
            1 => [
                'minRow' => 0,
                'maxRow' => $halfRow - 1,
                'minCol' => 0,
                'maxCol' => $halfCol - 1,
            ],
            2 => [
                'minRow' => 0,
                'maxRow' => $halfRow - 1,
                'minCol' => $halfCol + 1,
                'maxCol' => $this->maxCol - 1,
            ],
            3 => [
                'minRow' => $halfRow + 1,
                'maxRow' => $this->maxRow - 1,
                'minCol' => 0,
                'maxCol' => $halfCol - 1,
            ],
            4 => [
                'minRow' => $halfRow + 1,
                'maxRow' => $this->maxRow - 1,
                'minCol' => $halfCol + 1,
                'maxCol' => $this->maxCol - 1,
            ],
        ];
    }

    private function calculatePosition(array $robot, int $seconds = 100): void
    {
        [$x, $y] = $robot['pos'];
        [$dx, $dy] = $robot['vel'];

        $newX = $x + $seconds * $dx;
        $newY = $y + $seconds * $dy;

        if ($newX > $this->maxCol || $newX < 0) {
            $newX = $newX % $this->maxCol;

            if ($newX < 0) {
                $newX = $this->maxCol + $newX;
            }
        }

        if ($newY > $this->maxRow || $newY < 0) {
            $newY = $newY % $this->maxRow;

            if ($newY < 0) {
                $newY = $this->maxRow + $newY;
            }
        }

        $this->addRobot($newX, $newY);
    }

    private function addRobot(int $x, int $y): void
    {
        if (isset($this->map[$y][$x])) {
            $this->map[$y][$x]++;
        } else {
            $this->map[$y][$x] = 1;
        }
    }

    public function runPart02(): int
    {
        $this->loadData();
        $this->getQuadrants();
        $i = 1;

        while (true) {
            $this->map = [];

            foreach ($this->robots as $robot) {
                $this->calculatePosition($robot, $i);
            }

            foreach ($this->map as $row => $rows) {
                if (array_sum($rows) > 30) {
                    $together = 0;
                    for ($j=0;$j<$this->maxCol;$j++) {
                        if (isset($this->map[$row][$j])) {
                            $together++;

                            if ($together > 10) {
//                                $this->print();

                                return $i;
                            }
                        } else {
                            $together = 0;
                        }
                    }
                }
            }

            $i++;
        }
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            [$posArray, $velArray] = explode(' ', $line);
            $pos = $this->parseNumbers($posArray);
            $this->robots[] = [
                'pos' => $pos,
                'vel' => $this->parseNumbers($velArray),
            ];

            if (max($pos) > 11) {
                $this->maxRow = 103;
                $this->maxCol = 101;
            }
        }
    }

    private function print(): void
    {
        for ($i = 0; $i < $this->maxRow; $i++) {
            for ($j = 0; $j < $this->maxCol; $j++) {
                echo isset($this->map[$i][$j]) ? '#' : ' ';
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }
}
