<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle19 extends AbstractPuzzle
{
    private array $stripes;
    private array $patterns = [];
    private string $regexp;
    private array $cache = [];

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->patterns as $pattern) {
            echo "Checking: " . $pattern . PHP_EOL;
            if ($this->isValid($pattern)) {
                echo 'Valid: ' . $pattern . PHP_EOL;
                $total++;
            } else {
                echo 'Invalid: ' . $pattern . PHP_EOL;
            }

            echo '---' . PHP_EOL;
        }

        return $total;
    }

    private function isValid(string $pattern): bool
    {
        return preg_match($this->regexp, $pattern);
    }

    public function runPart02(): string
    {
        $this->loadData();

        $total = 0;

        foreach ($this->patterns as $pattern) {
            echo "Checking: " . $pattern . PHP_EOL;

            $total += $this->countValid($pattern);
        }

        return $total;
    }

    private function countValid(string $pattern): int
    {
        if (isset($this->cache[$pattern])) {
            return $this->cache[$pattern];
        }

        if (strlen($pattern) === 0) {
            return 1;
        }

        $count = 0;

        if ($this->isValid($pattern)) {
            $toCheck = [];

            foreach ($this->stripes as $stripe) {
                if (str_starts_with($pattern, $stripe)) {
                    $toCheck[] = $stripe;
                }
            }

            foreach ($toCheck as $stripe) {
                $count += $this->countValid(substr($pattern, strlen($stripe)));
            }
        }

        $this->cache[$pattern] = $count;

        return $count;
    }

    private function loadData(): void
    {
        $data = $this->data;

        $stripes = array_shift($data);
        $this->stripes = explode(", ", $stripes);

        $this->regexp = "#\b(" . implode('|', $this->stripes) . ")+\b#";

        array_shift($data);

        foreach ($data as $line) {
            $this->patterns[] = $line;
        }
    }
}
