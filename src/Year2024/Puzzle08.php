<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle08 extends AbstractPuzzle
{
    private array $map = [];
    private array $nodes = [];
    private array $antinodes = [];
    private const string EMPTY = '.';
    private int $maxRow;
    private int $maxCol;

    public function runPart01(): int
    {
        $this->loadData();

        foreach ($this->nodes as $nodes) {
            $this->findAntinodes($nodes);
        }

        return array_sum($this->antinodes);
    }

    private function findAntinodes(array $nodes, bool $part2 = false): void
    {
        while ($node = array_shift($nodes)) {
            [$x, $y] = $node;

            foreach ($nodes as $secondNode) {
                [$x2, $y2] = $secondNode;

                $deltaX = $x - $x2;
                $deltaY = $y - $y2;

                if ($part2) {
                    $this->antinodes[$x . '-' . $y] = 1;
                    $this->antinodes[$x2 . '-' . $y2] = 1;

                    $i = 1;

                    while (true) {
                        $a1x = $x + $deltaX * $i;
                        $a1y = $y + $deltaY * $i;

                        if ($this->isValid($a1x, $a1y)) {
                            $this->antinodes[$a1x . '-' . $a1y] = 1;
                            $i++;
                        } else {
                            break;
                        }
                    }

                    $i = 1;

                    while (true) {
                        $a2x = $x2 - $deltaX * $i;
                        $a2y = $y2 - $deltaY * $i;

                        if ($this->isValid($a2x, $a2y)) {
                            $this->antinodes[$a2x . '-' . $a2y] = 1;
                            $i++;
                        } else {
                            break;
                        }
                    }
                } else {
                    $a1x = $x + $deltaX;
                    $a1y = $y + $deltaY;
                    $a2x = $x2 - $deltaX;
                    $a2y = $y2 - $deltaY;

                    if ($this->isValid($a1x, $a1y)) {
                        $this->antinodes[$a1x . '-' . $a1y] = 1;
                    }

                    if ($this->isValid($a2x, $a2y)) {
                        $this->antinodes[$a2x . '-' . $a2y] = 1;
                    }
                }
            }
        }
    }

    private function isValid(int $x, int $y): bool
    {
        return $x >= 0 && $x < $this->maxRow && $y >= 0 && $y < $this->maxCol;
    }

    public function runPart02(): int
    {
        $this->loadData();

        foreach ($this->nodes as $nodes) {
            $this->findAntinodes($nodes, true);
        }

        return array_sum($this->antinodes);
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->map[] = str_split($line);
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);

        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $value) {
                if ($value !== self::EMPTY) {
                    $this->nodes[$value][$row . '-' . $col] = [$row, $col];
                }
            }
        }
    }

    private function print(array $map): void
    {
        foreach ($map as $row) {
            foreach ($row as $col) {
                echo $col;
            }

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }
}
