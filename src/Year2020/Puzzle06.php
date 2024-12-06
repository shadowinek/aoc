<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle06 extends AbstractPuzzle
{
    private array $groups = [];
    private array $groupSizes = [];

    public function runPart01(): int
    {
        $this->loadData01();

        return array_sum(array_map('count', $this->groups));
    }

    public function runPart02(): int
    {
        $this->loadData02();

        $total = 0;

        foreach ($this->groups as $group => $answers) {
            foreach ($answers as $answer) {
                if ($answer === $this->groupSizes[$group]) {
                    $total++;
                }
            }
        }

        return $total;
    }

    private function loadData01(): void
    {
        $i = 0;

        foreach ($this->data as $line) {
            if (empty($line)) {
                $i++;
                continue;
            }

            foreach (str_split($line) as $char) {
                $this->groups[$i][$char] = true;
            }
        }
    }

    private function loadData02(): void
    {
        $i = 0;
        $count = 0;

        foreach ($this->data as $line) {
            if (empty($line)) {
                $this->groupSizes[$i] = $count;
                $i++;
                $count = 0;
                continue;
            }

            foreach (str_split($line) as $char) {
                if (!isset($this->groups[$i][$char])) {
                    $this->groups[$i][$char] = 1;
                } else {
                    $this->groups[$i][$char]++;
                }
            }

            $count++;
        }

        $this->groupSizes[$i] = $count;
    }
}
