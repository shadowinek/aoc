<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle16 extends AbstractPuzzle
{
    private array $map;
    private int $maxRow;
    private int $maxCol;
    private const string EMPTY = '.';
    private const string NORTH = '^';
    private const string SOUTH = 'v';
    private const string WEST = '<';
    private const string EAST = '>';
    private const string START = 'S';
    private const string END = 'E';
    private array $directions = [
        self::NORTH => [-1, 0],
        self::SOUTH => [1, 0],
        self::WEST => [0, -1],
        self::EAST => [0, 1],
    ];
    private array $start;
    private array $paths = [];
    private array $visited = [];
    private array $found = [];
    private array $best = [];

    public function runPart01(): int
    {
        $this->loadData();

        $this->findPaths();

        return min($this->found);
    }

    private function findPaths(): void
    {
        $this->paths[] = [
            'steps' => [$this->getKey($this->start[0], $this->start[1])],
            'facing' => self::EAST,
            'score' => 0,
        ];

        while (true) {
            $path = array_shift($this->paths);

            if (empty($path)) {
                break;
            }

            $steps = $path['steps'];
            $step = end($steps);
            $step = explode('-', $step);
            $row = (int)$step[0];
            $col = (int)$step[1];
            $facing = $path['facing'];
            $score = $path['score'];

            foreach ($this->directions as $direction => $delta) {
                $newRow = $row + $delta[0];
                $newCol = $col + $delta[1];

                if ($this->map[$newRow][$newCol] === self::END) {
                    $newScore = $this->getScore($facing, $direction);
                    $this->found[] = $score + $newScore;
                }

                $newKey = $this->getKey($newRow, $newCol);
                if ($this->map[$newRow][$newCol] === self::EMPTY && !in_array($newKey, $steps)) {
                    $newScore = $this->getScore($facing, $direction);

                    if ($newScore > 0) {
                        if (!isset($this->visited[$newKey]) || $score + $newScore <= $this->visited[$newKey]) {
                            $newSteps = $steps;
                            $newSteps[] = $newKey;
                            $newFacing = $direction;
                            $this->paths[] = [
                                'steps' => $newSteps,
                                'facing' => $newFacing,
                                'score' => $score + $newScore,
                            ];

                            $this->visited[$newKey] = $score + $newScore;
                        }
                    }
                }
            }
        }
    }

    private function findPaths2(): void
    {
        $this->paths[] = [
            'steps' => [$this->getKey($this->start[0], $this->start[1])],
            'facing' => self::EAST,
            'score' => 0,
            'size' => 1,
        ];

        $dir = [
            self::EAST => PHP_INT_MAX,
            self::WEST => PHP_INT_MAX,
            self::NORTH => PHP_INT_MAX,
            self::SOUTH => PHP_INT_MAX,
        ];

        $cols = array_fill(0, $this->maxCol, $dir);
        $this->visited = array_fill(0, $this->maxRow, $cols);

        while (true) {
            $path = array_shift($this->paths);

            if (empty($path)) {
                break;
            }

            $steps = $path['steps'];
            $step = end($steps);
            $step = explode('-', $step);
            $row = (int)$step[0];
            $col = (int)$step[1];
            $facing = $path['facing'];
            $score = $path['score'];

            foreach ($this->directions as $direction => $delta) {
                $newRow = $row + $delta[0];
                $newCol = $col + $delta[1];

                if ($this->map[$newRow][$newCol] === self::END) {
                    $this->best[] = $path;
                }

                $newKey = $this->getKey($newRow, $newCol);
                if ($this->map[$newRow][$newCol] === self::EMPTY && !in_array($newKey, $steps)) {
                    $add = $this->getScore($facing, $direction);

                    if ($add > 0) {
                        $newScore = $score + $add;
                        if ($newScore <= $this->visited[$newRow][$newCol][$direction]) {
                            $newSteps = $steps;
                            $newSteps[] = $newKey;
                            $newFacing = $direction;
                            $this->paths[] = [
                                'steps' => $newSteps,
                                'facing' => $newFacing,
                                'score' => $newScore,
                                'size' => count($newSteps),
                            ];

                            $this->visited[$newRow][$newCol][$direction] = $newScore;
                        }
                    }
                }
            }
        }
    }

    private function getScore(string $facing, string $direction): int
    {
        if ($facing === $direction) {
            return 1;
        }

        return match ($facing) {
            self::NORTH => match ($direction) {
                self::SOUTH => -1,
                default => 1001,
            },
            self::SOUTH => match ($direction) {
                self::NORTH => -1,
                default => 1001,
            },
            self::WEST => match ($direction) {
                self::EAST => -1,
                default => 1001,
            },
            self::EAST => match ($direction) {
                self::WEST => -1,
                default => 1001,
            },
            default => 0,
        };
    }

    private function getKey(int $x, int $y): string
    {
        return $x . '-' . $y;
    }

    public function runPart02(): int
    {
        $this->loadData();
        $this->findPaths2();

        array_multisort(array_column($this->best, 'score'), SORT_ASC, $this->best);

        $score = PHP_INT_MAX;
        $seats = [];

        foreach ($this->best as $best) {
            if ($best['score'] <= $score) {
                $score = $best['score'];
                $seats = array_merge($seats, $best['steps']);
            } else {
                break;
            }
        }

//        foreach ($seats as $seat) {
//            $seat = explode('-', $seat);
//            $this->map[$seat[0]][$seat[1]] = 'O';
//        }
//
//        $this->print();

        $seats = array_unique($seats);

        return count($seats) + 1;
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

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->map[] = str_split($line);
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
        $this->start = $this->find(self::START);
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
