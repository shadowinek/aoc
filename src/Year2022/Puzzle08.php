<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle08 extends AbstractPuzzle
{
    private array $trees = [];
    private array $visible = [];

    private int $rows = 0;
    private int $columns = 0;

    private array $scenicScore = [];

    public function runPart01(): int
    {
        $this->loadData();

        foreach ($this->visible as $id => $tree) {
            if (!$tree) {
                $this->visible[$id] = $this->checkVisibility($id);
            }
        }

        return count(array_filter($this->visible));
    }

    public function runPart02(): int
    {
        $this->loadData();

        foreach ($this->visible as $id => $tree) {
            if (!$tree) {
                $this->scenicScore[$id] = $this->getScenicScore($id);
            }
        }

        return max($this->scenicScore);
    }

    private function loadData(): void
    {
        $this->rows = count($this->data);

        foreach ($this->data as $row => $data) {
            if (!$this->columns) {
                $this->columns = strlen($data);
            }

            $trees = str_split($data);

            foreach ($trees as $column => $tree) {
                $key = $row . '-' . $column;

                $this->trees[$key] = $tree;

                if ($row === 0 || $row === $this->rows - 1 || $column === 0 || $column === $this->columns - 1) {
                    $this->visible[$key] = true;
                } else {
                    $this->visible[$key] = false;
                }
            }
        }
    }

    private function checkVisibility(string $id): bool
    {
        $coords = explode('-', $id);
        $x = (int)$coords[0];
        $y = (int)$coords[1];
        $tree = $this->trees[$id];

        // check top
        for ($i = $x - 1; $i >= 0; $i--) {
            $key = $i . '-' . $y;

            if ($this->trees[$key] < $tree) {
                $visible = true;
            } else {
                $visible = false;
                break;
            }
        }

        if ($visible) {
            return true;
        }

        // check bottom
        for ($i = $x + 1; $i < $this->rows; $i++) {
            $key = $i . '-' . $y;

            if ($this->trees[$key] < $tree) {
                $visible = true;
            } else {
                $visible = false;
                break;
            }
        }

        if ($visible) {
            return true;
        }

        // check left
        for ($i = $y - 1; $i >= 0; $i--) {
            $key = $x . '-' . $i;

            if ($this->trees[$key] < $tree) {
                $visible = true;
            } else {
                $visible = false;
                break;
            }
        }

        if ($visible) {
            return true;
        }

        // check right
        for ($i = $y + 1; $i < $this->columns; $i++) {
            $key = $x . '-' . $i;

            if ($this->trees[$key] < $tree) {
                $visible = true;
            } else {
                $visible = false;
                break;
            }
        }

        return $visible;
    }

    private function getScenicScore(string $id): int
    {
        $coords = explode('-', $id);
        $x = (int)$coords[0];
        $y = (int)$coords[1];
        $tree = $this->trees[$id];
        $a = $b = $c = $d = 0;

        // check top
        for ($i = $x - 1; $i >= 0; $i--) {
            $key = $i . '-' . $y;
            $a++;

            if ($this->trees[$key] >= $tree) {
                break;
            }
        }

        // check bottom
        for ($i = $x + 1; $i < $this->rows; $i++) {
            $key = $i . '-' . $y;
            $b++;

            if ($this->trees[$key] >= $tree) {
                break;
            }
        }

        // check left
        for ($i = $y - 1; $i >= 0; $i--) {
            $key = $x . '-' . $i;
            $c++;

            if ($this->trees[$key] >= $tree) {
                break;
            }
        }

        // check right
        for ($i = $y + 1; $i < $this->columns; $i++) {
            $key = $x . '-' . $i;
            $d++;

            if ($this->trees[$key] >= $tree) {
                break;
            }
        }

        return $a * $b * $c * $d;
    }
}
