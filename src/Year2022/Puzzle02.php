<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle02 extends AbstractPuzzle
{
    private const ROCK = 1;
    private const PAPER = 2;
    private const SCISSORS = 3;

    private const LOST = 0;
    private const DRAW = 3;
    private const WON = 6;

    private array $rounds;

    private const ELF = 'elf';
    private const ME = 'me';
    private const RESULT = 'result';

    private array $mapping01 = [
        'A' => self::ROCK,
        'B' => self::PAPER,
        'C' => self::SCISSORS,
        'X' => self::ROCK,
        'Y' => self::PAPER,
        'Z' => self::SCISSORS,
    ];

    private array $mapping02 = [
        'A' => self::ROCK,
        'B' => self::PAPER,
        'C' => self::SCISSORS,
        'X' => self::LOST,
        'Y' => self::DRAW,
        'Z' => self::WON,
    ];

    public function runPart01(): int
    {
        $this->loadData01();

        $result = 0;

        foreach ($this->rounds as $round) {
            $result += $round[self::ME];
            $result += $this->getResult01($round[self::ELF], $round[self::ME]);
        }

        return $result;
    }

    public function runPart02(): int
    {
        $this->loadData02();

        $result = 0;

        foreach ($this->rounds as $round) {
            $result += $round[self::RESULT];
            $result += $this->getResult02($round[self::ELF], $round[self::RESULT]);
        }

        return $result;
    }

    private function loadData01(): void
    {
        foreach ($this->data as $index => $data) {
            $input = explode(' ', $data);
            $this->rounds[$index] = [
                self::ELF => $this->mapping01[$input[0]],
                self::ME => $this->mapping01[$input[1]],
            ];
        }
    }

    private function loadData02(): void
    {
        foreach ($this->data as $index => $data) {
            $input = explode(' ', $data);
            $this->rounds[$index] = [
                self::ELF => $this->mapping02[$input[0]],
                self::RESULT => $this->mapping02[$input[1]],
            ];
        }
    }

    private function getResult01(int $elf, int $me): int
    {
        if ($elf === $me) {
            return self::DRAW;
        }

        if (
            $elf === self::ROCK && $me === self::PAPER ||
            $elf === self::PAPER && $me === self::SCISSORS ||
            $elf === self::SCISSORS && $me === self::ROCK
        ) {
            return self::WON;
        }

        if (
            $elf === self::ROCK && $me === self::SCISSORS ||
            $elf === self::PAPER && $me === self::ROCK ||
            $elf === self::SCISSORS && $me === self::PAPER
        ) {
            return self::LOST;
        }

        return -1;
    }

    private function getResult02(int $elf, int $result): int
    {
        if ($result === self::DRAW) {
            return $elf;
        }

        switch ($result) {
            case self::DRAW:
                return $elf;
            case self::LOST:
                switch ($elf) {
                    case self::ROCK:
                        return self::SCISSORS;
                    case self::PAPER:
                        return self::ROCK;
                    case self::SCISSORS:
                        return self::PAPER;
                }
                break;
            case self::WON:
                switch ($elf) {
                    case self::ROCK:
                        return self::PAPER;
                    case self::PAPER:
                        return self::SCISSORS;
                    case self::SCISSORS:
                        return self::ROCK;
                }
                break;
        }

        return -1;
    }
}
