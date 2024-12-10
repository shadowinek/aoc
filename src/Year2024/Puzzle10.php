<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle10 extends AbstractPuzzle
{
    private array $map;
    private array $starts;

    private array $directions = [
        [-1, 0],
        [0, 1],
        [1, 0],
        [0, -1],
    ];

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->starts as $start) {
            $total += $this->findTrails($start);
        }

        return $total;
    }

    private function findTrails(array $start, bool $part2 = false): int
    {
        $nodes = [$start];
        $trails = [];
        $total = 0;

        while (true) {
            $node = array_shift($nodes);

            if (empty($node)) {
                break;
            }

            [$x, $y, $value] = $node;

            if ($value === 9) {
                $trails[$this->getKey($node)] = true;
                $total++;
            } else {
                foreach ($this->directions as $direction) {
                    [$dx, $dy] = $direction;

                    $newX = $x + $dx;
                    $newY = $y + $dy;

                    if ($this->isValid($newX, $newY, $value + 1)) {
                        $nodes[] = [$newX, $newY, $this->map[$newX][$newY]];
                    }
                }
            }
        }

        return $part2 ? $total : count($trails);
    }

    private function getKey(array $node): string
    {
        return $node[0] . '-' . $node[1];
    }

    private function isValid(int $x, int $y, int $value): bool
    {
        return isset($this->map[$x][$y]) && $this->map[$x][$y] === $value;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->starts as $start) {
            $total += $this->findTrails($start, true);
        }

        return $total;
    }

    private function loadData(): void
    {
        foreach ($this->data as $row => $cols) {
            foreach (str_split($cols) as $col => $value) {
                $this->map[$row][$col] = (int)$value;

                if ((int)$value === 0) {
                    $this->starts[] = [$row, $col, $value];
                }
            }
        }
    }
}
