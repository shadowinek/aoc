<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle18 extends AbstractPuzzle
{
    private array $corrupted = [];
    private array $map = [];
    private int $maxRow = 7;
    private int $maxCol = 7;
    private int $bytes = 12;
    private array $directions = [
        [-1, 0],
        [1, 0],
        [0, -1],
        [0, 1],
    ];

    public function runPart01(): int
    {
        $this->loadData();

        return $this->aStar();
    }

    private function aStar(): int
    {
        $goal = [$this->maxRow - 1, $this->maxCol - 1];

        $cols = array_fill(0, $this->maxCol, INF);
        $gScore = array_fill(0, $this->maxRow, $cols);
        $fScore = array_fill(0, $this->maxRow, $cols);

        $startRow = 0;
        $startCol = 0;

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

                if ($this->map[$newRow][$newCol] === '.') {
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

    private function getKey(int $x, int $y): string
    {
        return $x . '-' . $y;
    }

    public function runPart02(): string
    {
        $this->loadData();

        foreach ($this->corrupted as $corrupted) {
            $this->map[$corrupted[0]][$corrupted[1]] = '#';

            $aStar = $this->aStar();

            echo "Corrupted: $corrupted[1],$corrupted[0] - $aStar" . PHP_EOL;

            if ($aStar === -1) {
                return "$corrupted[1],$corrupted[0]";
            }
        }

        return 0;
    }

    private function loadData(): void
    {
        $cols = array_fill(0, 71, '.');
        $this->map = array_fill(0, 71, $cols);
        $i = 0;

        foreach ($this->data as $line) {
            [$col, $row] = explode(',', $line);

            if ($row > 6 || $col > 6) {
                $this->maxRow = 71;
                $this->maxCol = 71;
                $this->bytes = 1024;
            }

            $col = (int)$col;
            $row = (int)$row;

            if ($i < $this->bytes) {
                $this->map[$row][$col] = '#';
            } else {
                $this->corrupted[] = [$row, $col];
            }

            $i++;
        }

//        $this->print();
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
