<?php

namespace Shadowinek\AdventOfCode\Year2025;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private array $ranges = [];

    private function loadData(): void
    {
        $inputs = explode(',', $this->data[0]);

        foreach ($inputs as $input) {
            list($min, $max) = explode('-', $input);
            $this->ranges[] = range($min, $max);
        }
    }

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->ranges as $range) {
            foreach ($range as $number) {
                $number = (string)$number;
                $length = str_split($number);

                if (count($length) % 2 !== 0) {
                    continue;
                }

                list($first, $second) = str_split($number, count($length) / 2);

                if ($first === $second) {
                    $total += (int)$number;
                }
            }
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->ranges as $range) {
            foreach ($range as $number) {
                $matches = [];
                preg_match('#^(.+)\1+$#', $number, $matches);

                if (!empty($matches)) {
                    $total += $number;
                }
            }
        }

        return $total;
    }
}
