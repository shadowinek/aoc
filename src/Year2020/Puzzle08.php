<?php

namespace Shadowinek\AdventOfCode\Year2020;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle08 extends AbstractPuzzle
{
    private int $accumulator = 0;
    private array $instructions = [];
    private int $pointer = 0;
    private array $cache = [];

    public function runPart01(): int
    {
        $this->loadData();

        while (true) {
            if (isset($this->cache[$this->pointer])) {
                return $this->accumulator;
            } else {
                $this->cache[$this->pointer] = true;
            }

            $current = $this->instructions[$this->pointer];

            $this->executeInstruction($current);
        }
    }

    private function executeInstruction(array $current): void
    {
        [$instruction, $value] = $current;

        switch ($instruction) {
            case 'acc':
                $this->accumulator += $value;
                $this->pointer++;
                break;
            case 'jmp':
                $this->pointer += $value;
                break;
            default:
                $this->pointer++;
                break;
        }
    }

    private function isFixed(): bool
    {
        while (true) {
            if (isset($this->cache[$this->pointer])) {
                return false;
            } else {
                $this->cache[$this->pointer] = true;
            }

            if (!isset($this->instructions[$this->pointer])) {
                return true;
            }

            $current = $this->instructions[$this->pointer];

            $this->executeInstruction($current);
        }
    }

    public function runPart02(): int
    {
        $this->loadData();

        $instructions = $this->instructions;

        foreach ($this->instructions as $id => $instruction) {
            $this->pointer = 0;
            $this->cache = [];
            $this->accumulator = 0;
            $this->instructions = $instructions;

            if ($instruction[0] === 'nop') {
                $this->instructions[$id][0] = 'jmp';
            } elseif ($instruction[0] === 'jmp') {
                $this->instructions[$id][0] = 'nop';
            }

            if ($this->isFixed()) {
                return $this->accumulator;
            }
        }

        return 0;
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            [$instruction, $value] = explode(' ', $line);

            $this->instructions[] = [
                $instruction,
                (int) $value,
            ];
        }
    }
}
