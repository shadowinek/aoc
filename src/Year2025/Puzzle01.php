<?php

namespace Shadowinek\AdventOfCode\Year2025;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle01 extends AbstractPuzzle
{
    private array $input = [];
    private const int MIN = 0;
    private const int MAX = 99;
    private const string LEFT = 'L';
    private const string RIGHT = 'R';

    private array $direction = [
        self::LEFT => -1,
        self::RIGHT => 1,
    ];

    private function loadData(): void
    {
        foreach ($this->data as $i => $line) {
            $this->input[$i]['direction'] = $line[0];
            $this->input[$i]['steps'] = $this->direction[$line[0]] * $this->parseNumbers($line)[0];
        }

//        print_r($this->input);
    }

    public function runPart01(): int
    {
        $this->loadData();

        $position = 50;
        $total = 0;

        foreach ($this->input as $input) {
            $position += $input['steps'];

            while ($position < self::MIN) {
                $position += self::MAX + 1;
            }

            while ($position > self::MAX) {
                $position -= self::MAX + 1;
            }

            if ($position === 0) {
                $total++;
            }
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $position = 50;
        $total = 0;

        foreach ($this->input as $input) {
            $position += $input['steps'];

            while ($position < self::MIN) {
                $position += self::MAX + 1;
                $total++;
            }

            while ($position > self::MAX) {
                $position -= self::MAX + 1;
                $total++;
            }
        }

        return $total;
    }
}
