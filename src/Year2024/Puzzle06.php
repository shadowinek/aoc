<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle06 extends AbstractPuzzle
{
    private array $map = [];
    private array $visited = [];
    private const string OBSTRUCTION = '#';
    private const string OPEN = '.';
    private const string GUARD = '^';
    private const string VISITED = 'X';
    private array $start = [];
    private int $maxCol = 0;
    private int $maxRow = 0;
    private array $mutations = [];
    private array $directions = [
        [-1, 0],
        [0, 1],
        [1, 0],
        [0, -1],
    ];
    private const string DIRECTION_HORIZONTAL = '-';
    private const string DIRECTION_VERTICAL = '|';
    private const string DIRECTION_CROSS = '+';
    private array $directionMarks = [
        0 => self::DIRECTION_VERTICAL,
        1 => self::DIRECTION_HORIZONTAL,
        2 => self::DIRECTION_VERTICAL,
        3 => self::DIRECTION_HORIZONTAL,
    ];

    public function runPart01(): int
    {
        $this->loadData();

        list($row, $col) = $this->start;
        $currentDirection = 0;
        $map = $this->map;

        while ($col < $this->maxCol - 1 && $row < $this->maxRow - 1 && $col >= 1 && $row >= 1) {
            list($newRow, $newCol) = $this->getNextPosition($row, $col, $currentDirection);

            if ($this->isFieldType($map, $newRow, $newCol, self::OBSTRUCTION)) {
                $currentDirection = $this->rotate($currentDirection);
            } else {
                $this->visited[$newRow][$newCol] = 1;
                $map[$newRow][$newCol] = self::VISITED;
                $col = $newCol;
                $row = $newRow;
            }
        }

        $total = 0;

        foreach ($this->visited as $visited) {
            $total += array_sum($visited);
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->runPart01();
        $this->generateMutations();

        $total = 0;

        foreach ($this->mutations as $i => $mutation) {
            echo 'Mutation: ' . $i . PHP_EOL;

            if ($this->findLoop($mutation)) {
                $total++;
            }

            echo PHP_EOL;
        }

        return $total;
    }

    private function findLoop(array $map): bool
    {
        list($row, $col) = $this->start;
        $currentDirection = 0;
        $visited = [];

        while (true) {
            list($newRow, $newCol) = $this->getNextPosition($row, $col, $currentDirection);

            if ($newRow < 0 || $newRow >= $this->maxRow || $newCol < 0 || $newCol >= $this->maxCol) {
                echo "Out of bounds" . PHP_EOL;
                return false;
            }

            $currentDirectionMark = $this->directionMarks[$currentDirection];

            if ($this->isFieldType($map, $newRow, $newCol, self::OBSTRUCTION)) {
                $currentDirection = $this->rotate($currentDirection);
            } else {
                $checkDirectionMark = $this->checkDirectionMark($map, $newRow, $newCol, $currentDirectionMark);

                if ($checkDirectionMark === self::VISITED && $visited[$newRow][$newCol] === $currentDirection) {
                    echo "Loop found" . PHP_EOL;
                    return true;
                }

                if ($checkDirectionMark === self::DIRECTION_CROSS) {
                    $currentDirectionMark = self::DIRECTION_CROSS;
                }

                $visited[$newRow][$newCol] = $currentDirection;
                $map[$newRow][$newCol] = $currentDirectionMark;
                $col = $newCol;
                $row = $newRow;
            }
        }
    }

    private function checkDirectionMark(array $map, int $row, int $col, string $currentDirectionMark): ?string
    {
        $newDirectionMark = $map[$row][$col];

        if ($newDirectionMark === $currentDirectionMark || $newDirectionMark === self::DIRECTION_CROSS) {
            return self::VISITED;
        }

        $return = null;

        switch ($currentDirectionMark) {
            case self::DIRECTION_HORIZONTAL:
                if ($newDirectionMark === self::DIRECTION_VERTICAL) {
                    $return = self::DIRECTION_CROSS;
                }
                break;
            case self::DIRECTION_VERTICAL:
                if ($newDirectionMark === self::DIRECTION_HORIZONTAL) {
                    $return = self::DIRECTION_CROSS;
                }
                break;
            default:
                break;
        }

        return $return;
    }

    private function loadData(): void
    {
        foreach ($this->data as $row => $line) {
            foreach (str_split($line) as $col => $char) {
                $this->map[$row][$col] = $char;
                $this->visited[$row][$col] = 0;

                if ($char === self::GUARD) {
                    $this->start = [$row, $col];
                    $this->visited[$row][$col] = 1;
                }
            }
        }

        $this->maxRow = count($this->map);
        $this->maxCol = count($this->map[0]);
    }

    private function generateMutations(): void
    {
        for ($row = 0; $row < $this->maxRow; $row++) {
            for ($col = 0; $col < $this->maxCol; $col++) {
                $mutation = $this->map;
                if (
                    $this->visited[$row][$col] === 1
                    && (
                        $this->isFieldType($this->map, $row, $col, self::OPEN)
                        || $this->isFieldType($this->map, $row, $col, self::DIRECTION_VERTICAL
                        )
                    )
                ) {
                    $mutation[$row][$col] = self::OBSTRUCTION;
                    $this->mutations[] = $mutation;
                }
            }
        }
    }

    private function isFieldType(array $map, int $row, int $col, string $type): bool
    {
        return $map[$row][$col] === $type;
    }

    private function rotate(int $direction): int
    {
        return ($direction + 1) % 4;
    }

    private function getNextPosition(int $row, int $col, int $direction): array
    {
        return [$row + $this->directions[$direction][0], $col + $this->directions[$direction][1]];
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
