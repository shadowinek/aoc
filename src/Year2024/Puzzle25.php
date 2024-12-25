<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle25 extends AbstractPuzzle
{
    private array $keys = [];
    private array $locks = [];

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->keys as $key) {
            foreach ($this->locks as $lock) {
                $fits = true;

                foreach ($key as $i => $pin) {
                    if ($pin > $lock[$i]) {
                        $fits = false;
                    }
                }

                if ($fits) {
                    $total++;
                }
            }
        }

        return $total;
    }

    public function runPart02(): string
    {
        $this->loadData();

        return 0;
    }

    private function loadData(): void
    {
        $i = 0;
        $empty = array_fill(0, 5, 0);
        $isKey = null;

        foreach ($this->data as $row => $line) {
            if ($line === '') {
                $i++;
                $isKey = null;
                continue;
            }

            foreach (str_split($line) as $column => $char) {
                if (is_null($isKey)) {
                    $isKey = $char === '#';

                    if ($isKey) {
                        $this->keys[$i] = $empty;
                    } else {
                        $this->locks[$i] = $empty;
                    }

                    break;
                }

                if ($isKey) {
                    if ($char === '#') {
                        $this->keys[$i][$column]++;
                    }
                } else {
                    if ($char === '.') {
                        $this->locks[$i][$column]++;
                    }
                }
            }
        }
    }
}
