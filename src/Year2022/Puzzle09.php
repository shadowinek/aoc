<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle09 extends AbstractPuzzle
{

    private Head $head;
    private array $visited = [];

    public function runPart01(): int
    {
        $tail = new Tail('T', 0, 0);
        $this->head = new Head('H', 0, 0);
        $this->head->child = $tail;

        $tail->parent = $this->head;
        $this->markVisited($tail);

        $this->execute();

//        $this->printVisited(5);

        return count($this->visited);
    }

    private function execute(): void
    {
        foreach ($this->data as $data) {
            $split = explode(' ', $data);

            switch ($split[0]) {
                case 'R':
                    $this->moveRight((int)$split[1]);
                    break;
                case 'L':
                    $this->moveLeft((int)$split[1]);
                    break;
                case 'U':
                    $this->moveUp((int)$split[1]);
                    break;
                case 'D':
                    $this->moveDown((int)$split[1]);
                    break;
                default:
                    // nothing to do
                    break;
            }
        }
    }

    public function runPart02(): int
    {
        $x = 11;
        $y = 5;
        $this->head = new Head('H', $x, $y);

        $tails = [];

        for ($i = 1; $i <= 9; $i++) {
            $tails[$i] = new Tail((string)$i, $x, $y);
        }

        foreach ($tails as $id => $tail) {
            if ($id === 1) {
                $tail->parent = $this->head;
            } else {
                $tail->parent = $tails[$id - 1];
            }

            if ($id !== 9) {
                $tail->child = $tails[$id + 1];
            }
        }

        $this->head->child = $tails[1];
        $this->markVisited($tails[9]);

        $this->execute();

//        $this->printVisited(26);

        return count($this->visited);
    }

    private function moveRight(int $steps): void
    {
        for ($i = 0; $i < $steps; $i++) {
            $this->head->x++;
            $this->moveTail($this->head->child);
        }
    }

    private function moveLeft(int $steps): void
    {
        for ($i = 0; $i < $steps; $i++) {
            $this->head->x--;
            $this->moveTail($this->head->child);
        }
    }

    private function moveUp(int $steps): void
    {
        for ($i = 0; $i < $steps; $i++) {
            $this->head->y++;
            $this->moveTail($this->head->child);
        }
    }

    private function moveDown(int $steps): void
    {
        for ($i = 0; $i < $steps; $i++) {
            $this->head->y--;
            $this->moveTail($this->head->child);
        }
    }

    private function moveTail(Tail $tail): void
    {
//        echo $tail->name . ' ' . $tail->x . ' ' . $tail->y . ' -> ';

        if ($tail->parent->x === $tail->x) {
            $diff = $tail->parent->y - $tail->y;

            if ($diff === 2) {
                $tail->y++;
            }

            if ($diff === -2) {
                $tail->y--;
            }
        } elseif ($tail->parent->y === $tail->y) {
            $diff = $tail->parent->x - $tail->x;

            if ($diff === 2) {
                $tail->x++;
            }

            if ($diff === -2) {
                $tail->x--;
            }
        } else {
            $diffX = $tail->parent->x - $tail->x;
            $diffY = $tail->parent->y - $tail->y;

            if (abs($diffY) === 2 || abs($diffX) === 2) {
                if ($diffX > 0) {
                    $tail->x++;
                } else {
                    $tail->x--;
                }

                if ($diffY > 0) {
                    $tail->y++;
                } else {
                    $tail->y--;
                }
            }
        }

//        echo $tail->name . ' ' . $tail->x . ' ' . $tail->y . PHP_EOL;

        if (!empty($tail->child)) {
            $this->moveTail($tail->child);
        } else {
            $this->markVisited($tail);
        }
    }

    private function markVisited(Tail $tail): void
    {
        $this->visited[$tail->x . '-' . $tail->y] = true;
    }

    private function printVisited(int $fields): void
    {
        for ($i = 0; $i < $fields; $i++) {
            for ($j = 0; $j < $fields; $j++) {
                if (isset($this->visited[$j . '-' . $i])) {
                    echo 'X';
                } else {
                    echo '.';
                }
            }

            echo PHP_EOL;
        }
    }
}

abstract class Element
{
    public ?Tail $child = null;

    public function __construct(
        public readonly string $name,
        public int             $x,
        public int             $y
    )
    {
    }
}

class Head extends Element
{
}

class Tail extends Element
{
    public Element $parent;
}
