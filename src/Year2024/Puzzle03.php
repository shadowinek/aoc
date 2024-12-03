<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $muls = [];

    public function runPart01(): int
    {
        $this->loadData01();

        $result = 0;

        foreach ($this->muls as $set) {
            foreach ($set as $mul) {
                    list($x, $y) = $this->parseNumbers($mul);

                    $result += $x * $y;
                }
        }

        return $result;
    }

    public function runPart02(): int
    {
        $this->loadData02();

        $result = 0;
        $isOn = true;

        foreach ($this->muls as $set) {
            foreach ($set as $mul) {
                if ($mul === 'do()') {
                    $isOn = true;
                } elseif ($mul === 'don\'t()') {
                    $isOn = false;
                } elseif ($isOn) {
                    list($x, $y) = $this->parseNumbers($mul);

                    $result += $x * $y;
                }
            }
        }

        return $result;
    }

    private function loadData01(): void
    {
        foreach ($this->data as $line) {
            $matches = [];

            preg_match_all('%mul\(\d{1,3},\d{1,3}\)%', $line, $matches);

            $this->muls[] = $matches[0];
        }
    }

    private function loadData02(): void
    {
        foreach ($this->data as $line) {
            $matches = [];

            preg_match_all('%do\(\)|don\'t\(\)|mul\(\d{1,3},\d{1,3}\)%', $line, $matches);

            $this->muls[] = $matches[0];
        }
    }
}
