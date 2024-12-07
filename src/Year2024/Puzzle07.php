<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle07 extends AbstractPuzzle
{
    private array $equations = [];
    private array $cache = [];

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->equations as $equation) {
            if ($this->isValid($equation)) {
                $total += $equation['result'];
            }
        }

        return $total;
    }

    private function calculate(array $numbers, array $operations): int
    {
        $result = array_shift($numbers);

        foreach ($operations as $operation) {

            switch ($operation) {
                case '+':
                    $result += array_shift($numbers);
                    break;
                case '*':
                    $result *= array_shift($numbers);
                    break;
                case '|':
                    $result .= array_shift($numbers);
                    break;
                default:
                    break;
            }
        }

        return $result;
    }

    private function isValid(array $equation, bool $part2 = false): bool
    {
        $numbers = $equation['numbers'];
        $count = count($numbers);
        $operations = $this->getOperations($count - 1, $part2);

        $result = $equation['result'];

        foreach ($operations as $operation) {
            if ($this->calculate($numbers, $operation) === $result) {
                return true;
            }
        }

        return false;
    }

    private function getOperations(int $count, bool $part2 = false): array
    {
        if (isset($this->cache[$count])) {
            return $this->cache[$count];
        }

        $operands = ['+', '*'];

        if ($part2) {
            $operands[] = '|';
        }

        $operations = $this->generateCombinations($operands, $count);

        $this->cache[$count] = $operations;

        return $operations;
    }

    private function generateCombinations(array $operands, int $count, array $current = []): array
    {
        if ($count === 0) {
            return [$current];
        }

        $combinations = [];
        foreach ($operands as $operand) {
            $newCombination = array_merge($current, [$operand]);
            $combinations = array_merge($combinations, $this->generateCombinations($operands, $count - 1, $newCombination));
        }

        return $combinations;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->equations as $equation) {
            if ($this->isValid($equation, true)) {
                $total += $equation['result'];
            }
        }

        return $total;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            [$result, $numbers] = explode(': ', $line);
            $this->equations[] = [
                'result' => (int)$result,
                'numbers' => $this->parseNumbers($numbers),
            ];
        }
    }
}
