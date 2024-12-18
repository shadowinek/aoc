<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Closure;
use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle12 extends AbstractPuzzle
{
    private array $values = [
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 5,
        'f' => 6,
        'g' => 7,
        'h' => 8,
        'i' => 9,
        'j' => 10,
        'k' => 11,
        'l' => 12,
        'm' => 13,
        'n' => 14,
        'o' => 15,
        'p' => 16,
        'q' => 17,
        'r' => 18,
        's' => 19,
        't' => 20,
        'u' => 21,
        'v' => 22,
        'w' => 23,
        'x' => 24,
        'y' => 25,
        'z' => 26,
        self::START => 0,
        self::END => 27,
    ];

    private const START = 'S';
    private const END = 'E';

    private array $offsets = [
        [0, 1],
        [1, 0],
        [0, -1],
        [-1, 0],
    ];

    private array $locations = [];
    private array $distances = [];

    private string $start;
    private string $end;

    private array $ends = [];
    private array $cache = [];

    public function runPart01(): int
    {
        $this->loadData();

        return $this->calculate();
    }

    public function runPart02(): int
    {
        $this->loadData();

        return $this->calculate();
    }

    private function loadData(): void
    {
        foreach ($this->data as $x => $data) {
            foreach (str_split($data) as $y => $char) {
                $this->locations[$this->getKey($x, $y)] = $this->values[$char];

                if ($char === self::START) {
                    $this->start = $this->getKey($x, $y);
                }

                if ($char === self::END) {
                    $this->end = $this->getKey($x, $y);
                }
            }
        }
    }

    private function move(string $node): int
    {
        if (isset($this->cache[$node])) {
            echo 'cached' . PHP_EOL;
            return $this->cache[$node];
        }

        echo $node . PHP_EOL;


        list($x, $y) = explode('-', $node);
        $return = 0;

        foreach ($this->offsets as $offset) {
            $newX = (int)$x + $offset[0];
            $newY = (int)$y + $offset[1];

            $key = $this->getKey($newX, $newY);

            if (isset($this->locations[$key])) {
                $diff = $this->calculateDistance($this->locations[$node], $this->locations[$key]);

                if ($diff === 0 || $diff === 1) {
                    if ($key === $this->end) {
                        $return = 1;
                    } else {
                        $return += $this->move($key);
                    }
                }
            }
        }

        $this->cache[$node] = $return;

        return $return;
    }

    private function calculate(): int
    {
        $stack = [$this->start];
        $visited = [];

        $foo = $this->move($this->start);

        print_r($this->cache);

//        while ($current = array_shift($stack)) {
//            $visited[] = $current;
//            list($x, $y) = explode('-', $current);
//
//            foreach ($this->offsets as $offset) {
//                $newX = (int) $x + $offset[0];
//                $newY = (int) $y + $offset[1];
//
//                $key = $this->getKey($newX, $newY);
//
//                if (isset($this->locations[$key]) && !in_array($key, $visited)) {
//                    $next = $this->locations[$key];
//                    $diff = $this->calculateDistance($this->locations[$current], $next);
//                }
//            }
//        }

        return 0;
    }

    private function calculateDistance(int $a, int $b): ?int
    {
        return $b - $a;
    }

    private function getKey(int $x, int $y): string
    {
        return $x . '-' . $y;
    }
}
