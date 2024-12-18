<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    private array $calories = [];

    public function runPart01(): int
    {
        $this->loadData();

        return max($this->calories);
    }

    public function runPart02(): int
    {
        $this->loadData();

        rsort($this->calories);

        return $this->calories[0] + $this->calories[1] + $this->calories[2];
    }

    private function loadData(): void
    {
        $i = 0;

        foreach ($this->data as $data) {
            if (empty($data)) {
                $i++;
            } else {
                if (!isset($this->calories[$i])) {
                    $this->calories[$i] = $data;
                } else {
                    $this->calories[$i] += $data;
                }
            }
        }
    }
}
