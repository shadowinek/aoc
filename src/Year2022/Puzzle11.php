<?php

namespace Shadowinek\AdventOfCode\Year2022;

use Closure;
use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle11 extends AbstractPuzzle
{
    private array $monkes = [];

    private array $inspected = [];

    public function runPart01(): int
    {
        $this->loadData();

        for ($i = 0; $i < 20; $i++) {
            foreach ($this->monkes as $monke) {
                while ($item = array_shift($monke->items)) {

                    $newItem = $monke->calculate($item);
                    $newItem = floor($newItem / 3);

                    if ($newItem % $monke->divisible === 0) {
                        $newMonke = $monke->true;
                    } else {
                        $newMonke = $monke->false;
                    }

                    $this->monkes[$newMonke]->items[] = $newItem;

                    $this->inspected[$monke->number]++;
                }
            }
        }

        rsort($this->inspected);

        return $this->inspected[0] * $this->inspected[1];
    }

    public function runPart02(): int
    {
        $this->loadData();

        $modulo = 1;

        foreach ($this->monkes as $monke) {
            $modulo *= $monke->divisible;
        }

        for ($i = 0; $i < 10000; $i++) {
            foreach ($this->monkes as $monke) {
                while ($item = array_shift($monke->items)) {

                    $newItem = $monke->calculate($item);

                    if ($newItem % $monke->divisible === 0) {
                        $newMonke = $monke->true;
                    } else {
                        $newMonke = $monke->false;
                    }

                    $this->monkes[$newMonke]->items[] = $newItem % $modulo;

                    $this->inspected[$monke->number]++;
                }
            }
        }

        rsort($this->inspected);

        return $this->inspected[0] * $this->inspected[1];
    }

    private function loadData(): void
    {
        foreach ($this->data as $data) {
            $data = trim($data);

            if (str_starts_with($data, 'Monkey ')) {
                $number = str_replace(['Monkey ', ':'], '', $data);
                $this->monkes[$number] = new Monke((int)$number);
                $currentMonke = $this->monkes[$number];
                $this->inspected[$number] = 0;
            } elseif (str_starts_with($data, 'Starting items: ')) {
                $items = str_replace('Starting items: ', '', $data);
                $currentMonke->items = explode(', ', $items);
            } elseif (str_starts_with($data, 'Operation: new = ')) {
                $operation = str_replace('Operation: new = ', '', $data);
                $operation = str_replace('old', '$old', $operation);
                $currentMonke->function = eval("return function (\$old) { return $operation; };");
            } elseif (str_starts_with($data, 'Test: divisible by ')) {
                $currentMonke->divisible = (int)str_replace('Test: divisible by ', '', $data);
            } elseif (str_starts_with($data, 'If true: throw to monkey ')) {
                $currentMonke->true = str_replace('If true: throw to monkey ', '', $data);
            } elseif (str_starts_with($data, 'If false: throw to monkey ')) {
                $currentMonke->false = str_replace('If false: throw to monkey ', '', $data);
            }
        }
    }
}

class Monke
{

    public array $items = [];
    public Closure $function;
    public int $divisible;
    public int $true;
    public int $false;

    public function __construct(
        public readonly int $number,
    )
    {
    }

    public function calculate(int $old): int
    {
        $func = $this->function;

        return $func($old);
    }
}
