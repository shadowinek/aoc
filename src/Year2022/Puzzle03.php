<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $newData;

    public function runPart01(): int
    {
        $result = 0;

        foreach ($this->data as $input) {
            $pockets = str_split($input, strlen($input) / 2);

            $common = array_intersect(str_split($pockets[0]), str_split($pockets[1]));

            $result += $this->getPriority(reset($common));
        }

        return $result;
    }

    public function runPart02(): int
    {
        $result = 0;

        $this->newData = array_reverse($this->data);

        while (!empty($this->newData)) {
            $common = array_intersect(str_split(array_pop($this->newData)), str_split(array_pop($this->newData)), str_split(array_pop($this->newData)));
            $result += $this->getPriority(reset($common));
        }

        return $result;
    }

    protected function getPriority(string $char): int
    {
        $priority = ord($char);

        if (ctype_upper($char)) {
            $priority -= 38;
        } else {
            $priority -= 96;
        }

        return $priority;
    }
}
