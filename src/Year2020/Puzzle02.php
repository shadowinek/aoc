<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private array $passwords = [];

    public function runPart01(): int
    {
        $this->loadData();
        $valid = 0;

        foreach ($this->passwords as $password) {
            $count = substr_count($password['password'], $password['char']);

            if ($count >= $password['min'] && $count <= $password['max']) {
                $valid++;
            }
        }

        return $valid;
    }

    public function runPart02(): int
    {
        $this->loadData();
        $valid = 0;

        foreach ($this->passwords as $password) {
            $count = 0;

            if ($password['password'][$password['min'] - 1] === $password['char']) {
                $count++;
            }

            if ($password['password'][$password['max'] - 1] === $password['char']) {
                $count++;
            }

            if ($count === 1) {
                $valid++;
            }
        }

        return $valid;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            preg_match('/(\d+)-(\d+) (\w): (\w+)/', $line, $matches);

            $this->passwords[] = [
                'min' => (int)$matches[1],
                'max' => (int)$matches[2],
                'char' => $matches[3],
                'password' => $matches[4]
            ];
        }
    }
}
