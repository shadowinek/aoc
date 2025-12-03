<?php

namespace Shadowinek\AdventOfCode\Year2025;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $batteries = [];

     private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->batteries[] = str_split($line);
        }
    }

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->batteries as $battery) {
            $total += $this->findBattery($battery, 2);
        }

        return $total;
    }

    private function findMax(array $battery): int
    {
        $max = max($battery);

        return array_search($max, $battery);
    }

    private function findBattery(array $battery, int $size): int
    {
        $newBattery = [];

        while (count($newBattery) < $size) {
            $length = count($battery);
            $batteryToSplice = $battery;
            $toSearch = array_splice($batteryToSplice, 0, $length - $size + count($newBattery) + 1);
            $find = $this->findMax($toSearch);
            $newBattery[] = $toSearch[$find];
            $battery = array_splice($battery, $find + 1);
        }

        return (int) implode('', $newBattery);
    }

    public function runPart02(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->batteries as $battery) {
            $total += $this->findBattery($battery, 12);
        }

        return $total;
    }
}
