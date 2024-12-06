<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private array $inputs = [];

    public function runPart01(): mixed
    {
        $this->loadData();
        $safe = 0;

        foreach ($this->inputs as $input) {
            if ($this->isSafe($input)) {
                $safe++;
            }
        }

        return $safe;
    }

    private function isSafe(array $input): bool
    {
        $isGrowing = null;

        for ($i = 0; $i < count($input) - 1; $i++) {
            $diff = $input[$i] - $input[$i + 1];

            if (abs($diff) > 3 || abs($diff) < 1) {
                return false;
            }

            if ($isGrowing === null) {
                $isGrowing = $diff > 0;
            } else {
                if ($isGrowing !== ($diff > 0)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function isSafeWithErrors(array $input): bool
    {
        $isGrowing = null;

        for ($i = 0; $i < count($input) - 1; $i++) {
            $diff = $input[$i] - $input[$i + 1];

            if (abs($diff) > 3 || abs($diff) < 1 || ($isGrowing !== null && $isGrowing !== ($diff > 0))) {
                return $this->isSubarraySafe($input);
            }

            if ($isGrowing === null) {
                $isGrowing = $diff > 0;
            }
        }

        return true;
    }

    private function isSubarraySafe(array $input): bool
    {
        for ($i = 0; $i < count($input); $i++) {
            $subarray = $input;
            unset($subarray[$i]);

            if ($this->isSafe(array_values($subarray))) {
                return true;
            }
        }

        return false;
    }

    public function runPart02(): mixed
    {
        $this->loadData();
        $safe = 0;

        foreach ($this->inputs as $input) {
            if ($this->isSafeWithErrors($input)) {
                $safe++;
            }
        }

        return $safe;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->inputs[] = $this->parseNumbers($line);
        }
    }
}
