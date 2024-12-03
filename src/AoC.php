<?php

namespace Shadowinek\AdventOfCode;

class AoC
{
    private function readInput(int $year, int $puzzle, bool $real_input, string $second_input): array
    {
        return file(
            sprintf(
                __DIR__ . '/../data/input%s/%d/input_%s%s',
                $real_input ? '' : '_test',
                $year,
                $this->getNumberString($puzzle),
                $second_input
            ),FILE_IGNORE_NEW_LINES);
    }

    private function getNumberString(string $number): string
    {
        return sprintf('%02d', $number);
    }

    public function execute(int $year, int $puzzle, int $part, bool $real_input, string $second_input = ''): void
    {
        $data = $this->readInput($year, $puzzle, $real_input, $second_input);
        $expected = include_once(__DIR__ . "/../data/output/{$year}.php");

        $class = sprintf(
            'Shadowinek\\AdventOfCode\\Year%d\\Puzzle%02d',
            $year,
            $this->getNumberString($puzzle)
        );

        echo $class . PHP_EOL;

        $method = 'runPart' . $this->getNumberString($part);

        echo 'Year:     ' . $year . PHP_EOL;
        echo 'Puzzle:   ' . $this->getNumberString($puzzle) . PHP_EOL;
        echo 'Part:     ' . $this->getNumberString($part) . PHP_EOL;
        echo 'Dataset:  ' . ($real_input ? 'real' : 'test') . PHP_EOL;
        echo '===============' . PHP_EOL;
        echo 'Output:   ' . (class_exists($class) ? (new $class($data))->$method() : 'This puzzle is not defined yet!') . PHP_EOL;
        echo 'Expected: ' . ($expected[$puzzle][$part][$real_input] ?? '-') . PHP_EOL;
    }
}
