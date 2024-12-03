<?php

namespace Shadowinek\AdventOfCode\Year2015;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    private array $steps = [];
    public function runPart01(): int
    {
        $this->loadData();

        $level = 0;

        foreach ($this->steps as $step) {
            if ($step === '(') {
                $level++;
            } else {
                $level--;
            }
        }

        return $level;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $level = 0;

        foreach ($this->steps as $i => $step) {
            if ($step === '(') {
                $level++;
            } else {
                $level--;
            }

            if ($level === -1) {
                return $i+1;
            }
        }

        return 0;
    }

    private function loadData(): void
    {
        $this->steps = str_split($this->data[0]);
    }
}
