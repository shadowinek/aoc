<?php

namespace Shadowinek\AdventOfCode\Year2018;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle03 extends AbstractPuzzle
{
    private array $map;
    private array $areas;
    private array $overlap;

    public function runPart01(): int
    {
        $this->loadData();

        foreach ($this->areas as $area) {
            $this->paintArea($area);
        }

        $values = array_count_values($this->map);
        unset($values[1]);

        return array_sum($values);
    }

    public function runPart02(): int
    {
        $this->loadData();

        foreach ($this->areas as $area) {
            $this->paintArea($area);
        }

        foreach ($this->overlap as $overlap) {
            if (count($overlap) > 1) {
                foreach ($overlap as $id) {
                    unset($this->areas[$id]);
                }
            }
        }

        return reset($this->areas)['id'];
    }

    private function paintArea(array $area): void
    {
        for ($i=$area['x'];$i<$area['x']+$area['width'];$i++) {
            for ($j=$area['y'];$j<$area['y']+$area['height'];$j++) {
                $key = $i . '-' . $j;

                if (!isset($this->map[$key])) {
                    $this->map[$key] = 1;
                } else {
                    $this->map[$key]++;
                }

                $this->overlap[$key][] = $area['id'];
            }
        }
    }

    private function loadData(): void
    {
        foreach ($this->data as $line) {
            $matches = [];
            preg_match('/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/', $line, $matches);

            $this->areas[$matches[1]] = [
                'id' => $matches[1],
                'x' => $matches[2],
                'y' => $matches[3],
                'width' => $matches[4],
                'height' => $matches[5],
            ];
        }
    }
}
