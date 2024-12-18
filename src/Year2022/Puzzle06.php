<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle06 extends AbstractPuzzle
{
    private array $chars = [];

    public function runPart01(): int
    {
        $this->loadData();
        return $this->findSubstring(4);
    }

    public function runPart02(): int
    {
        $this->loadData();
        return $this->findSubstring(14);
    }

    private function loadData(): void
    {
        $this->chars = str_split($this->data[0]);
    }

    private function findSubstring(int $length): int
    {
        $marker = [];
        $i = 0;

        while ($char = array_shift($this->chars)) {
            if (in_array($char, $marker)) {
                while ($value = array_shift($marker)) {
                    if ($value === $char) {
                        break;
                    }
                }
            }

            $marker[] = $char;
            $i++;

            if (count($marker) === $length) {
                return $i;
            }
        }

        return -1;
    }
}
