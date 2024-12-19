<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle07 extends AbstractPuzzle
{
    private array $bags = [];
    private const string SHINY_GOLD = 'shiny gold';
    private array $cache = [];

    public function runPart01(): int
    {
        $this->loadData();

        $fitIn = [];
        $toCheck = [self::SHINY_GOLD];

        while (!empty($toCheck)) {
            $currentColor = array_shift($toCheck);
            $currentBag = $this->bags[$currentColor];

            /** @var Bag $currentBag */
            foreach ($currentBag->getFitIn() as $color) {
                echo $color . PHP_EOL;

                if (!isset($fitIn[$color])) {
                    $fitIn[$color] = true;
                    $toCheck[] = $color;
                }
            }
        }

        return array_sum($fitIn);
    }

    public function runPart02(): int
    {
        $this->loadData();

        return $this->getCount(self::SHINY_GOLD);
    }

    private function getCount(string $color): int
    {
        if (isset($this->cache[$color])) {
            return $this->cache[$color];
        }

        $currentBag = $this->bags[$color];

        $count = 0;

        foreach ($currentBag->getBags() as $innerColor => $innerCount) {
            $count += $innerCount + $innerCount * $this->getCount($innerColor);
        }

        $this->cache[$color] = $count;

        return $count;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            [$color, $contains] = explode(' bags contain ', $line);

            $bag = new Bag();

            $matches = [];
            preg_match_all('/(\d+) ([a-z ]+) bag/', $contains, $matches);

            foreach ($matches[1] as $id => $match) {
                $bag->addBag($matches[2][$id], (int)$match);
            }

            $this->bags[$color] = $bag;
        }

        foreach ($this->bags as $color => $bag) {
            foreach ($bag->getBags() as $innerColor => $count) {
                $this->bags[$innerColor]->addFitIn($color);
            }
        }
    }
}

class Bag
{
    private array $bags = [];
    private array $fitIn = [];

    public function addBag(string $color, int $count): void
    {
        $this->bags[$color] = $count;
    }

    public function getBags(): array
    {
        return $this->bags;
    }

    public function addFitIn(string $color): void
    {
        $this->fitIn[$color] = $color;
    }

    public function getFitIn(): array
    {
        return $this->fitIn;
    }
}
