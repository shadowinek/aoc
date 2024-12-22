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

        $total = 0;

        foreach ($this->numbers as $number) {
            $secret = $this->findSecrets($number, true);
            $total += $secret;
            echo "$number: $secret" . PHP_EOL;

            $string = str_split((string)$number);
            $last = end($string);

            foreach ($this->bananas[$number] as $price) {
                $this->patterns[$number][] = $price - $last;
                $last = $price;
            }
        }

//        print_r(array_shift($this->bananas));
//        print_r(array_shift($this->patterns));

        $this->findBestPattern();


        asort($this->best);
        print_r($this->best);

        return $total;
    }

    private function findBestPattern(): void
    {
        $temp = $this->patterns;
        $patterns = array_shift($temp);

        $pattern = [];

        while (!empty($patterns)) {
            $pattern[] = array_shift($patterns);

            if (count($pattern) < 4) {
                continue;
            }

            if (count($pattern) === 5) {
                array_shift($pattern);
            }

            $this->findPrices($pattern);
        }
    }

    private function findPrices(array $pattern): void
    {
        $key = implode(';', $pattern);
        $this->best[$key] = 0;

        foreach ($this->patterns as $id => $patterns) {
            echo "ID: $id" . PHP_EOL;

            foreach ($patterns as $index => $value) {

                if ($index > 1995) {
                    continue;
                }

                if ($value === $pattern[0] && $patterns[$index + 1] === $pattern[1] && $patterns[$index + 2] === $pattern[2] && $patterns[$index + 3] === $pattern[3]) {
                    $this->best[$key] += $this->bananas[$id][$index + 4];
                    break;
                }
            }
        }
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
        $number = $this->mixAndPruner($number, $mul);

        $div = floor($number / 32);
        $number = $this->mixAndPruner($number, $div);

        $mul = $number * 2048;
        $number = $this->mixAndPruner($number, $mul);

        return $number;
    }

    private function mixAndPruner(int $number, int $result): int
    {
        $mix = $number ^ $result;
        return $mix % 16777216;
    }
}
