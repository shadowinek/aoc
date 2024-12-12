<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle11 extends AbstractPuzzle
{
    private array $stones;

    public function runPart01(): int
    {
        $this->loadData();

        for ($i = 0; $i < 25; $i++) {
            $this->blink();
        }

        return array_sum($this->stones);
    }

    public function runPart02(): int
    {
        $this->loadData();

        for ($i = 0; $i < 75; $i++) {
            $this->blink();
        }

        return array_sum($this->stones);
    }

    private function loadData(): void
    {
        foreach (explode(' ', $this->data[0]) as $stone) {
            $this->stones[$stone] = 1;
        }
    }

    private function blink(): void
    {
        $stones = $this->stones;

        foreach ($stones as $stone => $count) {
            $stone = (string)$stone;

            if ($count > 0) {
                $this->removeStones($stone, $count);

                if ($stone === '0') {
                    $this->addStones('1', $count);
                } elseif (strlen($stone) % 2 === 0) {
                    $parts = str_split($stone, strlen($stone) / 2);

                    $this->addStones((string)((int)$parts[0]), $count);
                    $this->addStones((string)((int)$parts[1]), $count);
                } else {
                    $this->addStones((string)((int)$stone * 2024), $count);
                }
            }
        }

        $this->stones = array_filter($this->stones);
    }

    private function addStones(string $stone, int $count): void
    {
        if (isset($this->stones[$stone])) {
            $this->stones[$stone] += $count;
        } else {
            $this->stones[$stone] = $count;
        }
    }

    private function removeStones(string $stone, int $count): void
    {
        $this->stones[$stone] -= $count;
    }
}
