<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle12 extends AbstractPuzzle
{
    private array $map;
    private int $maxRow;
    private int $maxCol;
    private array $plots;
    private array $areas;

    private $directions = [
        'N' => [-1, 0],
        'E' => [0, 1],
        'S' => [1, 0],
        'W' => [0, -1],
    ];

    public function runPart01(): int
    {
        $this->loadData();
        $total = 0;

        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $id) {
                if ($this->plots[$this->getKey($row, $col)]) {
                    $total += $this->findArea($row, $col, $id);
                }
            }
        }

        return $total;
    }

    private function findArea(int $startRow, int $startCol, string $id): int
    {
        $areas = [];
        $toSearch = [[$startRow, $startCol]];
        $area = 0;
        $totalPerimeter = 0;

        while (true) {
            $plot = array_shift($toSearch);

            if (empty($plot)) {
                break;
            }

            [$row, $col] = $plot;
            $areas[$this->getKey($row, $col)] = true;

            if ($this->plots[$this->getKey($row, $col)] === false) {
                continue;
            }

            $this->plots[$this->getKey($row, $col)] = false;
            $area++;

            $perimeter = 4;

            foreach ($this->directions as $direction) {
                $newRow = $row + $direction[0];
                $newCol = $col + $direction[1];

                if ($newRow < 0 || $newRow >= $this->maxRow || $newCol < 0 || $newCol >= $this->maxCol) {
                    continue;
                }

                if ($this->map[$newRow][$newCol] === $id) {
                    $perimeter--;
                    $toSearch[] = [$newRow, $newCol];
                }
            }

            $totalPerimeter += $perimeter;
        }

        $this->areas[] = ['id' => $id, 'area' => $areas];

        return $area * $totalPerimeter;
    }

    public function runPart02(): int
    {
        $this->loadData();
        $total = 0;

        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $id) {
                if ($this->plots[$this->getKey($row, $col)]) {
                    $this->findArea($row, $col, $id);
                }
            }
        }

        foreach ($this->areas as $area) {
            $total += $this->findFences($area);
        }

        return $total;
    }

    private function findFences(array $area): int
    {
        $colArray = array_fill(-1, $this->maxCol + 2, []);
        $rowArray = array_fill(-1, $this->maxRow + 2, $colArray);

        $id = $area['id'];
        $plots = $area['area'];

        foreach ($plots as $plot => $value) {
            [$row, $col] = explode('-', $plot);

            foreach ($this->directions as $directionId => $direction) {
                $newRow = (int)$row + $direction[0];
                $newCol = (int)$col + $direction[1];

                if ($newRow < 0 || $newRow >= $this->maxRow || $newCol < 0 || $newCol >= $this->maxCol || $this->map[$newRow][$newCol] !== $id) {
                    $rowArray[$newRow][$newCol][$directionId] = true;
                }
            }
        }

        $fence = 0;

        for ($i = -1; $i < count($rowArray) - 1; $i++) {
            $n = $s = true;

            for ($j = -1; $j < count($rowArray[1]) - 1; $j++) {
                if (isset($rowArray[$i][$j]['N'])) {
                    if ($n) {
                        $n = false;
                        $fence++;
                    }
                } else {
                    $n = true;
                }

                if (isset($rowArray[$i][$j]['S'])) {
                    if ($s) {
                        $s = false;
                        $fence++;
                    }
                } else {
                    $s = true;
                }
            }
        }

        for ($k = -1; $k < count($rowArray[1]) - 1; $k++) {
            $e = $w = true;

            for ($l = -1; $l < count($rowArray); $l++) {
                if (isset($rowArray[$l][$k]['E'])) {
                    if ($e) {
                        $e = false;
                        $fence++;
                    }
                } else {
                    $e = true;
                }

                if (isset($rowArray[$l][$k]['W'])) {
                    if ($w) {
                        $w = false;
                        $fence++;
                    }
                } else {
                    $w = true;
                }
            }
        }

        return $fence * count($plots);
    }

    private function loadData(): void
    {
        foreach ($this->data as $row => $line) {
            foreach (str_split($line) as $col => $char) {
                $this->map[$row][$col] = $char;
                $this->plots[$this->getKey($row, $col)] = true;
            }
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
    }

    private function getKey(int $row, int $col): string
    {
        return $row . '-' . $col;
    }
}
