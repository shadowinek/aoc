<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle10 extends AbstractPuzzle
{
    private int $cycle = 0;
    private int $x = 1;
    private array $readings = [];

    public function runPart01(): int
    {
        foreach ($this->data as $data) {
            $split = explode(' ', $data);

            switch ($split[0]) {
                case 'noop':
                    $this->cycle01();
                    break;
                case 'addx':
                    $this->cycle01();
                    $this->cycle01();
                    $this->x += (int)$split[1];
                    break;
            }
        }

        return array_sum($this->readings);
    }

    public function runPart02(): int
    {
        $this->cycle = -1;

        foreach ($this->data as $data) {
            $split = explode(' ', $data);

            switch ($split[0]) {
                case 'noop':
                    $this->cycle02();
                    break;
                case 'addx':
                    $this->cycle02();
                    $this->cycle02();
                    $this->x += (int)$split[1];
                    break;
            }
        }

        foreach ($this->readings as $cycle => $reading) {
            if ($cycle % 40 === 0) {
                echo PHP_EOL;
            }

            echo $reading;
        }

        echo PHP_EOL;
        echo PHP_EOL;

        return -1;
    }

    private function cycle01(): void
    {
        $this->cycle++;

        if ($this->cycle === 20 || ($this->cycle - 20) % 40 === 0) {
            $this->readings[$this->cycle] = $this->cycle * $this->x;
        }
    }

    private function cycle02(): void
    {
        $this->cycle++;
        $subcycle = $this->cycle % 40;

        if ($subcycle <= $this->x + 1 && $subcycle >= $this->x - 1) {
            $this->readings[$this->cycle] = '#';
        } else {
            $this->readings[$this->cycle] = ' ';
        }

//        echo $subcycle . ' ' . $this->x . ' ' . $this->readings[$this->cycle] . PHP_EOL;
    }
}
