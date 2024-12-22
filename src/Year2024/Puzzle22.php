<?php

namespace Shadowinek\AdventOfCode\Year2024;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle22 extends AbstractPuzzle
{
    private array $numbers = [];
    private array $bananas = [];
    private array $patterns = [];
    private array $best = [];

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->numbers as $number) {
            $secret = $this->findSecrets($number);
            $total += $secret;
            echo "$number: $secret" . PHP_EOL;
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData();

        foreach ($this->numbers as $number) {
            $secret = $this->findSecrets($number, true);
            echo "$number: $secret" . PHP_EOL;

            $string = str_split((string)$number);
            $last = end($string);

            $pattern = [];
            $this->patterns[$number] = [];

            foreach ($this->bananas[$number] as $price) {
                $diff = $price - $last;
                $last = $price;
                $pattern[] = $diff;

                if (count($pattern) < 4) {
                    continue;
                }

                if (count($pattern) === 5) {
                    array_shift($pattern);
                }

                $key = implode(';', $pattern);
                if (!isset($this->patterns[$number][$key])) {
                    $this->patterns[$number][$key] = $price;

                    if (!isset($this->best[$key])) {
                        $this->best[$key] = $price;
                    } else {
                        $this->best[$key] += $price;
                    }
                }
            }
        }

        asort($this->best);
        print_r($this->best);

        return max($this->best);
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $this->numbers[] = (int)$line;
        }
    }

    private function findSecrets(int $number, bool $countBananas = false): int
    {
        $id = $number;

        for ($i = 0; $i < 2000; $i++) {
            $number = $this->findSecret($number);
            if ($countBananas) {
                $string = str_split($number);
                $this->bananas[$id][] = end($string);
            }
        }

        return $number;
    }

    private function findSecret(int $number): int
    {
        $mul = $number * 64;
        $number = $this->mixAndPrune($number, $mul);

        $div = floor($number / 32);
        $number = $this->mixAndPrune($number, $div);

        $mul = $number * 2048;

        return $this->mixAndPrune($number, $mul);
    }

    private function mixAndPrune(int $number, int $result): int
    {
        $mix = $number ^ $result;

        return $mix % 16777216;
    }
}
