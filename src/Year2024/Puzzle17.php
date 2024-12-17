<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle17 extends AbstractPuzzle
{
    private int $a;
    private int $b;
    private int $c;
    private array $stack;
    private array $output = [];
    private int $pointer = 0;

    public function runPart01(): string
    {
        $this->loadData();
        $this->runProgram();

        return implode(',', $this->output);
    }

    private function runProgram(): void
    {
        while (true) {
            $opcode = $this->stack[$this->pointer++] ?? null;
            $operand = $this->stack[$this->pointer++] ?? null;

            if ($opcode === null || $operand === null) {
                break;
            }

            switch ($opcode) {
                // adv
                case 0:
                    $this->dv('a', $operand);
                    break;
                // bxl
                case 1:
                    $this->bxl($operand);
                    break;
                // bst
                case 2:
                    $this->bst($operand);
                    break;
                // jnz
                case 3:
                    $this->jnz($operand);
                    break;
                // bxc
                case 4:
                    $this->bxc();
                    break;
                // out
                case 5:
                    $this->out($operand);
                    break;
                // bdv
                case 6:
                    $this->dv('b', $operand);
                    break;
                // cdv
                case 7:
                    $this->dv('c', $operand);
                    break;
                default:
                    echo "Unknown opcode: {$opcode}" . PHP_EOL;
                    exit;
            }
        }

    }

    private function dv(string $register, int $operand): void
    {
        $value = $this->getOperandValue($operand);

        $pow = bcpow(2, $value);
        $div = bcdiv($this->a, $pow);

        $this->$register = (int)$div;
    }

    private function bxl(int $operand): void
    {
        $this->b = $this->b ^ $operand;
    }

    private function bxc(): void
    {
        $this->b = $this->b ^ $this->c;
    }

    private function out(int $operand): void
    {
        $this->output[] = $this->getOperandValue($operand) % 8;
    }

    private function jnz(int $operand): void
    {
        if ($this->a !== 0) {
            $this->pointer = $operand;
        }
    }

    private function bst(int $operand): void
    {
        $this->b = $this->getOperandValue($operand) % 8;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $a = 1;

        while (true) {
//            echo "Trying: $a" . PHP_EOL;
            $this->output = [];
            $this->b = $this->c = 0;
            $this->a = $a;
            $this->pointer = 0;

            $this->runProgram();
//            echo "Stack:  " . implode(',', $this->stack) . PHP_EOL;
//            echo "Output: " . implode(',', $this->output) . PHP_EOL;

            if ($this->isValid()) {
//                echo "Valid: $a" . PHP_EOL;

                if (count($this->stack) === count($this->output)) {
                    return $a;
                }

                $a = $a << 3; // same as $a * 8, but a bit faster
            } else {
                $a++;
            }
        }
    }

    private function isValid(): bool
    {
        return str_ends_with(implode(',', $this->stack), implode(',', $this->output));
    }

    private function loadData(): void
    {
        bcscale(2);

        $this->a = $this->parseNumbers($this->data[0])[0];
        $this->b = $this->parseNumbers($this->data[1])[0];
        $this->c = $this->parseNumbers($this->data[2])[0];

        $this->stack = $this->parseNumbers($this->data[4]);
    }

    private function getOperandValue(int $operand): int
    {
        return match ($operand) {
            4 => $this->a,
            5 => $this->b,
            6 => $this->c,
            7 => false,
            default => $operand,
        };
    }
}
