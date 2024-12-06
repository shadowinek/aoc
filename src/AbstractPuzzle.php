<?php

namespace Shadowinek\AdventOfCode;

abstract class AbstractPuzzle
{
    public function __construct(protected readonly array $data)
    {
    }

    abstract public function runPart01(): mixed;

    abstract public function runPart02(): mixed;

    protected function parseNumbers(string $input): array
    {
        $numbers = [];

        preg_match_all('/(-?\d+)/', $input, $numbers);

        return $numbers[0];
    }
}
