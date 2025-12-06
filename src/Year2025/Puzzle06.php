<?php

namespace Shadowinek\AdventOfCode\Year2025;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle06 extends AbstractPuzzle
{
    private array $numbers = [];
    private array $operations = [];

    private const string ADD = '+';
    private const string MULTIPLY = '*';

    private function loadData(): void
    {
        $count = count($this->data);

        foreach ($this->data as $row => $line) {
            $i = 0;
            $data = explode(' ', $line);

            foreach ($data as $number) {
                if (empty($number)) {
                    continue;
                }

                if ($row === $count - 1) {
                    $this->operations[$i++] = $number;
                } else {
                    $this->numbers[$i++][] = (int)$number;
                }
            }
        }
    }

    private function loadData2(): void
    {
        $count = count($this->data);
        $columns = [];
        $operations = [];

        foreach ($this->data as $row => $line) {
            $i = 0;
            $data = str_split($line);

            foreach ($data as $number) {
                if ($row === $count - 1) {
                    $operations[$i++] = $number;
                } else {
                    $columns[$i++][] = $number;
                }
            }
        }

        $i = 0;
        foreach ($columns as $column) {
            $number = trim(implode('', $column));

            if (empty($number)) {
                $i++;
                continue;
            }

            $this->numbers[$i][] = (int)$number;
        }

        $i = 0;
        foreach ($operations as $operation) {
            if ($operation !== ' ') {
                $this->operations[$i++] = $operation;
            }
        }
    }

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->operations as $index => $operation) {
            $total += $this->calculate($operation, $this->numbers[$index]);
        }

        return $total;
    }

    private function calculate(string $operation, array $numbers): int
    {
        $result = 0;

        while ($number = array_shift($numbers)) {
            switch ($operation) {
                case self::ADD:
                    $result += $number;
                    break;
                case self::MULTIPLY:
                    $result = $result === 0 ? $number : $result * $number;
                    break;
            }
        }

        return $result;
    }

    public function runPart02(): int
    {
        $this->loadData2();

        print_r($this->numbers);

        $total = 0;

        foreach ($this->operations as $index => $operation) {
            $total += $this->calculate($operation, $this->numbers[$index]);
        }

        return $total;
    }
}

