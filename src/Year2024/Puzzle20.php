<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle20 extends AbstractPuzzle
{
    private array $map = [];
    private int $maxRow;
    private int $maxCol;
    private const string WALL = '#';
    private const string EMPTY = '.';
    private const string START = 'S';
    private const string END = 'E';
    private array $start;
    private array $end;
    private array $directions = [
        [-1, 0],
        [1, 0],
        [0, -1],
        [0, 1],
    ];

    public function runPart01(): int
    {
        $this->loadData();

        $time = $this->aStar();
        $times = [];

        $a = 0;

        for ($i = 1; $i < $this->maxRow - 1; $i++) {
            for ($j = 1; $j < $this->maxCol - 1; $j++) {
                if ($this->map[$i][$j] === self::WALL) {
                    $this->map[$i][$j] = self::EMPTY;
                    echo "Iteration: " . $a++ . PHP_EOL;
                    $times[] = $this->aStar();

                    $this->map[$i][$j] = self::WALL;
                }
            }
        }

        $times = array_count_values($times);

        unset($times[-1]);

        ksort($times);

        print_r($times);

        echo "Time: " . $time . PHP_EOL;

        $total = 0;

        foreach ($times as $key => $value) {
            if ($key < $time) {
                echo "Saved: " . $time - $key . ' ' . $value . ' times' . PHP_EOL;

                if ($time - $key >= 100) {
                    $total += $value;
                }
            }
        }

        return $total;
    }

    private function aStar(): int
    {
        $goal = $this->end;

        $cols = array_fill(0, $this->maxCol, INF);
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
        ];

        while (!empty($openSet)) {
            if (count($openSet) > 1) {
                array_multisort(array_column($openSet, 'fScore'), SORT_ASC, $openSet);
            }

            $current = array_shift($openSet);
            $currentNode = $current['node'];

            if ($currentNode == $goal) {
                return $current['gScore'];
            }

            foreach ($this->directions as $delta) {
                $newRow = $currentNode[0] + $delta[0];
                $newCol = $currentNode[1] + $delta[1];

                if ($newRow < 0 || $newRow >= $this->maxRow || $newCol < 0 || $newCol >= $this->maxCol) {
                    continue;
                }

                if ($this->map[$newRow][$newCol] === self::EMPTY || $this->map[$newRow][$newCol] === self::END) {
                    $tentativeGScore = $gScore[$currentNode[0]][$currentNode[1]] + 1;

                    if ($tentativeGScore < $gScore[$newRow][$newCol]) {
                        $gScore[$newRow][$newCol] = $tentativeGScore;
                        $fScore[$newRow][$newCol] = $gScore[$newRow][$newCol] + $this->heuristic($newRow, $newCol, $goal);

                        $openSet[] = [
                            'node' => [$newRow, $newCol],
                            'fScore' => $fScore[$newRow][$newCol],
                            'gScore' => $gScore[$newRow][$newCol],
                        ];
                    }
                }
            }
        }

        return -1;
    }


    private function heuristic(int $row, int $col, array $goal): int
    {
        return abs($row - $goal[0]) + abs($col - $goal[1]);
    }

    public function runPart02(): int
    {
        $this->loadData();

        return 0;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->map[] = str_split($line);
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
        $this->start = $this->find(self::START);
        $this->end = $this->find(self::END);
    }

    private function find(string $cell): array
    {
        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $value) {
                if ($value === $cell) {
                    return [$row, $col];
                }
            }
        }

        return [];
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
