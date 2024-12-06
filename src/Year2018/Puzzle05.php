<?php

namespace Shadowinek\AdventOfCode\Year2018;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle05 extends AbstractPuzzle
{
    public function runPart01(): mixed
    {
        return $this->reduce($this->data[0]);
    }

    public function runPart02(): mixed
    {
        $polymers = [];

        $chars = array_keys(array_count_values(str_split(strtolower($this->data[0]))));

        foreach ($chars as $char) {
            $polymers[$char] = $this->reduce(str_replace([$char, strtoupper($char)], '', $this->data[0]));
        }

        return min($polymers);
    }

    private function reduce(string $string): string
    {
        $length = strlen($string);

        while (true) {
            $string = $this->reduceLoop($string);
            $newLength = strlen($string);

            if ($newLength === $length) {
                break;
            } else {
                $length = $newLength;
            }
        }

        return $newLength;
    }

    private function reduceLoop($string): string
    {
        for ($i=0; $i<strlen($string)-1; $i++) {
            if (abs(ord($string[$i]) - ord($string[$i+1])) === 32) {
                $string = substr_replace($string, '', $i, 2);
            }
        }

        return $string;
    }
}
