<?php

namespace Shadowinek\AdventOfCode\Year2018;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private array $strings = [];

    public function runPart01(): mixed
    {
        $this->loadData();

        $doubles = 0;
        $triples = 0;

        foreach ($this->strings as $string) {
            $counts = array_count_values($string);

            if (in_array(2, $counts)) {
                $doubles++;
            }

            if (in_array(3, $counts)) {
                $triples++;
            }
        }

        return $doubles * $triples;
    }

    public function runPart02(): mixed
    {
        $this->loadData();

        while ($string = array_shift($this->strings)) {
            foreach ($this->strings as $other) {
                $diff = array_diff_assoc($string, $other);

                if (count($diff) === 1) {
                    $result = array_diff_assoc($string, $diff);

                    return implode('', array_unique($result));
                }
            }
        }

        return null;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->strings[] = str_split($line);
        }
    }
}
