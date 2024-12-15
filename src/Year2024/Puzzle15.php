<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle15 extends AbstractPuzzle
{
    private array $map;
    private int $maxRow;
    private int $maxCol;
    private array $moves = [];
    private const string WALL = '#';
    private const string EMPTY = '.';
    private const string ROBOT = '@';
    private const string BOX = 'O';
    private const string UP = '^';
    private const string DOWN = 'v';
    private const string LEFT = '<';
    private const string RIGHT = '>';
    private const string BOX_LEFT = '[';
    private const string BOX_RIGHT = ']';
    private array $directions = [
        self::UP => [-1, 0],
        self::DOWN => [1, 0],
        self::LEFT => [0, -1],
        self::RIGHT => [0, 1],
    ];
    private array $robot = [];

    public function runPart01(): int
    {
        $this->loadData();
        $this->findRobot();

        foreach ($this->moves as $move) {
            $toMove = $this->move($move);

            if (!empty($toMove)) {
                [$deltaRow, $deltaCol] = $this->directions[$move];
                $toMove = array_reverse($toMove);

                foreach ($toMove as $movement) {
                    [$char, $row, $col] = $movement;
                    $newRow = $row + $deltaRow;
                    $newCol = $col + $deltaCol;

                    $this->map[$newRow][$newCol] = $char;
                }

                $this->map[$row][$col] = self::EMPTY;
                $this->robot = [$newRow, $newCol];
            }
        }

        $total = 0;

        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $cell) {
                if ($cell === self::BOX) {
                    $total += 100 * $row + $col;
                }
            }

        }

        return $total;
    }

    private function move(string $move): array
    {
        [$row, $col] = $this->robot;
        [$deltaRow, $deltaCol] = $this->directions[$move];
        $toMove[] = [self::ROBOT, $row, $col];

        while (true) {
            $newRow = $row + $deltaRow;
            $newCol = $col + $deltaCol;
            $cell = $this->map[$newRow][$newCol];

            switch ($cell) {
                case self::BOX:
                    $toMove[] = [self::BOX, $newRow, $newCol];
                    break;
                case self::EMPTY:
                    return $toMove;
                case self::WALL:
                default:
                    return [];
            }

            $row = $newRow;
            $col = $newCol;
        }
    }

    private function findRobot(): void
    {
        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $cell) {
                if ($cell === self::ROBOT) {
                    $this->robot = [$row, $col];
                    return;
                }
            }
        }
    }

    public function runPart02(): int
    {
        $this->loadData();
        $this->expandMap();
        $this->findRobot();

        foreach ($this->moves as $move) {
            $toMove = $this->moveInBigMap($move);

            if (!empty($toMove)) {
                [$deltaRow, $deltaCol] = $this->directions[$move];

                foreach ($toMove as $movement) {
                    [, $row, $col] = $movement;
                    $this->map[$row][$col] = self::EMPTY;
                }

                foreach ($toMove as $movement) {
                    [$char, $row, $col] = $movement;
                    $newRow = $row + $deltaRow;
                    $newCol = $col + $deltaCol;

                    $this->map[$newRow][$newCol] = $char;
                }
            }

            $this->findRobot();
        }

        $this->print();

        $total = 0;

        foreach ($this->map as $row => $cols) {
            foreach ($cols as $col => $cell) {
                if ($cell === self::BOX_LEFT) {
                    $total += 100 * $row + $col;
                }
            }

        }

        return $total;
    }

    private function moveInBigMap(string $move): array
    {
        [$deltaRow, $deltaCol] = $this->directions[$move];
        $toCheck[] = $this->robot;
        $toMove[] = [self::ROBOT, $this->robot[0], $this->robot[1]];

        while (true) {
            $current = array_shift($toCheck);

            if (empty($current)) {
                break;
            }

            [$row, $col] = $current;
            $newRow = $row + $deltaRow;
            $newCol = $col + $deltaCol;
            $cell = $this->map[$newRow][$newCol];

            switch ($cell) {
                case self::BOX_RIGHT:
                    $toMove[] = [self::BOX_RIGHT, $newRow, $newCol];

                    $leftBoxRow = $newRow + $this->directions[self::LEFT][0];
                    $leftBoxCol = $newCol + $this->directions[self::LEFT][1];
                    $toMove[] = [self::BOX_LEFT, $leftBoxRow, $leftBoxCol];
                    $toCheck[] = [$newRow, $newCol];

                    if ($move === self::UP || $move === self::DOWN) {
                        $toCheck[] = [$leftBoxRow, $leftBoxCol];
                    }
                    break;
                case self::BOX_LEFT:
                    $toMove[] = [self::BOX_LEFT, $newRow, $newCol];

                    $rightBoxRow = $newRow + $this->directions[self::RIGHT][0];
                    $rightBoxCol = $newCol + $this->directions[self::RIGHT][1];
                    $toMove[] = [self::BOX_RIGHT, $rightBoxRow, $rightBoxCol];
                    $toCheck[] = [$newRow, $newCol];

                    if ($move === self::UP || $move === self::DOWN) {
                        $toCheck[] = [$rightBoxRow, $rightBoxCol];
                    }
                    break;
                case self::EMPTY:
                    if (empty($toCheck)) {
                        return $toMove;
                    }
                    break;
                case self::WALL:
                default:
                    return [];
            }
        }

        return $toMove;
    }

    private function expandMap(): void
    {
        $newMap = [];

        foreach ($this->map as $row) {
            $col = [];
            foreach ($row as $cell) {
                switch ($cell) {
                    case self::WALL:
                        $col[] = self::WALL;
                        $col[] = self::WALL;
                        break;
                    case self::EMPTY:
                        $col[] = self::EMPTY;
                        $col[] = self::EMPTY;
                        break;
                    case self::ROBOT:
                        $col[] = self::ROBOT;
                        $col[] = self::EMPTY;
                        break;
                    case self::BOX:
                        $col[] = self::BOX_LEFT;
                        $col[] = self::BOX_RIGHT;
                        break;
                    default:
                        break;
                }
            }
            $newMap[] = $col;
        }

        $this->maxRow = count($newMap);
        $this->maxCol = count($newMap[0]);

        $this->map = $newMap;
    }

    private function loadData(): void
    {
        $loadMoves = false;

        foreach ($this->data as $line) {
            if ($line === '') {
                $loadMoves = true;
                continue;
            }

            if ($loadMoves) {
                $this->moves = array_merge($this->moves, str_split($line));
            } else {
                $this->map[] = str_split($line);
            }
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
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
