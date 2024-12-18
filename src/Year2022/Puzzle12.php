<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Closure;
use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle12 extends AbstractPuzzle
{
    private array $values = [
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 5,
        'f' => 6,
        'g' => 7,
        'h' => 8,
        'i' => 9,
        'j' => 10,
        'k' => 11,
        'l' => 12,
        'm' => 13,
        'n' => 14,
        'o' => 15,
        'p' => 16,
        'q' => 17,
        'r' => 18,
        's' => 19,
        't' => 20,
        'u' => 21,
        'v' => 22,
        'w' => 23,
        'x' => 24,
        'y' => 25,
        'z' => 26,
        self::START => 0,
        self::END => 0,
    ];

    private const START = 'S';
    private const END = 'E';

    private array $directions = [
        [0, 1],
        [1, 0],
        [0, -1],
        [-1, 0],
    ];

    private array $map = [];
    private array $destinations = [];
    private array $start;
    private array $end;
    private int $maxRow;
    private int $maxCol;
    private array $a = [];

    public function runPart01(): int
    {
        $this->loadData();

        return $this->aStar();
    }

    public function runPart02(): int
    {
        $this->loadData(true);

        $best = [];

        foreach ($this->a as $a) {
            $this->start = $a;
            $best[] = $this->aStar();
        }

        $best = array_filter($best);

        return min($best);
    }

    private function loadData(bool $isPart2 = false): void
    {
        echo "!!! Doesn't work on example data !!!" . PHP_EOL;

        foreach ($this->data as $row => $cols) {
            foreach (str_split($cols) as $col => $char) {
                $this->map[$row][$col] = $this->values[$char];
                $this->destinations[$row][$col] = $char;

                if ($isPart2 && $char === 'a') {
                    $this->a[] = [$row, $col];
                }

                if ($char === self::START) {
                    if ($isPart2) {
                        $this->map[$row][$col] = $this->values['a'];
                        $this->a[] = [$row, $col];
                    } else {
                        $this->start = [$row, $col];
                    }
                }

                if ($char === self::END) {
                    $this->end = [$row, $col];
                }
            }
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
//        $this->print();
    }

    private function aStar(): int
    {
        $goal = $this->end;

        $cols = array_fill(0, $this->maxCol, PHP_INT_MAX);
        $gScore = array_fill(0, $this->maxRow, $cols);
        $fScore = array_fill(0, $this->maxRow, $cols);

        $startRow = $this->start[0];
        $startCol = $this->start[1];

        $gScore[$startRow][$startCol] = 0;
        $fScore[$startRow][$startCol] = $this->heuristic($startRow, $startCol, $goal);

        $openSet[] = [
            'node' => [$startRow, $startCol],
            'fScore' => $fScore[$startRow][$startCol],
            'gScore' => $gScore[$startRow][$startCol],
            'steps' => 0,
        ];

        while (!empty($openSet)) {
            if (count($openSet) > 1) {
                array_multisort(array_column($openSet, 'fScore'), SORT_ASC, $openSet);
            }

            $current = array_shift($openSet);
            $currentNode = $current['node'];

            if ($currentNode == $goal) {
                return $current['steps'];
            }

            foreach ($this->directions as $delta) {
                $newRow = $currentNode[0] + $delta[0];
                $newCol = $currentNode[1] + $delta[1];

                if ($newRow < 0 || $newRow >= $this->maxRow || $newCol < 0 || $newCol >= $this->maxCol) {
                    continue;
                }

                $distance = $this->calculateDistance($this->map[$currentNode[0]][$currentNode[1]], $this->map[$newRow][$newCol]);

                if ($distance < 2) {
                    // prefer move to higher rather than 0
                    if ($distance === 0) {
                        $distance = 2;
                    }

                    $tentativeGScore = $gScore[$currentNode[0]][$currentNode[1]] + $distance;

                    if ($tentativeGScore < $gScore[$newRow][$newCol]) {
                        $gScore[$newRow][$newCol] = $tentativeGScore;
                        $fScore[$newRow][$newCol] = $gScore[$newRow][$newCol] + $this->heuristic($newRow, $newCol, $goal);

                        $openSet[] = [
                            'node' => [$newRow, $newCol],
                            'fScore' => $fScore[$newRow][$newCol],
                            'gScore' => $gScore[$newRow][$newCol],
                            'steps' => $current['steps'] + 1,
                        ];
                    }
                }
            }
        }

        return 0;
    }

    private function heuristic(int $row, int $col, array $goal): int
    {
        return abs($row - $goal[0]) + abs($col - $goal[1]);
    }

    private function calculateDistance(int $a, int $b): ?int
    {
        return $b - $a;
    }

    private function print(): void
    {
        for ($i = 0; $i < $this->maxRow; $i++) {
            for ($j = 0; $j < $this->maxCol; $j++) {
                echo $this->destinations[$i][$j];
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }
}
