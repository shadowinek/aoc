<?php

namespace Shadowinek\AdventOfCode\Year2025;

use Shadowinek\AdventOfCode\AbstractPuzzle;

class Puzzle05 extends AbstractPuzzle
{
    private array $fresh = [];
    private array $available = [];

    private function loadData(): void
    {
        $loadAvailable = false;

        foreach ($this->data as $line) {
            if ($line === '') {
                $loadAvailable = true;
                continue;
            }

            if ($loadAvailable) {
                $this->available[] = (int)$line;
            } else {
                [$min, $max] = explode('-', $line);
                $this->fresh[$line] = [
                    'min' => (int)$min,
                    'max' => (int)$max
                ];
            }
        }
    }

    public function runPart01(): int
    {
        $this->loadData();

        $total = 0;

        foreach ($this->available as $ingredient) {
            foreach ($this->fresh as $fresh) {
                if ($ingredient >= $fresh['min'] && $ingredient <= $fresh['max']) {
                    $total++;
                    break;
                }
            }
        }

        return $total;
    }

    public function runPart02(): int
    {
        $this->loadData();

        $total = 0;
        $merged = [];

        while ($current = array_shift($this->fresh)) {
            $currentMin = $current['min'];
            $currentMax = $current['max'];

            foreach ($this->fresh as $key => $fresh) {
                $min = $fresh['min'];
                $max = $fresh['max'];

                if (
                    ($currentMin >= $min && $currentMin <= $max)
                    || ($currentMax >= $min && $currentMax <= $max)
                    || ($min >= $currentMin && $min <= $currentMax)
                    || ($max >= $currentMin && $max <= $currentMax)
                ) {
                    unset($this->fresh[$key]);
                    $newMin = min($currentMin, $min);
                    $newMax = max($currentMax, $max);
                    $this->fresh[$newMin . '-' . $newMax] = [
                        'min' => $newMin,
                        'max' => $newMax
                    ];
                    continue 2;
                }
            }

            $merged[] = $current;
        }

        foreach ($merged as $toCount) {
            $total += $toCount['max'] - $toCount['min'] + 1;
        }

        return $total;
    }
}

