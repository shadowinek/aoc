<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle05 extends AbstractPuzzle
{
    private array $pages = [];
    private array $prints = [];

    public function runPart01(): mixed
    {
        $this->loadData();

        $total = 0;

        foreach ($this->prints as $print) {
            if ($this->isValid($print)) {
                $total += $this->findMiddle($print);
            }
        }

        return $total;
    }

    private function isValid(array $print): bool
    {
        while (count($print) > 1) {
            $page = array_shift($print);
            $needle = $this->pages[$page] ?? [];

            if (!empty(array_intersect($needle, $print))) {
                return false;
            }
        }

        return true;
    }

    private function findMiddle(array $print): int
    {
        $count = floor(count($print) / 2);

        return $print[$count];
    }

    public function runPart02(): mixed
    {
        $this->loadData();

        $total = 0;

        foreach ($this->prints as $print) {
            if (!$this->isValid($print)) {
                $total += $this->processInvalid($print);
            }
        }

        return $total;
    }

    private function processInvalid(array $print): int
    {
        $correct = [];

        while (count($print) > 1) {
            $page = array_shift($print);
            $needle = $this->pages[$page] ?? [];

            $intersect = array_intersect($needle, $print);

            if (!empty($intersect)) {
                $print = $this->reorder($print, $page, $intersect);
            } else {
                $correct[] = $page;
            }
        }

        return $this->findMiddle($correct);
    }

    private function reorder(array $print, int $page, array $intersect): array
    {
        $newPage = reset($intersect);

        $newPrint[] = $newPage;

        while ($replace = array_shift($print)) {
            if ($replace == $newPage) {
                $replace = $page;
            }

            $newPrint[] = $replace;
        }

        return $newPrint;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            if (str_contains($line, '|')) {
                list ($x, $y) = explode('|', $line);
                $this->pages[$y][] = $x;
            } elseif (str_contains($line, ',')) {
                $this->prints[] = explode(',', $line);
            }
        }
    }
}
